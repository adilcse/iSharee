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
        return $this->belongsTo('App\Model\Article');
    }
    public function user()
    {
        # code...
        return $this->belongsTo('App\User');
    }
}
