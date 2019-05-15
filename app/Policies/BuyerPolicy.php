<?php

namespace App\Policies;

use App\User;
use App\Buyer;
use App\Traits\AdminPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuyerPolicy
{
    use HandlesAuthorization,AdminPolicy;

    /**
     * Determine whether the user can view the buyer.
     *
     * @param  \App\User  $user
     * @param  \App\Buyer  $buyer
     * @return mixed
     */
    public function view(User $user, Buyer $buyer)
    {
        return $user->id === $buyer->id;
    }
    /**
     * Determine whether the user can purchase something.
     *
     * @param  \App\User  $user
     * @param  \App\Buyer  $buyer
     * @return mixed
     */
    public function purchase(User $user, Buyer $buyer)
    {
        return $user->id === $buyer->id;
    }
}
