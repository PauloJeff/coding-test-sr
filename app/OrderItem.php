<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_item';
    protected $timestamps = false;

    protected $fillables = [
        'request_id', 'product_id',
    ];

    protected $guard = [];

    public function request()
    {
        return belongsTo('App\Request');
    }

    public function client()
    {
        return belongsTo('App\Client');
    }
}
