<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MobileVerify extends Model
{
    //define table name
    protected $table='mobile_verify';

        //define mass assignable columns
    protected $fillable=['number','otp'];
}
