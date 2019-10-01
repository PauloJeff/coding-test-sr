<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'store';
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    protected $guarded = [];
}
