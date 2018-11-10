<?php

namespace App\Listeners;

use App\Product;
use App\Events\ProductUpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductUpdateEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProductUpdateEvent  $event
     * @return void
     */
    public function handle(ProductUpdateEvent $event)
    {
        $product = $event->product;
        if($product->quantity == 0 && $product->isAvailable())
        {
            $product->status = Product::UNAVAILABLE_PRODUCT;
            $product->save();
        }
    }
}
