<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    protected $table = 'demand';
    public $timestamps = false;

    protected $fillable = [
        'client_id', 'code_demand', 'status', 'freight_price', 'store_id'
    ];

    protected $guard = [];

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function demandItem()
    {
        return $this->hasMany('App\DemandItem', 'demand_id');
    }

    public function statusDemand()
    {
        return $this->hasOne('App\StatusDemand', 'id', 'status');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }
}
