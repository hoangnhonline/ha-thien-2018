<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cate;
use App\Models\CateParent;
use App\Models\MetaData;
use Helper, File, Session, Auth;

class CateController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index(Request $request)
    {        
        if( $request->parent_id > -1){
            $parent_id = $request->parent_id;
            $loaiSp = CateParent::find($parent_id);
        }else{
            $loaiSp = CateParent::orderBy('id')->first();
            $parent_id = $loaiSp->id;    
        }

        $items = Cate::where('parent_id', '=', $parent_id)->where('status', 1)->orderBy('display_order')->get();
        $cateParentList = CateParent::where('status', 1)->orderBy('display_order')->get();
        return view('backend.cate.index', compact( 'items', 'loaiSp' , 'parent_id', 'cateParentList'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function create(Request $request)
    {
        $parent_id = isset($request->parent_id) ? $request->parent_id : 0;
        
        $cateParentList = CateParent::where('status', 1)->orderBy('display_order')->get();

        return view('backend.cate.create', compact( 'parent_id', 'cateParentList'));
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  Request  $request
    * @return Response
    */
    public function store(Request $request)
    {
        $dataArr = $request->all();
        
        $this->validate($request,[
            'name' => 'required',
            'slug' => 'required',
        ],
        [
            'name.required' => 'Bạn chưa nhập tên danh mục',
            'slug.required' => 'Bạn chưa nhập slug',
        ]);
        $dataArr['alias'] = Helper::stripUnicode($dataArr['name']);
        
        $dataArr['created_user'] = Auth::user()->id;

        $dataArr['updated_user'] = Auth::user()->id;

        $dataArr['display_order'] = Helper::getNextOrder('cate', ['parent_id' => $dataArr['parent_id']]);
        
        $dataArr['is_hot'] = isset($dataArr['is_hot']) ? 1 : 0;            

        $dataArr['display_order'] = 1;

        $rs = Cate::create($dataArr);        
        $id = $rs->id;

        $this->storeMeta( $id, 0, $dataArr);

        Session::flash('message', 'Tạo mới danh mục thành công');

        return redirect()->route('cate.index',[$dataArr['parent_id']]);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
    public function show($id)
    {
    //
    }
    public function storeMeta( $id, $meta_id, $dataArr ){
       
        $arrData = [ 'title' => $dataArr['meta_title'], 'description' => $dataArr['meta_description'], 'keywords'=> $dataArr['meta_keywords'], 'custom_text' => $dataArr['custom_text'], 'updated_user' => Auth::user()->id ];
        if( $meta_id == 0){
            $arrData['created_user'] = Auth::user()->id;            
            $rs = MetaData::create( $arrData );
            $meta_id = $rs->id;
            
            $modelSp = Cate::find( $id );
            $modelSp->meta_id = $meta_id;
            $modelSp->save();
        }else {
            $model = MetaData::find($meta_id);           
            $model->update( $arrData );
        }              
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
    public function edit($id)
    {
        $detail = Cate::find($id);
        $cateParentList = CateParent::where('status', 1)->orderBy('display_order')->get();
        $meta = (object) [];
        if ( $detail->meta_id > 0){
            $meta = MetaData::find( $detail->meta_id );
        }       
        $loaiSp = CateParent::find($detail->parent_id); 
        return view('backend.cate.edit', compact( 'detail', 'cateParentList', 'meta', 'loaiSp'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  Request  $request
    * @param  int  $id
    * @return Response
    */
    public function update(Request $request)
    {
        $dataArr = $request->all();
        
        $this->validate($request,[
            'name' => 'required',
            'slug' => 'required',
        ],
        [
            'name.required' => 'Bạn chưa nhập tên danh mục',
            'slug.required' => 'Bạn chưa nhập slug',
        ]);
        $dataArr['alias'] = Helper::stripUnicode($dataArr['name']);

        $model = Cate::find($dataArr['id']);

        $dataArr['updated_user'] = Auth::user()->id;
       
        $dataArr['is_hot'] = isset($dataArr['is_hot']) ? 1 : 0;           

        $model->update($dataArr);

        $this->storeMeta( $dataArr['id'], $dataArr['meta_id'], $dataArr);
        Session::flash('message', 'Cập nhật danh mục thành công');

        return redirect()->route('cate.edit', $dataArr['id']);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return Response
    */
    public function destroy($id)
    {
        // delete
        $model = Cate::find($id);
        $model->delete();

        // redirect
        Session::flash('message', 'Xóa danh mục thành công');
        return redirect()->route('cate.index',[$model->parent_id]);
    }
}
