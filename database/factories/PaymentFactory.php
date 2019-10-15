<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Payment;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'gateway' => 'zarinpal',
        'transaction_id' => $faker->randomNumber(),
        'description' => $faker->text,
        'amount' => $faker->numberBetween(10, 100000),
    ];
});
