<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Contact;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use function GuzzleHttp\uri_template;

class ContactsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function contacts_can_be_fetch_by_authenticated_user()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $contact = factory(Contact::class)->create(['user_id' => $user->id]);
        $anotherContact = factory(Contact::class)->create(['user_id' => $anotherUser->id]);

        $response = $this->get('/api/contacts?api_token=' . $user->api_token);
//         dd(json_decode($response->getContent()));
        $response->assertJsonCount(1)
        ->assertJson([
            'data' => [
                [
                    "data" => [
                        'contact_id' => $contact->id
                    ]
                ]
            ]
        ]);

    }

    /** @test */
    public function unauthenticated_user_redirected_to_login()
    {
        $response = $this->post('/api/contacts', array_merge($this->data(), ['api_token' => '']));
        $response->assertRedirect('/login');
        $this->assertCount(0, Contact::all());
    }

    /** @test */
    public function unauthenticated_user_can_add_contact()
    {
//        $this->withoutExceptionHandling();
        $response = $this->post('/api/contacts', $this->data());
        $contact = Contact::first();

//        dd(json_decode($response->getContent()));

        $this->assertEquals('Test Name', $contact->name);
        $this->assertEquals('1960-06-30', $contact->dob->format('Y-m-d'));
        $this->assertEquals('test@email.com', $contact->email);
        $this->assertEquals('siacom', $contact->company);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
           'data' => [
               'contact_id' => $contact->id,
           ],
            'links' => [
                'self' => $contact->path(),
            ]
        ]);
    }


    /** @test */
    public function fields_required()
    {
        collect(['name', 'email', 'dob', 'company'])
            ->each(function($field) {
                $response = $this->post('/api/contacts',
                    array_merge( $this->data(), [$field => '']));

                $response->assertSessionHasErrors($field);

                $this->assertCount(0, Contact::all());
            });

    }

    /** @test */
    public function valid_email()
    {
        $response = $this->post('/api/contacts/',
            array_merge( $this->data(), ['email' => 'NOT AN EMAIL']));

        $response->assertSessionHasErrors('email');

        $this->assertCount(0, Contact::all());

    }

    /** @test */
    public function valid_dob()
    {
        $response = $this->post('/api/contacts/',
            array_merge( $this->data(), $this->data()));

        $this->assertCount(1, Contact::all());
        $this->assertInstanceOf(Carbon::class, Contact::first()->dob);
        $this->assertEquals('1960-06-30', Contact::first()->dob->format('Y-m-d'));

    }

    /** @test */
    public function a_contact_can_be_retrieved()
    {
//        $this->withoutExceptionHandling();
        $contact = factory(Contact::class)->create(['user_id' => $this->user->id]);

        $response = $this->get('/api/contacts/' . $contact->id . '?api_token=' . $this->user->api_token);

        $response->assertJson([
            'data' =>[
                'contact_id'   => $contact->id,
                'name'         => $contact->name,
                'email'        => $contact->email,
                'dob'          => $contact->dob->format('d/m/Y'),
                'company'      => $contact->company,
                'last_updated' => $contact->updated_at->diffForHumans()
            ]
        ]);
    }

    /** @test
     *
     */
    public function only_users_contacts_can_be_retrieved()
    {
        $contact = factory(Contact::class) ->create( ['user_id' => $this->user->id] );
        $anotherUser = factory(User::class)->create();

        $response = $this->get('/api/contacts/' . $contact->id . '?api_token=' .
                    $anotherUser->api_token);

        $response->assertStatus(403);
    }

    /** @test */
    public function a_contact_can_be_patched()
    {
//        $this->withoutExceptionHandling();
        $contact = factory(Contact::class)->create(['user_id' => $this->user->id]);

        $response = $this->patch('/api/contacts/'. $contact->id, $this->data());

        $contact = $contact->fresh();


        $this->assertEquals('1960-06-30'    , $contact->dob->format('Y-m-d'));
        $this->assertEquals('Test Name'     , $contact->name);
        $this->assertEquals('test@email.com', $contact->email);
        $this->assertEquals('siacom'        , $contact->company);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'contact_id' => $contact->id,
            ],
            'links' => [
                'self' => $contact->path(),
            ]
        ]);

    }

    /** @test */
    public function only_the_owner_of_the_contact_can_patch_the_contact()
    {
        $contact = factory(Contact::class)->create();
        $anotherUser = factory(User::class)->create();


        $response = $this->patch('/api/contacts/'. $contact->id,
            array_merge($this->data(), ['api_token' => $anotherUser->api_token]));

        $response->assertStatus(403);
    }

    /** @test */
    public function contact_deleted()
    {

//        $this->withoutExceptionHandling();
        $contact = factory(Contact::class)->create(['user_id' => $this->user->id]);
        $response = $this->delete('/api/contacts/'. $contact->id,
            ['api_token' => $this->user->api_token]);
        $this->assertCount(0, Contact::all());
        $response->assertStatus(Response::HTTP_NO_CONTENT);

    }

    /** @test */
    public function only_the_owner_can_delete_contact()
    {
        $contact = factory(Contact::class)->create();
        $anotherUser = factory(User::class)->create();

        $response = $this->delete('/api/contacts/'. $contact->id,
            ['api_token' => $this->user->api_token]);

        $response->assertStatus(403);
    }

    // a method that only returns an array
    private function data()
    {
        return [
            'name'    => 'Test Name',
            'email'   => 'test@email.com',
            'dob'     => '1960-06-30',
            'company' => 'siacom',
            'api_token' => $this->user->api_token,
        ];
    }
}
