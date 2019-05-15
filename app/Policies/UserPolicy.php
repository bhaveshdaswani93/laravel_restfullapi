<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\AdminPolicy;

class UserPolicy
{
    use HandlesAuthorization,AdminPolicy;

    

    /**
     * Determine whether the user can view the user.
     *
     * @param  \App\User  $authenticatedUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->id === $user->id;
    }

    
    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\User  $authenticatedUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->id === $user->id;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\User  $authenticatedUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->id === $user->id && $authenticatedUser->token()->client->personal_access_client;
    }
}
