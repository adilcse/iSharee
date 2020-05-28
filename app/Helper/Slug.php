<?php
/**
 * Generate sluf for articles and catagory
 * PHP version: 7.0
 * 
 * @category Helper
 * @package  App/Helper
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Helper/Slug.php
 */

namespace App\Helper;

use App\Model\Article;
use Illuminate\Support\Str;

/**
 * Generate slug for uniquely identifying article and catagory
 * 
 * @category Helper
 * @package  App/Helper
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Helper/Slug.php
 */
class Slug
{
    /**
     * Create a new slug
     * 
     * @param srting $table name of the table
     * @param string $title of the article|catagory
     * @param int    $id    of new article|catagory
     * 
     * @return string $slug generted 
     * @throws \Exception
     */
    public static function createSlug($table,$title, $id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title, '-');
        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = self::getRelatedSlugs($slug, $id);
        // If we haven't used it before then we are all good.
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 100; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    /**
     * Check for selected slug and fid its related slug
     * 
     * @param srting $slug  for checking collision
     * @param int    $id    default 0 of new article|catagory
     * @param string $table default 'article' 
     * 
     * @return status if duplicate slug found or not
     */
    protected static function getRelatedSlugs($slug,$id=0,$table='article')
    {
        if ('article' === $table) {
            return self::getRelatedArticleSlugs($slug, $id);
        }
        return self::getRelatedCatagorySlugs($slug, $id);

    }

    /**
     * Get article slug which is not assigned to any article
     * 
     * @param string $slug generated slug
     * @param int    $id   default 0 
     * 
     * @return status of given slug is present or not
     */
    protected static function getRelatedArticleSlugs($slug, $id = 0)
    {
        return Article::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    /**
     * Check for catagory name already present 
     * 
     * @param string $slug of given catagory and check if it is already linked or not
     * @param int    $id   default 0
     * 
     * @return boolean status of given present or not
     */
    protected static function getRelatedCatagorySlugs($slug, $id = 0)
    {
        return Catagory::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
}