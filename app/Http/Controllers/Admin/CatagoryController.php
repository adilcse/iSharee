<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Catagory;
class CatagoryController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $catagoryAll=Catagory::all();
        $catagory=Catagory::find($id);
        if(is_null($catagory)){
            return view('admin.catagory.Catagory',['mode'=>'Edit','invalid'=>'id','catagories'=>$catagoryAll]);
        }else{
            return view('admin.catagory.Catagory',['mode'=>'Edit','id'=>$id,'value'=>$catagory->name,'catagories'=>$catagoryAll]);
        }
    }
        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function add()
    {
        $catagory=Catagory::all();
            return view('admin.catagory.Catagory',['mode'=>'Add','catagories'=>$catagory]);
    }

    public function insert(Request $request)
    {
        $this->validate($request,[
            'catagory'=>'required'
        ]);
        $catagory= new Catagory;
        $catagory->name=$request->input('catagory');
        $catagory->save();
        return redirect('/admin/catagory/add')->with('response','Catagory successfully added');
    }
    public function update(Request $request)
    {
        $name=$request->input('catagory');
        $id= $request->input('id');
        Catagory::where('id',intval($id))->update(['name'=>$name]);
        return redirect('/admin/catagory/add');
    }

    public function delete($id)
    {
        Catagory::where('id',intval($id))->delete();  
        return redirect('/admin/catagory/add');
    }
}
