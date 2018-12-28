<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Seller;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'identifier' => (int)$seller->id,
            'name' => (string)$seller->name,
            'email' => $seller->email,
            'isVerified' => (int)$seller->verified,
            'createdDate' => (string)$seller->created_at,
            'lastChange' => (string)$seller->updated_at,
            'deletedDate' => isset($seller->deleted_at)?(string)(string)$seller->deleted_at:null,
            'links' => [
                [
                    'rel'=>'self',
                    'href'=>route('sellers.show',$seller->id)
                ],
                
                [
                    'rel' => 'seller.categories',
                    'href'=> route('sellers.categories.index',$seller->id)
                ],
                [
                    'rel' => 'seller.products',
                    'href' => route('sellers.products.index',$seller->id)
                ],
                
                [
                    'rel' => 'seller.buyers',
                    'href' => route('sellers.buyers.index',$seller->id)
                ],
                [
                    'rel' => 'seller.transaction',
                    'href' => route('sellers.transaction.index',$seller->id)
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show',$seller->id)
                ],
                
                

            ]
        ];
    }

    public static function getOrignalAttribute($attribute)
    {
        $attributeMapper =  [
            'identifier' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
            
            'createdDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at'
        ];
        return isset($attributeMapper[$attribute])?$attributeMapper[$attribute]:null;
    }
}
