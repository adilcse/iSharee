<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Catagory extends Model
{
    protected $table='catagories';

    /**
     * join articles table with catagories table
     */
    public function articles(){
        return $this->belongsToMany('App\Model\Article')->withTimestamps();
    }
}
