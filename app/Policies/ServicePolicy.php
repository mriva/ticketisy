<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Service;

class ServicePolicy
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

    public function show(User $user, Service $service) {
        if ($user->role == 'technician') {
            return true;
        }

        return $user->id == $service->user_id;
    }

}
