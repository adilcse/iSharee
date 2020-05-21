<?php
namespace App\Traits;
use Google\Cloud\Storage\StorageClient;

/**
 * handle image upload to the server
 */
trait ImageDelete
{
    public function UserImageDelete($filename) // Taking input image as parameter
    {   try{
            $storage = new StorageClient();
            $bucket = $storage->bucket($this->imageBkt);
            $objectName=str_replace($this->storageAPI.$this->imageBkt.'/','',$filename); 
            $success = $bucket->object($objectName)->delete();
            return $success; // Just return status
        }
        catch(Exception $e){
            return false;
        }
    }
}