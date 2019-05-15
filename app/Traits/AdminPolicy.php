<?php
namespace App\Traits;

trait AdminPolicy
{
    public function before($user, $ability)
    {
        if($user->isAdmin())
        {
            return true;
        }
    }
}