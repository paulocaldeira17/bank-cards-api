<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->name,
        'email' => $faker->email,
        'api_token' => $faker->sha1,
    ];
});

$factory->define(App\Card::class, function (Faker\Generator $faker) {
    $bankDetails = $faker->creditCardDetails;
    return [
        'id' => $faker->uuid,
        'accountName' => $bankDetails["name"],
        'iban' => $faker->bankAccountNumber,
        'bic' => $faker->swiftBicNumber,
        'user_id' => \App\User::first()->id
    ];
});

$factory->define(App\CardTransaction::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'amount' => $faker->randomFloat(2, 1, 20),
        'description' => 'Deposit',
        'card_id' => \App\Card::first()->id,
        'type' => $faker->randomElement([\App\CardTransaction::TYPE_DEPOSIT])
    ];
});
