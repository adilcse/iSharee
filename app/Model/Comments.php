<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //
    protected $table='comments';

    public function article()
    {
        # code...
        return $this->belongsToMany('App\Model\Article');
    }
    public function user()
    {
        # code...
        return $this->belongsToMany('App\Users','users','user_id');
    }
}
