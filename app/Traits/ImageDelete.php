<?php
namespace App\Traits;

use Illuminate\Support\Facades\File; 
/**
 * handle image upload to the server
 */
trait ImageDelete
{
    public function UserImageDelete($filename) // Taking input image as parameter
    {   
        $fullPath=public_path().$filename;
        $success = File::delete($fullPath);
        return $success; // Just return status
    }
}