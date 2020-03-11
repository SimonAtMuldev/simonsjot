<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Contact;
use Faker\Generator as Faker;

/**
 * Notes:
 * the call factory(\App\User::class)
 * I have omitted ->create() to the end of the line
 * by ommiting it Laravel will create a new user if none exists,
 * however if a user_id is present then no user will be created.
 */
$factory->define(Contact::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class),
        'name'    => $faker->name,
        'email'   => $faker->email,
        'dob'     => date($format = 'Y-m-d'),
        'company' => $faker->company
    ];
});
