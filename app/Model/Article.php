<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table='articles';

    protected $fillable = [
        'title', 'image_url', 'body','is_published'
    ];
    /**
     * joining catagories with articles
     */
    public function catagories()
    {
        return $this->belongsToMany('App\Model\Catagory', 'article_catagory', 'article_id', 'catagory_id')
                ->withTimestamps();
    }

    /**
     * joining commens with articles
     */
    public function comments()
    {
        # code...
        return $this->belongsToMany('App\User', 'comments', 'article_id', 'user_id')
                    ->withTimestamps()
                    ->withPivot('is_published','body','id');
    }

    /**
     * join likes table with articles 
     */
    public function likes()
    {
        # code...
        return $this->belongsToMany('App\User', 'likes', 'article_id', 'user_id')
        ->withTimestamps();
    }

    /**
     * join user table with article
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
