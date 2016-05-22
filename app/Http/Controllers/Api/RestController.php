<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\User;

class RestController extends Controller
{
    
    protected $user;

    public function __construct() {
        $this->middleware('auth:api');
        $this->user = Auth::guard('api')->user();
    }

    public function getRedirectUrl() {
        return url('/unauthorized');
    }

    protected function getFilterUser($requested_user) {
        if ($this->user->role == 'user') {
            return $this->user;
        }

        if ($requested_user) {
            return User::find($requested_user);
        } else {
            return null;
        }
    }


}

