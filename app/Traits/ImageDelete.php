<?php
/**
 * Delete image from bucket storage
 * PHP version: 7.0
 * 
 * @category Controller
 * @package  Http/Traits
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Traits/ImageDelete.php
 */
namespace App\Traits;
use Google\Cloud\Storage\StorageClient;

/**
 * Handle image upload to the server
 * 
 * @category Controller
 * @package  Http/Traits
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Traits/ImageDelete.php
 */
trait ImageDelete
{
    /**
     * Delete image from bucket
     * 
     * @param string $filename to be deleted
     * 
     * @return status
     */
    public function userImageDelete($filename) // Taking input image as parameter
    {   
        try{
            $storage = new StorageClient();
            $bucket = $storage->bucket($this->_imageBkt);
            $objectName=str_replace(
                $this->_storageAPI.$this->_imageBkt.'/', '', $filename
            ); 
            $success = $bucket->object($objectName)->delete();
            return $success; // Just return status
        }
        catch(Exception $e){
            return false;
        }
    }
}