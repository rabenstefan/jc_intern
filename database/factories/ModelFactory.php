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
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'voice_id' => $faker->numberBetween(5,12),
        'address_street' => $faker->streetAddress,
        'address_zip' => $faker->postcode,
        'address_city' => $faker->city,
        'birthday' => $faker->date(),
        'last_echo' => 1,
        'phone' => $faker->phoneNumber,
    ];
});

$factory->define(App\Rehearsal::class, function (Faker\Generator $faker) {
    // Make sure end is after start dateTime.
    $start = $faker->dateTimeBetween('11.04.2016', '29.09.2016');

    return [
        'semester_id' => 1,
        'title'       => 'Probe',
        'description' => $faker->sentence(14),
        'start'       => $start,
        'end'         => $faker->dateTimeBetween($start, '29.09.2016'),
        'place'       => $faker->address,
        'mandatory'   => $faker->boolean(90),
        'weight'      => $faker->randomFloat(1, 0.0, 1.0),
    ];
});

$factory->define(App\Gig::class, function (Faker\Generator $faker) {
    // Make sure end is after start dateTime.
    $start = $faker->dateTimeBetween('11.04.2016', '29.09.2016');

    return [
        'semester_id' => 1,
        'title'       => 'Konzert',
        'description' => $faker->sentence(14),
        'start'       => $start,
        'end'         => $faker->dateTimeBetween($start, '29.09.2016'),
        'place'       => $faker->address,
    ];
});
