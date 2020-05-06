<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table='articles';
    protected $fillable = [
        'title', 'image_url', 'body','is_published'
    ];

    public function catagories()
    {
        return $this->belongsToMany('App\Model\Catagory', 'article_catagory', 'article_id', 'catagory_id');
    }
    public function comments()
    {
        # code...
        return $this->belongsToMany('App\User', 'comments', 'article_id', 'user_id')
                    ->withTimestamps()
                    ->withPivot('is_published','body');
    }
    public function likes()
    {
        # code...
        return $this->belongsToMany('App\User', 'likes', 'article_id', 'user_id')->withTimestamps();
    }
}
