<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //define table name
    protected $table='comments';

    protected $fillable=['article_id','body','user_id','is_published'];

    /**
     * Join artices table with comments table
     * 
     * @return void
     */ 
    public function article()
    {
        return $this->belongsTo('App\Model\Article');
    }

    /**
     * Join users table with comments table
     * 
     * @return void
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}
