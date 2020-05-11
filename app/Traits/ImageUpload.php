<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * handle image upload to the server
 */
trait ImageUpload
{
    public function UserImageUpload($query) // Taking input image as parameter
    {
        //generate random name for image
        $str=rand(); 
        $image_name = md5($str); 
        $ext = strtolower($query->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'image/post/';    //Creating Sub directory in Public folder to put image
        $image_url = '/'.$upload_path.$image_full_name;
        $success = $query->move($upload_path,$image_full_name);
        return $image_url; // Just return image path
    }
}