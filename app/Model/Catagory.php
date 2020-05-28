<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Catagory extends Model
{
    protected $table='catagories';

    /**
     * Join articles table with catagories table
     * 
     * @return void
     */
    public function articles()
    {
        return $this->belongsToMany('App\Model\Article')->withTimestamps();
    }
}
