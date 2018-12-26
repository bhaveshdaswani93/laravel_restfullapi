<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

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
            'deletedDate' => isset($category->deleted_at)?(string)$category->deleted_at:null
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
}
