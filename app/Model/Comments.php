<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //define table name
    protected $table='comments';

    protected $fillable=['article_id','body','user_id','is_published'];

    // join artices table with comments table
    public function article()
    {
        # code...
        return $this->belongsTo('App\Model\Article');
    }

    //join users table with comments table
    public function user()
    {
        # code...
        return $this->belongsTo('App\User');
    }
}
