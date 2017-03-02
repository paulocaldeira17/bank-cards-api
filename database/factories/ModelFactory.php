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
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(App\Card::class, function (Faker\Generator $faker) {
    $bankDetails = $faker->creditCardDetails;
    return [
        'id' => $faker->uuid,
        'accountName' => $bankDetails["name"],
        'iban' => $faker->bankAccountNumber,
        'bic' => $faker->swiftBicNumber,
    ];
});

$factory->define(App\CardTransaction::class, function (Faker\Generator $faker) {
    $firstCard = \App\Card::getAll()->first();

    return [
        'id' => $faker->uuid,
        'amount' => $faker->randomFloat(2, 1, 20),
        'card_id' => $firstCard->id,
        'type' => $faker->randomElement([\App\CardTransaction::TYPE_DEPOSIT])
    ];
});
