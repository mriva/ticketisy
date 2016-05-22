<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

}

