<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * Join payment table with user table
     * 
     * @return void
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Join payment table with article table
     * 
     * @return void
     */
    public function article()
    {
        return $this->belongsTo('App\Model\Article', 'article_id');
    }
}
