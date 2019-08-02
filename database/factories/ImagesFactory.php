<?php

use Faker\Generator as Faker;
use App\Images as Images;

$factory->define(Images::class, function (Faker $faker) {
    return [
        'original_filename' => 'sample_product'.$faker->numberBetween($min = 1, $max = 9).'.png',
        'filename' => 'sample_product'.$faker->numberBetween($min = 1, $max = 9).'.png',
        'mime' => 'png'
    ];
});
