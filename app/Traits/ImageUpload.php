<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Storage\StorageClient;
/**
 * handle image upload to the server
 */
trait ImageUpload
{
    private $imageBkt,$storageAPI;
    public function __construct()
    {
        $this->imageBkt=env('GOOGLE_STORAGE_BUCKET');
        $this->storageAPI=env('GOOGLE_STORAGE_API');
    }
    public function UserImageUpload($query,$id=0) // Taking input image as parameter
    {
        //generate random name for image
        $str=rand(); 
        $image_name = $id.md5($str); 
        $ext = strtolower($query->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = 'articles/images/'.$image_name.'.'.$ext;
        $image_url = $this->storageAPI.$this->imageBkt.'/'.$image_full_name;
        $this->upload_object($this->imageBkt,$image_full_name,$query);
        return $image_url; // Just return image path
    }

    /**
     * Upload a file.
     *
     * @param string $bucketName the name of your Google Cloud bucket.
     * @param string $objectName the name of the object.
     * @param string $source the path to the file to upload.
     *
     * @return void
     */
    private function upload_object($bucketName, $objectName, $source)
    {
        $storage = new StorageClient();
        $file = fopen($source, 'r');
        $bucket = $storage->bucket($bucketName);
        $object = $bucket->upload($file, [
            'name' => $objectName
        ]);
    }
}