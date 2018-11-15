<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CateParent;
use App\Models\Cate;
use App\Models\Product;
use App\Models\ProductImg;
use App\Models\ProductMuaNhanh;
use App\Models\ProductImgMuaNhanh;
use App\Models\Banner;
use App\Models\Pages;
use App\Models\Articles;
use App\Models\ArticlesCate;
use App\Models\Customer;
use App\Models\Newsletter;
use App\Models\Settings;
use App\Models\Color;
use App\Models\UserShare;
use App\Models\PriceRange;
use App\Models\Orders;

use Helper, File, Session, Auth, Hash,Mail;

class HomeController extends Controller
{
    
    public static $loaiSp = []; 
    public static $cateParentListKey = [];    

    public function __construct(){        
       Helper::counter(1, 3);
    }
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */   
    public function zel(Request $request){

    exec('casperjs --load-images=false test.js', $arr, $res); 
    $gia = str_replace(" ZEL/BTC Exchange / Stocks.exchange", "", $arr[0]);
    if((float) $gia >= 0.00005){
        Mail::send('zel',
            [
                'gia' => $gia
            ],
            function($message) {
                $message->subject('Giá ZEL cao');
                $message->to('hoangnhonline@gmail.com');
                $message->from('hoangnhonline@gmail.com', 'HOANGNHONLINE');
                $message->sender('hoangnhonline@gmail.com', 'HOANGNHONLINE');
        });
    }

    }
   public function dongbo(Request $request){
    $query = ProductMuaNhanh::whereRaw(' code NOT IN ( SELECT code FROM product)')->get();
    foreach($query as $pro){        
        $hinhArr = ProductImgMuaNhanh::where('product_id', $pro->id)->get();
        $data = $pro->toArray();
        $thumbnail_id = $data['thumbnail_id'];
        unset($data['id']);

        $data['thumbnail_id'] = null;
        //insert product
        $rsSP = Product::create($data);
        $product_id = $rsSP->id;
        

        foreach($hinhArr as $hinh){
            $dataHinh = $hinh->toArray();
            //var_dump($dataHinh);die;
            $dataHinh['product_id'] = $product_id;
            $hinh_id = $dataHinh['id'];
            unset($dataHinh['id']);
            //var_dump($dataHinh);
            $rs_hinh = ProductImg::create($dataHinh);
            //dd($rs_hinh);die;
            //var_dump($hinh_id);
            if($hinh_id == $thumbnail_id){
                $rsProduct = Product::find($product_id);                
                //die('123');
                $rsProduct->update(['thumbnail_id' => $rs_hinh->id]);
            }
        }
    }
   }

