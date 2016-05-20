<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $fillable = [
        'user_id', 'product_id', 'name'
    ];

    protected $with = ['product'];

    public function product() {
        return $this->belongsTo('App\Product');
    }

}
