<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    public $timestamps = false;

    protected $fillable = [
        'name', 'code_product', 'description', 'attributes', 'price'
    ];

    protected $guard = [];
}
