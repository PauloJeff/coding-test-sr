<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';
    public $timestamps = false;

    protected $fillables = [
        'product_id', 'qtd_prod', 'store_id'
    ];

    protected $guarded = [];

    public function product()
    {
        return $this->hasOne('App\Product');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }
}
