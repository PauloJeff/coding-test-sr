<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = 'request';
    public $timestamps = false;

    protected $fillables = [
        'client_id'
    ];

    protected $guard = [];

    public function client()
    {
        return $this->hasMany('App\Client');
    }
}