    public function index(Request $request)
    {   
        $productArr = [];
        $settingArr = Settings::whereRaw('1')->lists('value', 'name');
        $seo = $settingArr;
        $seo['title'] = $settingArr['site_title'];
        $seo['description'] = $settingArr['site_description'];
        $seo['keywords'] = $settingArr['site_keywords'];
        $socialImage = $settingArr['banner'];

        $cateParentHot = CateParent::getList(['limit' => 10, 'parent_id' => 6]);
        foreach($cateParentHot as $parent){        
            $productArr[$parent->id] = Product::getList(['parent_id' => $parent->id, 'limit' => 8]);
        }

        $hotProduct = Product::getList(['is_hot' => 1, 'limit' => 50]);
        $articlesHotList = Articles::where(['cate_id' => 1, 'status' => 1])->orderBy('id', 'desc')->limit(4)->get();
                
        return view('frontend.home.index', compact(                                                                
                                'articlesList', 
                                'socialImage', 
                                'seo', 
                                'cateParentHot',
                                'articlesHotList',
                                'productArr',
                                'hotProduct'
                            ));
    }
    public function shareSuccess(Request $request){
        $user_id = Session::get('userId');        
        $product_id = $request->product_id;   
        UserShare::create(['user_id' => $user_id, 'product_id' => $product_id, 'date_share' => date('Y-m-d')]);
    }
    public function pages(Request $request){
        $slug = $request->slug;

        $detailPage = Pages::where('slug', $slug)->first();
         
        if(!$detailPage){
            return redirect()->route('home');
        }
        $seo['title'] = $detailPage->meta_title ? $detailPage->meta_title : $detailPage->title;
        $seo['description'] = $detailPage->meta_description ? $detailPage->meta_description : $detailPage->title;
        $seo['keywords'] = $detailPage->meta_keywords ? $detailPage->meta_keywords : $detailPage->title;      

        $kmHot = Articles::getList(['is_hot' => 1, 'cate_id' => 2, 'limit' => 5]);   
        $colorList = Color::all(); 
        $priceList = PriceRange::all();
        $hotProductList = Product::getList(['is_hot' => 1, 'limit' => 5]);
        return view('frontend.pages.index', compact('detailPage', 'seo', 'slug', 'kmHot', 'colorList', 'priceList', 'hotProductList'));    
    }
    public function getNoti(){
        $countMess = 0;
        if(Session::get('userId') > 0){
            $countMess = CustomerNotification::where(['customer_id' => Session::get('userId'), 'status' => 1])->count();    
        }
        return $countMess;
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function search(Request $request)
    {
        $tu_khoa = $request->keyword ? $request->keyword : '';  
        $code = $request->code ? $request->code : '';         
        $price_id = $request->p ? $request->p : null;              
        $colorArr = $request->color ? $request->color : [];          
        $parent_id = $request->pid ? $request->pid : null;

        $colorArr = array_filter($colorArr);   
        $cateDetail = (object) [];
        $query = Product::where('product.status', 1);
        $query->where('inventory', '>', 0)->where('price', '>', 0)
                        ->where('out_of_stock', 0)                       
                        ->leftJoin('product_img', 'product_img.id', '=','product.thumbnail_id')
                        ->select('product_img.image_url', 'product.*');
        if($tu_khoa){
            $query->where('product.alias', 'LIKE', '%'.$tu_khoa.'%');
            $query->orWhere('product.code', 'LIKE', '%'.$tu_khoa.'%');
        }
        if($code){
            $query->where('product.code', 'LIKE', '%'.$code.'%');
        }
        if($parent_id){
            $query->where('product.parent_id', $parent_id);
        }        
       
        if(!empty($colorArr)){
            $query->whereIn('product.color_id', $colorArr);
        }

        $productList = $query->orderBy('is_hot', 'desc')->orderBy('id', 'desc')->paginate(20);
        $cateDetail->name = $seo['title'] = $seo['description'] =$seo['keywords'] = "Kết quả tìm kiếm";
        $kmHot = Articles::getList(['is_hot' => 1, 'cate_id' => 2, 'limit' => 5]);   
        $colorList = Color::all(); 
        $priceList = PriceRange::all();
        $hotProductList = Product::getList(['is_hot' => 1, 'limit' => 5]);
        return view('frontend.search.index', compact('productList', 'tu_khoa', 'seo', 'cateDetail','price_id', 'colorArr', 'parent_id', 'kmHot', 'colorList', 'priceList', 'code', 'hotProductList'));
    }
    public function ajaxTab(Request $request){
        $table = $request->type ? $request->type : 'category';
        $id = $request->id;

        $arr = Film::getFilmHomeTab( $table, $id);

        return view('frontend.index.ajax-tab', compact('arr'));
    }
    public function contact(Request $request){        

        $seo['title'] = 'Liên hệ';
        $seo['description'] = 'Liên hệ';
        $seo['keywords'] = 'Liên hệ';
        $socialImage = '';
        $kmHot = Articles::getList(['is_hot' => 1, 'cate_id' => 2, 'limit' => 5]);   
        return view('frontend.contact.index', compact('seo', 'socialImage', 'kmHot'));
    }

    public function newsList(Request $request)
    {
        $slug = $request->slug;
        $cateArr = $cateActiveArr = $moviesActiveArr = [];
       
        $cateDetail = ArticlesCate::where('slug' , $slug)->first();

        $title = trim($cateDetail->meta_title) ? $cateDetail->meta_title : $cateDetail->name;

        $articlesArr = Articles::where('cate_id', $cateDetail->id)->orderBy('id', 'desc')->paginate(10);

        $hotArr = Articles::where( ['cate_id' => $cateDetail->id, 'is_hot' => 1] )->orderBy('id', 'desc')->limit(5)->get();
        $seo['title'] = $cateDetail->meta_title ? $cateDetail->meta_title : $cateDetail->title;
        $seo['description'] = $cateDetail->meta_description ? $cateDetail->meta_description : $cateDetail->title;
        $seo['keywords'] = $cateDetail->meta_keywords ? $cateDetail->meta_keywords : $cateDetail->title;
        $socialImage = $cateDetail->image_url;       
        return view('frontend.news.index', compact('title', 'hotArr', 'articlesArr', 'cateDetail', 'seo', 'socialImage'));
    }      

     public function newsDetail(Request $request)
    {     
        $id = $request->id;

        $detail = Articles::where( 'id', $id )
                ->select('id', 'title', 'slug', 'description', 'image_url', 'content', 'meta_title', 'meta_description', 'meta_keywords', 'custom_text', 'created_at', 'cate_id')
                ->first();
        $is_km = $is_news = $is_kn = 0;
        if( $detail ){           

            $title = trim($detail->meta_title) ? $detail->meta_title : $detail->title;

            $hotArr = Articles::where( ['cate_id' => 1, 'is_hot' => 1] )->where('id', '<>', $id)->orderBy('id', 'desc')->limit(5)->get();
            $otherArr = Articles::where( ['cate_id' => 1] )->where('id', '<>', $id)->orderBy('id', 'desc')->limit(5)->get();
            $seo['title'] = $detail->meta_title ? $detail->meta_title : $detail->title;
            $seo['description'] = $detail->meta_description ? $detail->meta_description : $detail->title;
            $seo['keywords'] = $detail->meta_keywords ? $detail->meta_keywords : $detail->title;
            $socialImage = $detail->image_url; 
            $is_km = $detail->cate_id == 2 ? 1 : 0;
            $is_news = $detail->cate_id == 1 ? 1 : 0;
            $is_kn = $detail->cate_id == 4 ? 1 : 0;
            return view('frontend.news.news-detail', compact('title',  'hotArr', 'detail', 'otherArr', 'seo', 'socialImage', 'is_km', 'is_news', 'is_kn'));
        }else{
            return view('erros.404');
        }
    }

    public function registerNews(Request $request)
    {

        $register = 0; 
        $email = $request->email;
        $newsletter = Newsletter::where('email', $email)->first();
        if(is_null($newsletter)) {
           $newsletter = new Newsletter;
           $newsletter->email = $email;
           $newsletter->is_member = Customer::where('email', $email)->first() ? 1 : 0;
           $newsletter->save();
           $register = 1;
        }

        return $register;
    }

}
