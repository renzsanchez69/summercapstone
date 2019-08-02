<?php

use Faker\Generator as Faker;
use App\Products as Products;

$factory->define(Products::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'price' => $faker->numberBetween($min = 20, $max = 100000),
        'desc' => $faker->text,
        'status' => 1,
        'qty' => $faker->numberBetween($min = 20, $max = 1000),
        'total' => $faker->numberBetween($min = 20, $max = 1000),
        'category_id' => $faker->numberBetween($min = 1, $max = 13),
        'sellers_id' => $faker->numberBetween($min = 1, $max = 3),
        'location' => $faker->address,
        // 'filename' => 'sample_product'.$faker->numberBetween($min = 1, $max = 9).'.png',
    ];
});
