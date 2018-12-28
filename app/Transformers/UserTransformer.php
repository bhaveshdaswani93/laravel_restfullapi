<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identifier' => (int)$user->id,
            'name' => (string)$user->name,
            'email' => $user->email,
            'isVerified' => (int)$user->verified,
            'isAdmin' => ($user->admin === 'true'),
            'createdDate' => (string)$user->created_at,
            'lastChange' => (string)$user->updated_at,
            'deletedDate' => isset($user->deleted_at)?(string)$user->deleted_at:null,
            'links' => [
                [
                    'rel'=>'self',
                    'href'=>route('users.show',$user->id)
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
            'isAdmin' => 'admin',
            'createdDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at'
        ];
        return isset($attributeMapper[$attribute])?$attributeMapper[$attribute]:null;
    }

    public static function getTransformedAttribute($attribute)
    {
        $attributeMapper =  [
             'id'=>'identifier',
             'name'=>'name',
             'email'=>'email',
             'verified'=>'isVerified',
             'admin'=>'isAdmin',
             'created_at'=>'createdDate',
             'updated_at'=>'lastChange',
             'deleted_at'=>'deletedDate',
        ];
        return isset($attributeMapper[$attribute])?$attributeMapper[$attribute]:null;
    } 
}
