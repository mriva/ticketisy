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
        'App\Model' => 'App\Policies\ModelPolicy',
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

        $gate->before(function($user, $ability) {
            if ($user->role == 'admin') {
                return true;
            }
        });

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

        $gate->define('access-ticket', function($user, $ticket) {
            if ($user->role == 'admin' || $user->role == 'technician') {
                return true;
            }

            return $user->id == $ticket->user_id;
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

        $gate->define('ticketevent-comment', function($user, $ticket) {
            if ($user->role == 'user') {
                return $user->id == $ticket->user_id;
            }

            return $user->id == $ticket->technician_id;
        });

        $gate->define('ticketevent-priority', function($user, $ticket) {
            if ($user->role == 'user') {
                return false;
            }

            return $user->id == $ticket->technician_id;
        });

        $gate->define('ticketevent-close', function($user, $ticket) {
            if ($user->role == 'user') {
                return false;
            }

            return $user->id == $ticket->technician_id;
        });

        $gate->define('ticketevent-assignee', function($user, $ticket, $request) {
            if ($user->role == 'user') {
                return false;
            }

            return $ticket->technician_id == 0 &&
                ($user->id == $ticket->technician_id ||
                 $ticket->technician_id == 'me');
        });

    }
}
