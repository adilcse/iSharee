<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EmailVerify extends Model
{
    //define table name
    protected $table='email_verify';

    //define mass assignable columns
    protected $fillable=['email','otp'];
}
