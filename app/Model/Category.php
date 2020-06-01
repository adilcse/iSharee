<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table='categories';

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
