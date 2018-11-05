<?php

use App\Product;
use Faker\Generator as Faker;
use App\User;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1,10),
        'status' => $faker->randomElement([Product::AVAILABLE_PRODUCT,Product::UNAVAILABLE_PRODUCT]),
        'image' => $faker->randomElement(['228.jpg','154791-OV8MJK-259.jpg','180861-OWMMOA-86.jpg']),
        'seller_id' => User::all()->random()->id
    ];
});
