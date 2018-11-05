<?php

use Faker\Generator as Faker;
use App\Transaction;
use App\User;
use App\Seller;

$factory->define(App\Transaction::class, function (Faker $faker) {
	$seller = Seller::has('products')->get()->random();
	$buyer = User::all()->except($seller->id)->random();

    return [
        'quantity'=>$faker->numberBetween(1,3),
        'buyer_id'=> $buyer->id,
        'product_id'=> $seller->products->random()->id
    ];
});
