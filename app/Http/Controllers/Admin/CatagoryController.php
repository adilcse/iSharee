<?php
/**
 * Handles admin catagory related actions
 * PHP version: 7.0
 * 
 * @category Admin/Article
 * @package  Http/Controller/Admin
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Admin/CatagoryController.php
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Catagory;
use Illuminate\Database\QueryException;
use App\Helper\Slug;
use App\Helper\Constants;
/**
 * Handle admin catagory action
 * 
 * @category Admin/Article
 * @package  Http/Controller/Admin
 * @author   Adil Hussain <adilh@mindfiresolutions.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/adilcse/iSharee/blob/finalCode/app/Http/Controllers/Admin/CatagoryController.php
 */
class CatagoryController extends Controller
{
    /**
     * Gives admin catagory edit view with catagory details
     * 
     * @param int $id of the catagory to be updated
     * 
     * @return view catagoy
     */
    public function edit($id)
    {
        try{
            $catagoryAll=Catagory::all();
            $catagory=Catagory::find($id);
            if (is_null($catagory)) {
                return view(
                    'admin.catagory.Catagory', 
                    ['mode'=>'Edit','invalid'=>'id','catagories'=>$catagoryAll]
                );
            } else {
                return view(
                    'admin.catagory.Catagory',
                    [
                        'mode'=>'Edit',
                        'id'=>$id,
                        'value'=>$catagory->name,
                        'catagories'=>$catagoryAll
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
     * Gives admin add catagory view
     * 
     * @return view catagory
     */
    public function add()
    {
        try{
            $catagory=Catagory::all();
            return view(
                'admin.catagory.Catagory',
                ['mode'=>'Add','catagories'=>$catagory]
            );
        }
        catch(Exception $e){
            return view('admin.catagory.Catagory', ['mode'=>'Add'])
                ->withErrors([Constants::$ERROR_WRONG]);
        }
    }

    /**
     * Insert new catagory in database
     * 
     * @param Request $request http request object
     * 
     * @return redirect to catagory page with status
     */
    public function insert(Request $request)
    {
        //validate input catagory
        $this->validate(
            $request,
            ['catagory'=>'required|string|min:3|max:50']
        );
        //creates a new catagory
        try{
            $catagory= new Catagory;
            $catagory->name=$request->input('catagory');
            $catagory->slug=Slug::createSlug(
                'catagory', 
                $request->input('catagory')
            );
            $catagory->save();
            return redirect('/admin/catagory/add')
                ->with('success', Constants::$SUCCESS_CATAGORY_ADD);
        }
        catch(Exception $e){
            return view('error', Constants::$ERROR_CREATING_CATAGORY);
        }
    }

    /**
     * Update a catagory
     * 
     * @param Request $request http requrst object
     * 
     * @return redirect to catagory page
     */
    public function update(Request $request)
    {
        $request->validate(
            [
                'catagory'=>'required|string|min:3|max:50',
                'id'=>'required|numeric'
            ]
        );
        $name=$request->input('catagory');
        $id= $request->input('id');
        try{
            //update catagory
            Catagory::where('id', intval($id))->update(['name'=>$name]);
            return redirect('/admin/catagory/add');
        }
        catch(Exception $e){
            return view('error', ['message'=>Constants::$ERROR_UPDATING_CATAGORY]);
        }
    }

    /**
     * Delete a catagory by its id
     * 
     * @param int $id of the catagory to be deleted
     * 
     * @return redirect to add page
     */
    public function delete($id)
    {
        try{
            $catagory=Catagory::find(intval($id));
            if (is_null($catagory)) {
                return redirect('/admin/catagory/add')
                ->withErrors([Constants::$ERROR_INVALID_CATAGORY]);    
            }
            //delete gatagory with given id
            $catagory->delete(); 
        }catch(QueryException $e){  
            return redirect('/admin/catagory/add')
                ->withErrors([Constants::$ERROR_DELETING_CATAGORY]);    
        } 
        return redirect('/admin/catagory/add')
            ->with('success', Constants::$SUCCESS_DELETE);
    }
}
