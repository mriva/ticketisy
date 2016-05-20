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

        $gate->define('activate-service', function($user) {
            return $user->role == 'user';
        });

        $gate->define('create-ticket', function($user, $request) {
            $service = DB::table('services')->find($request->service_id);

            return $service->user_id == $user->id;
        });

        $gate->define('access-ticket', function($user, $ticket) {
            if ($user->role == 'admin' || $user->role == 'technician') {
                return true;
            }

            return $user->id == $ticket->user_id;
        });
    }
}
