<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = ['user_id','email', 'product','product_id'];


    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
