<?php

/**
 * Upload image to bucket storage
 * PHP version: 7.0
 *
 * @category Controller
 * @package  Http/Traits
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Traits/ImageUpload.php
 */

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Storage\StorageClient;

/**
 * Handle image upload to the server
 *
 * @category Controller
 * @package  Http/Traits
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Traits/ImageUpload.php
 */
trait ImageUpload
{
    private $_imageBkt, $_storageAPI;

    /**
     * Set bucket name and storage api
     *
     * @return void
     */
    public function __construct()
    {
        $this->_imageBkt = env('GOOGLE_STORAGE_BUCKET');
        $this->_storageAPI = env('GOOGLE_STORAGE_API');
    }

    /**
     * Upload image to cloud bucket
     *
     * @param file $query image to be uploaded
     * @param int  $id    of the image
     *
     * @return upload status
     */
    // upload to cloud
    // public function userImageUpload($query,$id=0) // Taking input image as parameter
    // {
    //     //Generate random name for image
    //     $str=rand();
    //     $image_name = $id.md5($str);
    //     $ext = strtolower($query->getClientOriginalExtension()); // You can use also getClientOriginalName()
    //     $image_full_name = 'articles/images/'.$image_name.'.'.$ext;
    //     $image_url = $this->_storageAPI.$this->_imageBkt.'/'.$image_full_name;
    //     $this->_uploadObject($this->_imageBkt, $image_full_name, $query);
    //     return $image_url; // Just return image path
    // }

    public function UserImageUpload($query) // Taking input image as parameter
    {
        //generate random name for image
        $str = rand();
        $image_name = md5($str);
        $ext = strtolower($query->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name . '.' . $ext;
        $upload_path = 'image/post/';    //Creating Sub directory in Public folder to put image
        $image_url = '/' . $upload_path . $image_full_name;
        $success = $query->move($upload_path, $image_full_name);
        return $image_url; // Just return image path
    }
    /**
     * Upload a file.
     *
     * @param string $bucketName the name of your Google Cloud bucket.
     * @param string $objectName the name of the object.
     * @param string $source     the path to the file to upload.
     *
     * @return void
     */
    private function _uploadObject($bucketName, $objectName, $source)
    {
        $storage = new StorageClient();
        $file = fopen($source, 'r');
        $bucket = $storage->bucket($bucketName);
        $object = $bucket->upload(
            $file,
            ['name' => $objectName]
        );
    }
}
