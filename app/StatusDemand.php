<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusDemand extends Model
{
    protected $table = 'status_demand';
    public $timestamps = false;

    protected $fillables = [
        'name'
    ];

    protected $guard = [];
}
