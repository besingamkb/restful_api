<?php

use Faker\Generator as Faker;

$factory->define(\App\Phonenumber::class, function (Faker $faker) {
    return [
        'value' => $faker->phoneNumber
    ];
});
