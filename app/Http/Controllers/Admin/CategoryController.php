<?php
/**
 * Handles admin category related actions
 * PHP version: 7.0
 * 
 * @category Admin/Article
 * @package  Http/Controller/Admin
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Admin/CategoryController.php
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Category;
use Illuminate\Database\QueryException;
use App\Helper\Slug;
use App\Helper\Constants;
/**
 * Handle admin category action
 * 
 * @category Admin/Article
 * @package  Http/Controller/Admin
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Admin/CategoryController.php
 */
class CategoryController extends Controller
{
    /**
     * Gives admin category edit view with category details
     * 
     * @param int $id of the category to be updated
     * 
     * @return view catagoy
     */
    public function edit($id)
    {
        try{
            $categoryAll=Category::all();
            $category=Category::find($id);
            if (is_null($category)) {
                return view(
                    'admin.category.Category', 
                    ['mode'=>'Edit','invalid'=>'id','catagories'=>$categoryAll]
                );
            } else {
                return view(
                    'admin.category.Category',
                    [
                        'mode'=>'Edit',
                        'id'=>$id,
                        'value'=>$category->name,
                        'catagories'=>$categoryAll
                    ]
                );
            }
        }
        catch(Exception $e){
            return view(
                'error', 
                ['message'=>Constants::$ERROR_GETTING_CATAGORY]
            );
        }
    }
    
    /**
     * Gives admin add category view
     * 
     * @return view category
     */
    public function add()
    {
        try{
            $category=Category::all();
            return view(
                'admin.category.Category',
                ['mode'=>'Add','catagories'=>$category]
            );
        }
        catch(Exception $e){
            return view('admin.category.Category', ['mode'=>'Add'])
                ->withErrors([Constants::$ERROR_WRONG]);
        }
    }

    /**
     * Insert new category in database
     * 
     * @param Request $request http request object
     * 
     * @return redirect to category page with status
     */
    public function insert(Request $request)
    {
        //validate input category
        $this->validate(
            $request,
            ['category'=>'required|string|min:3|max:50']
        );
        //creates a new category
        try{
            $category= new Category;
            $category->name=$request->input('category');
            $category->slug=Slug::createSlug(
                'category', 
                $request->input('category')
            );
            $category->save();
            return redirect('/admin/category/add')
                ->with('success', Constants::$SUCCESS_CATAGORY_ADD);
        }
        catch(Exception $e){
            return view('error', Constants::$ERROR_CREATING_CATAGORY);
        }
    }

    /**
     * Update a category
     * 
     * @param Request $request http requrst object
     * 
     * @return redirect to category page
     */
    public function update(Request $request)
    {
        $request->validate(
            [
                'category'=>'required|unique|string|min:3|max:50',
                'id'=>'required|numeric'
            ]
        );
        $name=$request->input('category');
        $id= $request->input('id');
        try{
            //update category
            Category::where('id', intval($id))->update(['name'=>$name]);
            return redirect('/admin/category/add');
        }
        catch(Exception $e){
            return view('error', ['message'=>Constants::$ERROR_UPDATING_CATAGORY]);
        }
    }

    /**
     * Delete a category by its id
     * 
     * @param int $id of the category to be deleted
     * 
     * @return redirect to add page
     */
    public function delete($id)
    {
        try{
            $category=Category::find(intval($id));
            if (is_null($category)) {
                return redirect('/admin/category/add')
                ->withErrors([Constants::$ERROR_INVALID_CATAGORY]);    
            }
            //delete gatagory with given id
            $category->delete(); 
        }catch(QueryException $e){  
            return redirect('/admin/category/add')
                ->withErrors([Constants::$ERROR_DELETING_CATAGORY]);    
        } 
        return redirect('/admin/category/add')
            ->with('success', Constants::$SUCCESS_DELETE);
    }
}
