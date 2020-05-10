<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MobileVerify extends Model
{
    protected $table='mobile_verify';
    protected $fillable=['number','otp'];
}
