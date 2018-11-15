<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CateParent;
use App\Models\Cate;
use App\Models\Product;
use App\Models\Articles;
use App\Models\ProductImg;
use App\Models\Banner;
use App\Models\MetaData;
use App\Models\Color;
use App\Models\PriceRange;
use App\Models\Tag;
use App\Models\TagObjects;
use App\Models\Settings;

use Helper, File, Session, Auth;

class DetailController extends Controller
{
    
    public static $loaiSp = []; 
    public static $cateParentListKey = [];    

    public function __construct(){
        
       

    }
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index(Request $request)
    {           
        Helper::counter(1, 3);
        
        $productArr = [];
        $slug = $request->slug;
        $detail = Product::where('slug', $slug)->first();
        if(!$detail){
            return redirect()->route('home');
        }
        $loaiDetail = CateParent::find( $detail->parent_id );
        $cateDetail = Cate::find( $detail->cate_id );
      
        $hinhArr = ProductImg::where('product_id', $detail->id)->get()->toArray();
        
        if( $detail->meta_id > 0){
           $meta = MetaData::find( $detail->meta_id )->toArray();
           $seo['title'] = $meta['title'] != '' ? $meta['title'] : $detail->name;
           $seo['description'] = $meta['description'] != '' ? $meta['description'] : $detail->name;
           $seo['keywords'] = $meta['keywords'] != '' ? $meta['keywords'] : $detail->name;
        }else{
            $seo['title'] = $seo['description'] = $seo['keywords'] = $detail->name;
        }               
        
        $socialImage = ProductImg::find($detail->thumbnail_id)->image_url;        
        $price = $detail->is_sale == 1 ? $detail->price_sale : $detail->price;
        $price_fm = $price - 1000000;
        $price_to = $price + 1000000;
        $query = Product::where('product.slug', '<>', '')
                    ->where('out_of_stock', 0)
                    ->where('product.cate_id', $detail->cate_id)
                    ->leftJoin('product_img', 'product_img.id', '=','product.thumbnail_id')
                    ->select('product_img.image_url', 'product.*')
                    ->where('product.id', '<>', $detail->id);    
                    $query->where(function($query) use ($price_fm, $price_to){
                        $query->where('price', '<=', $price_to)->where('price', '>=', $price_fm)->where('is_sale', 0);
                        $query->orWhere(function($query) use ($price_fm, $price_to){
                            $query->where('price_sale', '<=', $price_to)->where('price_sale', '>=', $price_fm)->where('is_sale', 1);
                        });
                    });
                    $otherList = $query->orderBy('product.id', 'desc')->limit(3)->get();
        $kmHot = Articles::getList(['is_hot' => 1, 'cate_id' => 2, 'limit' => 5]);
        $colorList = Color::all(); 
        $priceList = PriceRange::all();
        $tagSelected = Product::getListTag($detail->id); 
        return view('frontend.detail.index', compact('detail', 'loaiDetail', 'cateDetail', 'hinhArr', 'productArr', 'seo', 'socialImage', 'otherList', 'kmHot', 'colorList', 'priceList', 'tagSelected'));
    }
    public function tagDetail(Request $request){
          $settingArr = Settings::whereRaw('1')->lists('value', 'name');
        $slug = $request->slug;
        $cateDetail = Tag::where('slug', $slug)->first();
        if($cateDetail->type == 1 || $cateDetail->type == 3){ // product           
            $productList = (object)[];
            $listId = [];
            $listId = TagObjects::where(['type' => $cateDetail->type, 'tag_id' => $cateDetail->id])->lists('object_id');
            if($listId){
                $listId = $listId->toArray();
            }
            if(!empty($listId)){
                $productList = Product::getList( ['pagination' => $settingArr['product_per_page'], 'status' => 1, 'listId' => $listId] );
            }
            
            if( $cateDetail->meta_id > 0){
               $seo = MetaData::find( $cateDetail->meta_id )->toArray();
               $seo['title'] = $seo['title'] != '' ? $seo['title'] : 'Tag - '. $cateDetail->name;
               $seo['description'] = $seo['description'] != '' ? $seo['description'] : 'Tag - '. $cateDetail->name;
               $seo['keywords'] = $seo['keywords'] != '' ? $seo['keywords'] : 'Tag - '. $cateDetail->name;
               $seo['custom_text'] = $seo['custom_text'];
            }else{
                $seo['title'] = $seo['description'] = $seo['keywords'] = 'Tag - '. $cateDetail->name;
                $seo['custom_text'] = "";
            }
             $hotProductList = Product::getList(['is_hot' => 1, 'limit' => 5, 'status' => 1]);   
            return view('frontend.cate.tag', compact('productList', 'socialImage', 'seo', 'cateDetail', 'hotProductList'));
        }elseif($detail->type == 2){ // articles
            $articlesArr = (object)[];
            $listId = [];
            $listId = TagObjects::where(['type' => 2, 'tag_id' => $detail->id])->lists('object_id');
            if($listId){
                $listId = $listId->toArray();
            }
            if(!empty($listId)){
                $articlesArr = Articles::whereIn('id', $listId)->orderBy('id', 'desc')->where('cate_id', '<>', 999)->paginate(20);
            }  

            if( $detail->meta_id > 0){
               $seo = MetaData::find( $detail->meta_id )->toArray();
               $seo['title'] = $seo['title'] != '' ? $seo['title'] : 'Tag - '. $detail->name;
               $seo['description'] = $seo['description'] != '' ? $seo['description'] : 'Tag - '. $detail->name;
               $seo['keywords'] = $seo['keywords'] != '' ? $seo['keywords'] : 'Tag - '. $detail->name;
            }else{
                $seo['title'] = $seo['description'] = $seo['keywords'] = 'Tag - '. $detail->name;
            }  
            $hotProductList = Product::getList(['is_hot' => 1, 'limit' => 5, 'status' => 1]);              
            return view('frontend.news.tag', compact('title', 'articlesArr', 'seo', 'socialImage', 'detail', 'hotProductList'));
        }
    }
    public function ajaxTab(Request $request){
        $table = $request->type ? $request->type : 'category';
        $id = $request->id;

        $arr = Film::getFilmHomeTab( $table, $id);

        return view('frontend.index.ajax-tab', compact('arr'));
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function search(Request $request)
    {

        $settingArr = Settings::whereRaw('1')->lists('value', 'name');
        
        $layout_name = "main-category";
        
        $page_name = "page-category";

        $cateArr = $cateActiveArr = $moviesActiveArr = [];

        $tu_khoa = $request->k;
        
        $is_search = 1;

        $moviesArr = Film::where('alias', 'LIKE', '%'.$tu_khoa.'%')->orderBy('id', 'desc')->paginate(20);

        return view('frontend.cate', compact('settingArr', 'moviesArr', 'tu_khoa',  'is_search', 'layout_name', 'page_name' ));
    }    

    public function tags(Request $request)
    {
        $settingArr = Settings::whereRaw('1')->lists('value', 'name');

        $layout_name = "main-category";
        
        $page_name = "page-category";

        $cateArr = $cateActiveArr = $moviesActiveArr = [];
       
        $is_search = 0;
        $tagName = $request->tagName;

        $title = '';
        $cateDetail = (object) [];       
        
        $cateDetail = Tag::where('slug', $tagName)->first();
       
         $moviesArr = Film::where('status', 1)
        ->join('tag_objects', 'id', '=', 'tag_objects.object_id')
        ->where('tag_objects.tag_id', $cateDetail->id)
        ->where('tag_objects.type', 1)
        ->groupBy('object_id')
        ->orderBy('id', 'desc')->paginate(30);        
       
        $title = trim($cateDetail->meta_title) ? $cateDetail->meta_title : $cateDetail->name;
        $cateDetail->name = "Phim theo tags : ".'"'.$cateDetail->name.'"';
        

        return view('frontend.cate', compact('title', 'settingArr', 'is_search', 'moviesArr', 'cateDetail', 'layout_name', 'page_name', 'cateActiveArr', 'moviesActiveArr'));
    }
    
    public function daoDien(Request $request)
    {
        $settingArr = Settings::whereRaw('1')->lists('value', 'name');

        $layout_name = "main-category";
        
        $page_name = "page-category";

        $cateArr = $cateActiveArr = $moviesActiveArr = [];
       
        $is_search = 0;
        $name = $request->name;

        $title = '';
        $cateDetail = (object) [];       
        
        $cateDetail = Crew::where('slug', $name)->first();
       
         $moviesArr = Film::where('status', 1)
        ->join('film_crew', 'id', '=', 'film_crew.film_id')
        ->where('film_crew.crew_id', $cateDetail->id)
        ->where('film_crew.type', 2)
        ->groupBy('film_id')
        ->orderBy('id', 'desc')->paginate(30);        
       
        $title = trim($cateDetail->meta_title) ? $cateDetail->meta_title : $cateDetail->name;
        $cateDetail->name = "Phim của : ".'"'.$cateDetail->name.'"';
        

        return view('frontend.cate', compact('title', 'settingArr', 'is_search', 'moviesArr', 'cateDetail', 'layout_name', 'page_name', 'cateActiveArr', 'moviesActiveArr'));
    }

    public function dienVien(Request $request)
    {
        $settingArr = Settings::whereRaw('1')->lists('value', 'name');

        $layout_name = "main-category";
        
        $page_name = "page-category";

        $cateArr = $cateActiveArr = $moviesActiveArr = [];
       
        $is_search = 0;
        $name = $request->name;

        $title = '';
        $cateDetail = (object) [];       
        
        $cateDetail = Crew::where('slug', $name)->first();
       
         $moviesArr = Film::where('status', 1)
        ->join('film_crew', 'id', '=', 'film_crew.film_id')
        ->where('film_crew.crew_id', $cateDetail->id)
        ->where('film_crew.type', 1)
        ->groupBy('film_id')
        ->orderBy('id', 'desc')->paginate(30);         
       
        $title = trim($cateDetail->meta_title) ? $cateDetail->meta_title : $cateDetail->name;
        $cateDetail->name = "Phim của : ".'"'.$cateDetail->name.'"';
        

        return view('frontend.cate', compact('title', 'settingArr', 'is_search', 'moviesArr', 'cateDetail', 'layout_name', 'page_name', 'cateActiveArr', 'moviesActiveArr'));
    }

    public function newsList(Request $request)
    {
        $settingArr = Settings::whereRaw('1')->lists('value', 'name');
        $layout_name = "main-news";
        
        $page_name = "page-news";

        $cateArr = $cateActiveArr = $moviesActiveArr = [];
       
        $cateDetail = ArticlesCate::where('slug' , 'tin-tuc')->first();
        $title = trim($cateDetail->meta_title) ? $cateDetail->meta_title : $cateDetail->name;

        $articlesArr = Articles::where('cate_id', 1)->orderBy('id', 'desc')->paginate(10);
        $hotArr = Articles::where( ['cate_id' => 1, 'is_hot' => 1] )->orderBy('id', 'desc')->limit(5)->get();
        return view('frontend.news-list', compact('title','settingArr', 'hotArr', 'layout_name', 'page_name', 'articlesArr'));
    }

    public function newsDetail(Request $request)
    {
        $settingArr = Settings::whereRaw('1')->lists('value', 'name');
        $layout_name = "main-news";
        
        $page_name = "page-news";

        $id = $request->id;

        $detail = Articles::where( 'id', $id )
                ->select('id', 'title', 'slug', 'description', 'image_url', 'content', 'meta_title', 'meta_description', 'meta_keywords', 'custom_text')
                ->first();

        if( $detail ){
            $cateArr = $cateActiveArr = $moviesActiveArr = [];
        
            
            $title = trim($detail->meta_title) ? $detail->meta_title : $detail->title;

            $hotArr = Articles::where( ['cate_id' => 1, 'is_hot' => 1] )->where('id', '<>', $id)->orderBy('id', 'desc')->limit(5)->get();

            return view('frontend.news-detail', compact('title', 'settingArr', 'hotArr', 'layout_name', 'page_name', 'detail'));
        }else{
            return view('erros.404');
        }     

        
    }

}
