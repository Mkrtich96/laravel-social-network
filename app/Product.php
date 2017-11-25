<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['name','description','price'];

    public function user(){

        return $this->belongsTo('App\User');
    }

    public function order(){

        return $this->hasOne('App\Order');
    }
}
