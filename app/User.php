<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    protected $with = ['departments'];

    public function departments() {
        return $this->belongsToMany('App\Department', 'users_departments');
    }

    public static function create(array $attributes = []) {
        $model = new static($attributes);

        $model->save();

        if (!empty($attributes['departments'])) {
            $model->departments()->attach($attributes['departments']);
        }

        return $model;
    }

}
