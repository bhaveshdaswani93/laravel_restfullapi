<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Buyer;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'identifier' => (int)$buyer->id,
            'name' => (string)$buyer->name,
            'email' => $buyer->email,
            'isVerified' => (int)$buyer->verified,
            'createdDate' => (string)$buyer->created_at,
            'lastChange' => (string)$buyer->updated_at,
            'deletedDate' => isset($buyer->deleted_at)?(string)$buyer->deleted_at:null,
            'links' => [
                [
                    'rel'=>'self',
                    'href'=>route('buyers.show',$buyer->id)
                ],
                
                [
                    'rel' => 'buyer.categories',
                    'href'=> route('buyers.categories.index',$buyer->id)
                ],
                [
                    'rel' => 'buyer.products',
                    'href' => route('buyers.products.index',$buyer->id)
                ],
                
                [
                    'rel' => 'buyer.sellers',
                    'href' => route('buyers.sellers.index',$buyer->id)
                ],
                [
                    'rel' => 'buyer.transaction',
                    'href' => route('buyers.transaction.index',$buyer->id)
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show',$buyer->id)
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


    public static function getTransformedAttribute($attribute)
    {
        $attributeMapper =  [
             'id' => 'identifier' ,
             'name' => 'name' ,
             'email' => 'email' ,
             'verified' => 'isVerified' ,
            
             'created_at' => 'createdDate' ,
             'updated_at' => 'lastChange' ,
             'deleted_at' => 'deletedDate' ,
        ];
        return isset($attributeMapper[$attribute])?$attributeMapper[$attribute]:null;
    }
}
