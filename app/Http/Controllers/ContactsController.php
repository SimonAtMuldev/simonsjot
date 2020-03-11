<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Resources\Contact as ContactResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Contact::class);
        return ContactResource::collection(\request()->user()->contacts);
//        return request()->user()->contacts;
    }
    public function store()
    {
        $this->authorize('create', Contact::class);
        $contact = request()->user()->contacts()->create($this->validationData());
        return (new ContactResource($contact))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Contact $contact)
    {
        $this->authorize('view', $contact);
        return new ContactResource($contact);
    }

    public function update(Contact $contact)
    {
        $this->authorize('view', $contact);
        $contact->update( $this->validationData());
        return (new ContactResource($contact))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Contact $contact)
    {
        $this->authorize('view', $contact);
        $contact->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }

    private function validationData()
    {
        return request()->validate([
            'name'    => 'required',
            'dob'     => 'required',
            'email'   => 'required|email',
            'company' => 'required',
        ]);
    }
}
