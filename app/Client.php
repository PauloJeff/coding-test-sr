<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';
    public $timestamps = false;

    protected $fillables = [
        'name', 'lastname', 'cpf', 'rg'
    ];

    protected $guard = [];
}
