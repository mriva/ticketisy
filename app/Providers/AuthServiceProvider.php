<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\User'    => 'App\Policies\UserPolicy',
        'App\Ticket'  => 'App\Policies\TicketPolicy',
        'App\Service' => 'App\Policies\ServicePolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('activate-service', function($user) {
            return $user->role == 'user';
        });

        $gate->define('create-ticket', function($user, $request) {
            if ($user->role == 'admin' || $user->role == 'technician') {
                return true;
            }

            $service = DB::table('services')->find($request->service_id);

            return $service->user_id == $user->id;
        });

        $gate->define('list-users', function($user, $role) {
            if ($user->role == 'admin') {
                return true;
            }

            if ($user->role == 'technician') {
                return !$role || $role == 'user';
            }

            return false;
        });

        $gate->define('create-user', function($user, $role) {
            return $user->role == 'admin';
        });

    }
}
