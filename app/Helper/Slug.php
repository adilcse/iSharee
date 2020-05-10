<?php
namespace App\Helper;
use App\Model\Article;
use Illuminate\Support\Str;
class Slug
{
    /**
     * create a new slug
     * @param table
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

    protected static function getRelatedSlugs($slug,$id=0,$table='article'){
        if('article' === $table){
            return self::getRelatedArticleSlugs($slug,$id);
        }
        return self::getRelatedCatagorySlugs($slug,$id);

    }

    protected static function getRelatedArticleSlugs($slug, $id = 0)
    {
        return Article::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    protected static function getRelatedCatagorySlugs($slug, $id = 0)
    {
        return Catagory::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
}