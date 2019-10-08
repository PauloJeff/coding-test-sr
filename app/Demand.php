<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    protected $table = 'demand';
    public $timestamps = false;

    protected $fillables = [
        'client_id'
    ];

    protected $guard = [];

    public function client()
    {
        return $this->hasOne('App\Client');
    }

    public function status()
    {
        return $this->hasOne('App\StatusOrder');
    }

    public function store()
    {
        return $this->hasOne('App\Store');
    }
}
