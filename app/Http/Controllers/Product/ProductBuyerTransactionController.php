<?php

namespace App\Http\Controllers\Product;

use App\User;
use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use App\Events\ProductUpdateEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class ProductBuyerTransactionController extends ApiController
{
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product,User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];

        $this->validate($request,$rules);

        if($buyer->id == $product->seller_id)
        {
            return $this->errorResponse("The buyer could not buy his own product",409);
        }

        if(!$buyer->isVerified())
        {
            return $this->errorResponse("The buyer must be verified to do the transaction.",409);
        }
        if(!$product->seller->isVerified())
        {
            return $this->errorResponse("The seller of the product is not verified.",409);
        }

        if(!$product->isAvailable())
        {
            return $this->errorResponse("This product is not available.",409);
        }

        if($product->quantity < $request->quantity)
        {
            return $this->errorResponse("You have provided more quantity than available please reduce the quantity to purchase",409);
        }

        return DB::transaction(function () use ($request,$product,$buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

           $transaction =  Transaction::create([
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
           // event(new ProductUpdateEvent($product));
           return $this->showOne($transaction);
        });
    }
}
