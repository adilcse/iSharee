<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Catagory extends Model
{
    protected $table='catagories';

    public function articles(){
        return $this->belongsToMany('App\Model\Articles');
    }
}
