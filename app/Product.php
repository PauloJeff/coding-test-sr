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

    public function stock()
    {
        return $this->hasMany('App\Stock', 'product_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($model){
            if($model->forceDeleting) {
                $model->roles()->detach();
            }
        });
    }
}
