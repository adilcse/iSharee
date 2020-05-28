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
     * Joining catagories with articles
     * 
     * @return void
     */
    public function catagories()
    {
        return $this
            ->belongsToMany(
                'App\Model\Catagory', 
                'article_catagory', 
                'article_id',
                'catagory_id'
            )
            ->withTimestamps();
    }

    /**
     * Joining commens with articles
     * 
     * @return void
     */
    public function comments()
    {
        return $this->belongsToMany('App\User', 'comments', 'article_id', 'user_id')
            ->withTimestamps()
            ->withPivot('is_published', 'body', 'id');
    }

    /**
     * Join likes table with articles 
     * 
     * @return void
     */
    public function likes()
    {
        return $this->belongsToMany('App\User', 'likes', 'article_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Join user table with article
     * 
     * @return void
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function payment()
    {
        return $this->hasOne('App\Model\Payment', 'article_id');
    }
}
