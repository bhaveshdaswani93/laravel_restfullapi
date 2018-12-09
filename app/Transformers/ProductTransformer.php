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
    public function transform(Prdouct $product)
    {
        return [
            'identifier' => (int)$product->id,
            'title' => $product->name,
            'details' => $product->description,
            'stock'=>$product->quantity,
            'situation'=>$product->status,
            'picture'=> url('images/{$product->image}'),
            'seller'=>$product->seller_id,
            'createdDate' => (string)$product->created_at,
            'lastChange' => (string)$product->updated_at,
            'deletedDate' => isset($product->deleted_at)?(string)$product->deleted_at:null
        ];
    }
}
