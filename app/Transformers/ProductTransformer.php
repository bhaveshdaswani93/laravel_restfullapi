<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Product;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identifier' => (int)$product->id,
            'title' => $product->name,
            'details' => $product->description,
            'stock'=>$product->quantity,
            'situation'=>$product->status,
            'picture'=> url("images/{$product->image}"),
            'seller'=>$product->seller_id,
            'createdDate' => (string)$product->created_at,
            'lastChange' => (string)$product->updated_at,
            'deletedDate' => isset($product->deleted_at)?(string)$product->deleted_at:null,
            'links' => [
                [
                    'rel'=>'self',
                    'href'=>route('products.show',$product->id)
                ],
                [
                    'rel' => 'product.buyer',
                    'href' => route('products.buyers.index',$product->id)
                ],
                
                [
                    'rel' => 'product.categories',
                    'href'=> route('products.categories.index',$product->id)
                ],
                [
                    'rel' => 'product.transactions',
                    'href' => route('products.transactions.index',$product->id)
                ],
                [
                    'rel' => 'seller',
                    'href' => route('sellers.show',$product->seller_id)
                ],

            ]
        ];
    }

    public static function getOrignalAttribute($attribute)
    {
        $attributeMapper =  [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
            'stock' => 'quantity',
            'situation' => 'status',
            'seller'=>'seller_id',

            'createdDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at'
        ];
        return isset($attributeMapper[$attribute])?$attributeMapper[$attribute]:null;
    }

     public static function getTransformedAttribute($attribute)
    {
        $attributeMapper =  [
             'id'=>'identifier' ,
             'name'=>'title' ,
             'description'=>'details' ,
             'quantity'=>'stock' ,
             'status'=>'situation' ,
            'seller_id'=>'seller',

             'created_at'=>'createdDate' ,
             'updated_at'=>'lastChange' ,
             'deleted_at'=>'deletedDate' ,
        ];
        return isset($attributeMapper[$attribute])?$attributeMapper[$attribute]:null;
    }
}
