<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * user model handle user related operations in database
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile','is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //join likes table with user table
    public function likes()
    {
        return $this->belongsToMany('App\Model\Article', 'likes', 'user_id', 'article_id');
    }

    //join comments table with user table
    public function comments()
    {
        return $this->belongsToMany('App\Model\Article', 'comments', 'user_id', 'article_id');
    }

    //join articles table with user table
    public function articles()
    {
        return $this->hasMany('App\Model\Article');
    }
}
