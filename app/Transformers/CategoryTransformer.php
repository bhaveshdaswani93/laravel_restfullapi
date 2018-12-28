<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Category;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier' => (int)$category->id,
            'title' => $category->name,
            'details' => $category->description,
            'createdDate' => (string)$category->created_at,
            'lastChange' => (string)$category->updated_at,
            'deletedDate' => isset($category->deleted_at)?(string)$category->deleted_at:null,
            'links' => [
                [
                    'rel'=>'self',
                    'href'=>route('categories.show',$category->id)
                ],
                [
                    'rel' => 'categories.buyer',
                    'href' => route('categories.buyers.index',$category->id)
                ],
                [
                    'rel' => 'categories.sellers',
                    'href' => route('categories.sellers.index',$category->id)
                ],
                [
                    'rel' => 'categories.products',
                    'href'=> route('categories.products.index',$category->id)
                ],
                [
                    'rel' => 'categories.transactions',
                    'href' => route('categories.transactions.index',$category->id)
                ]

            ]
        ];
    }

    public static function getOrignalAttribute($attribute)
    {
        $attributeMapper =  [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
            
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
            
             'created_at'=>'createdDate' ,
             'updated_at'=>'lastChange' ,
             'deleted_at'=>'deletedDate' ,
        ];
        return isset($attributeMapper[$attribute])?$attributeMapper[$attribute]:null;
    }
}
