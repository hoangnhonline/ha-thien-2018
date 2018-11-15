<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Color;
use Helper, File, Session, Auth, DB;

class ColorController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index(Request $request)
    {

        $query = Color::whereRaw('1');

        $items = $query->orderBy('display_order')->paginate(20);
        
      
        return view('backend.color.index', compact( 'items' ));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function create(Request $request)
    {          

        return view('backend.color.create');
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
            'color_code' => 'required',            
        ],
        [                                    
            'name.required' => 'Bạn chưa nhập tên màu',
            'color_code.required' => "Bạn chưa nhập mã màu"
        ]);       
        
        unset($dataArr['_token']);
        
        DB::table('color')->insert($dataArr);        
        
        Session::flash('message', 'Tạo mới thành công');

        return redirect()->route('color.index');
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

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
    public function edit($id)
    {        

        $detail = Color::find($id);

        return view('backend.color.edit', compact('detail'));
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
            'color_code' => 'required',            
        ],
        [                                    
            'name.required' => 'Bạn chưa nhập tên màu',
            'color_code.required' => "Bạn chưa nhập mã màu"
        ]);           
        
        
        unset($dataArr['_token']);
        DB::table('color')->where('id', $dataArr['id'])->update($dataArr);
       
        Session::flash('message', 'Cập nhật thành công');        

        return redirect()->route('color.edit', $dataArr['id']);
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
        $model = Color::find($id);
        $model->delete();

        // redirect
        Session::flash('message', 'Xóa màu thành công');
        return redirect()->route('color.index');
    }
}