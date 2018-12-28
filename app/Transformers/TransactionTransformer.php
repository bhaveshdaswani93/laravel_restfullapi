<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transaction;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identifier' => (int)$transaction->id,
            'quantity' => $transaction->quantity,
            'product' => $transaction->product_id,
            'buyer' => $transaction->buyer_id,
            'createdDate' => (string)$transaction->created_at,
            'lastChange' => (string)$transaction->updated_at,
            'deletedDate' => isset($transaction->deleted_at)?(string)$transaction->deleted_at:null,
            'links' => [
                [
                    'rel'=>'self',
                    'href'=>route('transactions.show',$transaction->id)
                ],
                
                [
                    'rel' => 'transaction.categories',
                    'href'=> route('transactions.categories.index',$transaction->id)
                ],
                
                [
                    'rel' => 'transaction.sellers',
                    'href' => route('transactions.sellers.index',$transaction->id)
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show',$transaction->buyer_id)
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show',$transaction->product_id)
                ],
                

            ]
        ];
    }

    public static function getOrignalAttribute($attribute)
    {
        $attributeMapper =  [
            'identifier' => 'id',
            'quantity' => 'quantity',
            'product' => 'product_id',
            'buyer' => 'buyer_id',
            
            'createdDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at'
        ];
        return isset($attributeMapper[$attribute])?$attributeMapper[$attribute]:null;
    }
}
