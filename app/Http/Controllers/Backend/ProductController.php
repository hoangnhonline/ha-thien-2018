<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\TagObjects;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CateParent;
use App\Models\Cate;
use App\Models\Color;
use App\Models\ProductImg;
use App\Models\MetaData;
use App\Models\ThongTinChung;
use App\Models\Tag;

use Helper, File, Session, Auth, URL, Image;

class ProductController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index(Request $request)
    {

        $arrSearch['status'] = $status = isset($request->status) ? $request->status : 1;
        $arrSearch['is_hot'] = $is_hot = isset($request->is_hot) ? $request->is_hot : null;
        $arrSearch['is_sale'] = $is_sale = isset($request->is_sale) ? $request->is_sale : null;
        $arrSearch['is_new'] = $is_new = isset($request->is_new) ? $request->is_new : null;
        $arrSearch['is_fbshare'] = $is_fbshare = isset($request->is_fbshare) ? $request->is_fbshare : null;
        $arrSearch['parent_id'] = $parent_id = isset($request->parent_id) ? $request->parent_id : 0;
        $arrSearch['cate_id'] = $cate_id = isset($request->cate_id) ? $request->cate_id : null;
       
        $arrSearch['name'] = $name = isset($request->name) && trim($request->name) != '' ? trim($request->name) : '';
        
        $query = Product::where('product.status', $status);
        if( $is_hot ){
            $query->where('product.is_hot', $is_hot);
        }
        if( $is_new ){
            $query->where('product.is_new', $is_new);
        }
        if( $is_fbshare ){
            $query->where('product.is_fbshare', $is_fbshare);
        }
        if( $is_sale ){
            $query->where('product.is_sale', $is_sale);
        }
        if( $parent_id ){
            $query->where('product.parent_id', $parent_id);
        }
        if( $cate_id ){
            $query->where('product.cate_id', $cate_id);
        }        
  
        if( $name != ''){
            $query->where('product.name', 'LIKE', '%'.$name.'%');          
        }
        $query->join('users', 'users.id', '=', 'product.created_user');
        $query->join('cate_parent', 'cate_parent.id', '=', 'product.parent_id');        
        $query->leftJoin('product_img', 'product_img.id', '=','product.thumbnail_id');        
        if($is_hot){
            $query->orderBy('product.display_order', 'asc');
        }else{
            $query->orderBy('product.id', 'desc');    
        }
        
        $items = $query->select(['product_img.image_url','product.*','product.id as product_id', 'full_name' , 'product.created_at as time_created', 'users.full_name', 'cate_parent.name as ten_loai'])
        ->paginate(50);   
        
        $cateParentList = CateParent::all();  
        if( $parent_id  > -1 ){
            $cateArr = Cate::where('parent_id', $parent_id)->orderBy('display_order', 'desc')->get();
        }else{
            $cateArr = (object) [];
        }

        return view('backend.product.index', compact( 'items', 'arrSearch', 'cateParentList', 'cateArr'));
    }
    private function processRelation($dataArr, $object_id, $type = 'add'){
    
        if( $type == 'edit'){
          
            TagObjects::deleteTags( $object_id, 1);      

        }
        // xu ly tags
        if( !empty( $dataArr['tags'] ) && $object_id ){
            foreach ($dataArr['tags'] as $tag_id) {
                TagObjects::create(['object_id' => $object_id, 'tag_id' => $tag_id, 'type' => 1]);
            }
        }      
    }
    public function kho(Request $request)
    {

        $arrSearch['status'] = $status = isset($request->status) ? $request->status : 1;
        $arrSearch['is_hot'] = $is_hot = isset($request->is_hot) ? $request->is_hot : null;
        $arrSearch['is_sale'] = $is_sale = isset($request->is_sale) ? $request->is_sale : null;
        $arrSearch['is_new'] = $is_new = isset($request->is_new) ? $request->is_new : null;
        $arrSearch['out_of_stock'] = $out_of_stock = isset($request->out_of_stock) ? $request->out_of_stock : null;
        
        $arrSearch['parent_id'] = $parent_id = isset($request->parent_id) ? $request->parent_id : null;
        $arrSearch['cate_id'] = $cate_id = isset($request->cate_id) ? $request->cate_id : null;
       
        $arrSearch['name'] = $name = isset($request->name) && trim($request->name) != '' ? trim($request->name) : '';
        
        $query = Product::where('product.status', $status);
        if( $out_of_stock ){
            $query->where('product.out_of_stock', $out_of_stock);
        }
        if( $is_hot ){
            $query->where('product.is_hot', $is_hot);
        }
        if( $is_new ){
            $query->where('product.is_new', $is_new);
        }
       
        if( $is_sale ){
            $query->where('product.is_sale', $is_sale);
        }
        if( $parent_id ){
            $query->where('product.parent_id', $parent_id);
        }
        if( $cate_id ){
            $query->where('product.cate_id', $cate_id);
        }        
  
        if( $name != ''){
            $query->where('product.name', 'LIKE', '%'.$name.'%');          
        }
        $query->join('users', 'users.id', '=', 'product.created_user');
        $query->join('cate_parent', 'cate_parent.id', '=', 'product.parent_id');
        $query->join('cate', 'cate.id', '=', 'product.cate_id');             
        if($is_hot){
            $query->orderBy('product.display_order', 'asc');
        }else{
            $query->orderBy('product.id', 'desc');    
        }
        
        $items = $query->select(['product.*','product.id as product_id', 'full_name' , 'product.created_at as time_created', 'users.full_name', 'cate_parent.name as ten_loai', 'cate.name as ten_cate'])
        ->paginate(50);   

        $cateParentList = CateParent::all();  
        if( $parent_id ){
            $cateArr = Cate::where('parent_id', $parent_id)->orderBy('display_order', 'desc')->get();
        }else{
            $cateArr = (object) [];
        }

        return view('backend.product.kho', compact( 'items', 'arrSearch', 'cateParentList', 'cateArr'));
    }
    public function short(Request $request)
    {
        
        $arrSearch['status'] = $status = isset($request->status) ? $request->status : 1;
        $arrSearch['parent_id'] = $parent_id = isset($request->parent_id) ? $request->parent_id : null;
        $arrSearch['cate_id'] = $cate_id = isset($request->cate_id) ? $request->cate_id : null;
        $arrSearch['name'] = $name = isset($request->name) && trim($request->name) != '' ? trim($request->name) : '';
        
        $query = Product::where('product.status', $status);
        if( $parent_id ){
            $query->where('product.parent_id', $parent_id);
        }
        if( $cate_id ){
            $query->where('product.cate_id', $cate_id);
        }
        if( $name != ''){
            $query->where('product.name', 'LIKE', '%'.$name.'%');         
        }        
        $query->orderBy('product.id', 'desc');
        $items = $query->select(['product.*','product.id as product_id' , 'product.created_at as time_created'])
        ->paginate(50);

        $cateParentList = CateParent::all();  
        if( $parent_id ){
            $cateArr = Cate::where('parent_id', $parent_id)->orderBy('display_order', 'desc')->get();
        }else{
            $cateArr = (object) [];
        }

        return view('backend.product.short', compact( 'items', 'arrSearch', 'cateParentList', 'cateArr'));
    }    
    public function ajaxSearch(Request $request){    
        $search_type = $request->search_type;
        $arrSearch['parent_id'] = $parent_id = isset($request->parent_id) ? $request->parent_id : -1;
        $arrSearch['cate_id'] = $cate_id = isset($request->cate_id) ? $request->cate_id : -1;
        $arrSearch['name'] = $name = isset($request->name) && trim($request->name) != '' ? trim($request->name) : '';
        
        $query = Product::whereRaw('1');
        
        if( $parent_id ){
            $query->where('product.parent_id', $parent_id);
        }
        if( $cate_id ){
            $query->where('product.cate_id', $cate_id);
        }
        if( $name != ''){
            $query->where('product.name', 'LIKE', '%'.$name.'%');
            $query->orWhere('name_extend', 'LIKE', '%'.$name.'%');
        }
        $query->join('users', 'users.id', '=', 'product.created_user');
        $query->join('cate_parent', 'cate_parent.id', '=', 'product.parent_id');
        $query->join('cate', 'cate.id', '=', 'product.cate_id');
        $query->leftJoin('product_img', 'product_img.id', '=','product.thumbnail_id');        
        $query->orderBy('product.id', 'desc');
        $items = $query->select(['product_img.image_url','product.*','product.id as product_id', 'full_name' , 'product.created_at as time_created', 'users.full_name', 'cate_parent.name as ten_loai', 'cate.name as ten_cate'])
        ->paginate(1000);

        $cateParentList = CateParent::all();  
        if( $parent_id ){
            $cateArr = Cate::where('parent_id', $parent_id)->orderBy('display_order', 'desc')->get();
        }else{
            $cateArr = (object) [];
        }

        return view('backend.product.content-search', compact( 'items', 'arrSearch', 'cateParentList', 'cateArr', 'search_type'));
    }
  
    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function create(Request $request)
    {
        $parent_id = $request->parent_id ? $request->parent_id : 0;
        $cate_id = $request->cate_id ? $request->cate_id : null;
        $cateArr = (object) [];
      $tagArr = Tag::where('type', 1)->get();
        $cateParentList = CateParent::all();
        
        if( $parent_id > -1){
            
            $cateArr = Cate::where('parent_id', $parent_id)->select('id', 'name')->orderBy('display_order', 'desc')->get();           
        }
        $colorArr = Color::orderBy('display_order')->get();        
        return view('backend.product.create', compact('cateParentList', 'cateArr', 'colorArr', 'parent_id', 'cate_id', 'tagArr'));
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
            'slug' => 'required' ,                     
            'inventory' => 'required'       
        ],
        [
            'name.required' => 'Bạn chưa nhập tên sản phẩm',
            'slug.required' => 'Bạn chưa nhập slug',            
            'inventory.required' => 'Bạn chưa nhập số lượng tồn'                      
        ]);
       
        $dataArr['is_hot'] = isset($dataArr['is_hot']) ? 1 : 0;
        $dataArr['is_sale'] = isset($dataArr['is_sale']) ? 1 : 0;         
        $dataArr['is_new'] = isset($dataArr['is_new']) ? 1 : 0;
        $dataArr['is_fbshare'] = isset($dataArr['is_fbshare']) ? 1 : 0;
        
        $dataArr['alias'] = Helper::stripUnicode($dataArr['name']);
        $dataArr['slug'] = str_replace(".", "-", $dataArr['slug']);
        $dataArr['slug'] = str_replace("(", "-", $dataArr['slug']);
        $dataArr['slug'] = str_replace(")", "", $dataArr['slug']);
        
        $dataArr['price'] = str_replace(',', '', $request->price);
        $dataArr['price_sale'] = str_replace(',', '', $request->price_sale);
        $dataArr['price_share'] = str_replace(',', '', $request->price_share);
        
        $dataArr['inventory'] = str_replace(',', '', $request->inventory);

        $dataArr['status'] = 1;
        $dataArr['price_sell'] = $dataArr['is_sale'] == 1 ? $dataArr['price_sale'] : $dataArr['price'];
        $dataArr['created_user'] = Auth::user()->id;

        $dataArr['updated_user'] = Auth::user()->id;

        if($dataArr['is_fbshare'] == 1){
            $dataArr['is_sale'] = $dataArr['price_sale'] = 0;

        }
        //luu display order
        if($dataArr['is_hot'] == 1){
            $dataArr['display_order'] = Helper::getNextOrder('product', 
                                            [
                                            'parent_id' => $dataArr['parent_id'],
                                            'cate_id' => $dataArr['cate_id']
                                        ]);
        }
        $rs = Product::create($dataArr);

        $product_id = $rs->id;

        //$this->storeThuocTinh( $product_id, $dataArr);

        $this->storeImage( $product_id, $dataArr);
        $this->storeMeta($product_id, 0, $dataArr);
        $this->processRelation($dataArr, $product_id);
        Session::flash('message', 'Tạo mới sản phẩm thành công');

        return redirect()->route('product.index', [
                        'parent_id' => $dataArr['parent_id'], 
                        'cate_id' => $dataArr['cate_id']            
                        ]
                        );
    }

    public function storeMeta( $id, $meta_id, $dataArr ){
       
        $arrData = ['title' => $dataArr['meta_title'], 'description' => $dataArr['meta_description'], 'keywords'=> $dataArr['meta_keywords'], 'custom_text' => $dataArr['custom_text'], 'updated_user' => Auth::user()->id];
        if( $meta_id == 0){
            $arrData['created_user'] = Auth::user()->id;            
            $rs = MetaData::create( $arrData );
            $meta_id = $rs->id;

            $modelSp = Product::find( $id );
            $modelSp->meta_id = $meta_id;
            $modelSp->save();
        }else {
            $model = MetaData::find($meta_id);           
            $model->update( $arrData );
        }              
    }   

  public function storeImage($id, $dataArr){        
        //process old image
        $imageIdArr = isset($dataArr['image_id']) ? $dataArr['image_id'] : [];
        $hinhXoaArr = ProductImg::where('product_id', $id)->whereNotIn('id', $imageIdArr)->lists('id');
        if( $hinhXoaArr )
        {
            foreach ($hinhXoaArr as $image_id_xoa) {
                $model = ProductImg::find($image_id_xoa);
                $urlXoa = config('phukien.upload_path')."/".$model->image_url;
                if(is_file($urlXoa)){
                    unlink($urlXoa);
                }
                $model->delete();
            }
        }       

        //process new image
        if( isset( $dataArr['thumbnail_id'])){
            $thumbnail_id = $dataArr['thumbnail_id'];

            $imageArr = []; 

            if( !empty( $dataArr['image_tmp_url'] )){

                foreach ($dataArr['image_tmp_url'] as $k => $image_url) {
                    
                    $origin_img = base_path().$image_url;
                    if( $image_url ){

                        $imageArr['is_thumbnail'][] = $is_thumbnail = $dataArr['thumbnail_id'] == $image_url  ? 1 : 0;

                        $img = Image::make($origin_img);
                        $w_img = $img->width();
                        $h_img = $img->height();

                        $tmpArrImg = explode('/', $origin_img);
                        
                        $new_img = config('phukien.upload_thumbs_path').end($tmpArrImg);
                       
                        if($w_img/$h_img > 300/200){

                            Image::make($origin_img)->resize(null, 200, function ($constraint) {
                                    $constraint->aspectRatio();
                            })->crop(300, 200)->save($new_img);
                        }else{
                            Image::make($origin_img)->resize(300, null, function ($constraint) {
                                    $constraint->aspectRatio();
                            })->crop(300, 200)->save($new_img);
                        }                           

                        $imageArr['name'][] = $image_url;
                        
                    }
                }
            }
            if( !empty($imageArr['name']) ){
                foreach ($imageArr['name'] as $key => $name) {
                    $rs = ProductImg::create(['product_id' => $id, 'image_url' => $name, 'display_order' => 1]);                
                    $image_id = $rs->id;
                    if( $imageArr['is_thumbnail'][$key] == 1){
                        $thumbnail_id = $image_id;
                    }
                }
            }
            $model = Product::find( $id );
            $model->thumbnail_id = $thumbnail_id;
            $model->save();
        }
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
          $tagArr = Tag::where('type', 1)->get();
        $hinhArr = (object) [];
        $detail = Product::find($id);

        $hinhArr = ProductImg::where('product_id', $id)->lists('image_url', 'id');
             

        $cateParentList = CateParent::all();
        
        $parent_id = $detail->parent_id; 
        
        $cateArr = Cate::where('parent_id', $parent_id)->select('id', 'name')->orderBy('display_order', 'desc')->get();
        
        $meta = (object) [];
        if ( $detail->meta_id > 0){
            $meta = MetaData::find( $detail->meta_id );
        }       
            $tagSelected = Product::productTag($id);  
        $colorArr = Color::all();          
            
        return view('backend.product.edit', compact( 'detail', 'hinhArr', 'colorArr', 'cateParentList', 'cateArr', 'meta', 'tagArr', 'tagSelected'));
    }
    public function copy($id)
    {
        
        $hinhArr = (object) [];
        $detail = Product::find($id);

        $hinhArr = ProductImg::where('product_id', $id)->lists('image_url', 'id');
        
            
        $cateParentList = CateParent::all();
        
        $parent_id = $detail->parent_id; 
            
        $cateArr = Cate::where('parent_id', $parent_id)->select('id', 'name')->orderBy('display_order', 'desc')->get();
        
       
        $meta = (object) [];
        if ( $detail->meta_id > 0){
            $meta = MetaData::find( $detail->meta_id );
        }       
        
        $colorArr = Color::all();          
            
        return view('backend.product.copy', compact( 'detail', 'hinhArr', 'colorArr', 'cateParentList', 'cateArr', 'meta'));
    }
    public function ajaxDetail(Request $request)
    {       
        $id = $request->id;
        $detail = Product::find($id);
        return view('backend.product.ajax-detail', compact( 'detail' ));
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
            'inventory' => 'required'            
        ],
        [
            'name.required' => 'Bạn chưa nhập tên sản phẩm',
            'slug.required' => 'Bạn chưa nhập slug',                        
            'inventory.required' => 'Bạn chưa nhập số lượng tồn'                    
        ]);

        
        $dataArr['is_hot'] = isset($dataArr['is_hot']) ? 1 : 0;
        $dataArr['is_sale'] = isset($dataArr['is_sale']) ? 1 : 0;          
        $dataArr['is_new'] = isset($dataArr['is_new']) ? 1 : 0;
        $dataArr['is_fbshare'] = isset($dataArr['is_fbshare']) ? 1 : 0;

        $dataArr['slug'] = str_replace(".", "-", $dataArr['slug']);
        $dataArr['slug'] = str_replace("(", "-", $dataArr['slug']);
        $dataArr['slug'] = str_replace(")", "", $dataArr['slug']);
        $dataArr['alias'] = Helper::stripUnicode($dataArr['name']);

        $dataArr['price'] = str_replace(',', '', $request->price);
        $dataArr['price_sale'] = str_replace(',', '', $request->price_sale);
        $dataArr['price_share'] = str_replace(',', '', $request->price_share);

        
        $dataArr['inventory'] = str_replace(',', '', $request->inventory);

        $dataArr['updated_user'] = Auth::user()->id;    

        $dataArr['price_sell'] = $dataArr['is_sale'] == 1 ? $dataArr['price_sale'] : $dataArr['price'];
        
        if($dataArr['is_fbshare'] == 1){
            $dataArr['is_sale'] = $dataArr['price_sale'] = 0;
        }

        $model = Product::find($dataArr['id']);

        $model->update($dataArr);
        
        $product_id = $dataArr['id'];
       
        //$this->storeThuocTinh( $product_id, $dataArr);

        $this->storeMeta( $product_id, $dataArr['meta_id'], $dataArr);
        $this->storeImage( $product_id, $dataArr);
         $this->processRelation($dataArr, $product_id, 'edit');
        Session::flash('message', 'Chỉnh sửa thành công');

        return redirect()->route('product.edit', $product_id);
        
    }
    public function ajaxSaveInfo(Request $request){
        
        $dataArr = $request->all();

        $dataArr['alias'] = Helper::stripUnicode($dataArr['name']);
        
        $dataArr['updated_user'] = Auth::user()->id;
        
        $model = Product::find($dataArr['id']);

        $model->update($dataArr);
        
        $product_id = $dataArr['id'];        

        Session::flash('message', 'Chỉnh sửa thành công');

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
        $model = Product::find($id);        
        $model->delete();
        ProductImg::where('product_id', $id)->delete(); 
        // redirect
        Session::flash('message', 'Xóa sản phẩm thành công');
        
        return redirect(URL::previous());//->route('product.short');
        
    }
}
