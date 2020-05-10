<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Catagory;
use Illuminate\Database\QueryException;
use App\Helper\Slug;
/**
 * handle user catagory action
 */
class CatagoryController extends Controller
{


    /**
     * gives admin catagory edit view with catagory details
     *@param id of the catagory to be updated
     *@return catagoy view
     */
    public function edit($id)
    {
        try{
            $catagoryAll=Catagory::all();
            $catagory=Catagory::find($id);
            if(is_null($catagory)){
                return view('admin.catagory.Catagory',['mode'=>'Edit','invalid'=>'id','catagories'=>$catagoryAll]);
            }else{
                return view('admin.catagory.Catagory',['mode'=>'Edit','id'=>$id,'value'=>$catagory->name,'catagories'=>$catagoryAll]);
            }
        }
        catch(Exception $e){
            return view('error',['message'=>'error in getting catagory']);
        }

    }
    
    /**
     * gives admin add catagory view
     * @return view catagory
     */
    public function add()
    {
        try{
        $catagory=Catagory::all();
            return view('admin.catagory.Catagory',['mode'=>'Add','catagories'=>$catagory]);
        }
        catch(Exception $e){
            return view('admin.catagory.Catagory',['mode'=>'Add'])->withErrors(['something went wrong']);
        }
    }

    /**
     * insert new catagory in database
     * @param request object
     * @return redirect to catagory page with status
     */
    public function insert(Request $request)
    {
        //validate input catagory
        $this->validate($request,[
            'catagory'=>'required|string|min:3|max:50'
        ]);
        //creates a new catagory
        try{
            $catagory= new Catagory;
            $catagory->name=$request->input('catagory');
            $catagory->slug=Slug::createSlug('catagory',$request->input('catagory'));
            $catagory->save();
            return redirect('/admin/catagory/add')->with('success','Catagory successfully added');
        }
        catch(Exception $e){
            return view('error','error in creating catagory');
        }
    }

    /**
     * update a catagory
     * @param request object
     * @return redirect to catagory page
     */
    public function update(Request $request)
    {
        $request->validate([
            'catagory'=>'required|string|min:3|max:50',
            'id'=>'required|numeric'
        ]);
        $name=$request->input('catagory');
        $id= $request->input('id');
        try{
            //update catagory
            Catagory::where('id',intval($id))->update(['name'=>$name]);
        return redirect('/admin/catagory/add');
        }
        catch(Exception $e){
            return view('error',['message'=>'error in updating catagory']);
        }
    }

    /**
     * delete a catagory by its id
     * @param id of the catagory to be deleted
     * @return redirect to add page
     */
    public function delete($id)
    {
        try{
        $catagory=Catagory::find(intval($id));
        if(is_null($catagory)){
            return redirect('/admin/catagory/add')->withErrors(['invalid catagory']);    
        }
        //delete gatagory with given id
        $catagory->delete(); 
        }catch(QueryException $e){  
            return redirect('/admin/catagory/add')->withErrors(['can not delete catagory']);    
        } 
        return redirect('/admin/catagory/add')->with('success','deleted');
    }
}
