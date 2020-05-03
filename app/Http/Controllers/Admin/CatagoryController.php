<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Catagory;
class CatagoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

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
            return view('catagory.Catagory',['mode'=>'Edit','invalid'=>'id','catagories'=>$catagoryAll]);
        }else{
            return view('catagory.Catagory',['mode'=>'Edit','id'=>$id,'value'=>$catagory->name,'catagories'=>$catagoryAll]);
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
            return view('catagory.Catagory',['mode'=>'Add','catagories'=>$catagory]);
    }

    public function insert(Request $request)
    {
        $name=$request->input('catagory');
        Catagory::insert(['name'=>$name]);
        return redirect('/admin/catagory/add');
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
