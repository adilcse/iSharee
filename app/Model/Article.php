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
}
