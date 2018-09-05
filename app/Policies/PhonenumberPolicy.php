<?php

namespace App\Policies;

use App\User;
use App\Phonenumber;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhonenumberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the phonenumber.
     *
     * @param  \App\User  $user
     * @param  \App\Phonenumber  $phonenumber
     * @return mixed
     */
    public function view(User $user, Phonenumber $phonenumber)
    {
        return $user->id == $phonenumber->user_id;
    }

    /**
     * Determine whether the user can create phonenumbers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the phonenumber.
     *
     * @param  \App\User  $user
     * @param  \App\Phonenumber  $phonenumber
     * @return mixed
     */
    public function update(User $user, Phonenumber $phonenumber)
    {
        return $user->id == $phonenumber->user_id;
    }

    /**
     * Determine whether the user can delete the phonenumber.
     *
     * @param  \App\User  $user
     * @param  \App\Phonenumber  $phonenumber
     * @return mixed
     */
    public function delete(User $user, Phonenumber $phonenumber)
    {
        return $user->id == $phonenumber->user_id;
    }

    /**
     * Determine whether the user can restore the phonenumber.
     *
     * @param  \App\User  $user
     * @param  \App\Phonenumber  $phonenumber
     * @return mixed
     */
    public function restore(User $user, Phonenumber $phonenumber)
    {
        return $user->id == $phonenumber->user_id;
    }

    /**
     * Determine whether the user can permanently delete the phonenumber.
     *
     * @param  \App\User  $user
     * @param  \App\Phonenumber  $phonenumber
     * @return mixed
     */
    public function forceDelete(User $user, Phonenumber $phonenumber)
    {
        return $user->id === $phonenumber->user_id;
    }
}
