<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function article()
    {
        return $this->belongsTo('App\Model\Article', 'article_id');
    }
}
