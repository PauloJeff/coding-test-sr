<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DemandItem extends Model
{
    protected $table = 'demand_item';
    public $timestamps = false;

    protected $fillable = [
        'demand_id', 'product_id', 'qtd_item'
    ];

    protected $guard = [];

    public function request()
    {
        return $this->belongsTo('App\Request');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }
}
