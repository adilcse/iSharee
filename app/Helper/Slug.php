<?php
namespace App\Helper;
use App\Model\Article;
use Illuminate\Support\Str;
class Slug
{
    /**
     * create a new slug
     * @param table name of the table
     * @param title 
     * @param id
     * @return string
     * @throws \Exception
     */
    public static function createSlug($table,$title, $id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title,'-');
        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = self::getRelatedSlugs($slug, $id);
        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
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
     * check for selected slug and fid its related slug
     */
    protected static function getRelatedSlugs($slug,$id=0,$table='article')
    {
        if('article' === $table){
            return self::getRelatedArticleSlugs($slug,$id);
        }
        return self::getRelatedCatagorySlugs($slug,$id);

    }

    /**
     * get article slug which is not assigned to any article
     * @param slug generated slug
     * @return status of given slug is present or not
     */
    protected static function getRelatedArticleSlugs($slug, $id = 0)
    {
        return Article::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    /**
     * check for catagory name already present 
     * @param slug of given catagory and check if it is already linked or not
     * @return status of given present or not
     */
    protected static function getRelatedCatagorySlugs($slug, $id = 0)
    {
        return Catagory::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
}