<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'employee';
    public $timestamps = false;

    protected $fillable = [
        'name', 'lastname', 'email', 'cpf', 'rg', 'password'
    ];

    protected $guard = [];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function store()
    {
        return $this->hasOne('App\Store');
    }
}
