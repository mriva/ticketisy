<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(User $user, $ability) {
        if ($user->role == 'admin') {
            return true;
        }
    }

    public function show(User $user, $requested) {
        if ($user->role == 'user') {
            return false;
        }

        if ($user->role == 'technician' && $requested->role != 'user') {
            return false;
        }

        return true;
    }

}
