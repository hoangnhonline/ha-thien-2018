<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ArticlesCate;
use App\Models\Articles;
use App\Models\Product;
use App\Models\Color;
use App\Models\PriceRange;
use App\Models\MetaData;

use Helper, File, Session, Auth;
use Mail;

class NewsController extends Controller
{
    public function newsList(Request $request)
    {
       Helper::counter(1, 3);
        $page = $request['page'] ? $request['page'] : 1;       
        $cateArr = [];
        $slug = $request->slug;
        $cateDetail = ArticlesCate::where('slug', $slug)->first();

        if( $cateDetail ){ 
            $title = trim($cateDetail->meta_title) ? $cateDetail->meta_title : $cateDetail->name;

            $articlesList = Articles::where('cate_id', $cateDetail->id)->where('status', 1)->orderBy('id', 'desc')->paginate(20);

            $hotArr = Articles::where( ['cate_id' => $cateDetail->id, 'is_hot' => 1, 'status' => 1] )->orderBy('id', 'desc')->limit(5)->get();
            $seo['title'] = $cateDetail->meta_title ? $cateDetail->meta_title : $cateDetail->title;
            $seo['description'] = $cateDetail->meta_description ? $cateDetail->meta_description : $cateDetail->title;
            $seo['keywords'] = $cateDetail->meta_keywords ? $cateDetail->meta_keywords : $cateDetail->title;
            $socialImage = $cateDetail->image_url;

            $kmHot = Articles::getList(['is_hot' => 1, 'cate_id' => 2, 'limit' => 5]);   
            $colorList = Color::all(); 
            $priceList = PriceRange::all();
            $hotProductList = Product::getList(['is_hot' => 1, 'limit' => 5]);
            return view('frontend.news.index', compact('title', 'hotArr', 'articlesList', 'cateDetail', 'seo', 'socialImage', 'page', 'kmHot', 'colorList', 'priceList', 'hotProductList'));
        }else{
            return redirect()->route('home');
        }
    }      

     public function newsDetail(Request $request)
    {         
        Helper::counter(1, 3);
        $id = $request->id;

        $detail = Articles::where( 'id', $id )->where('status', 1)
                ->select('id', 'title', 'slug', 'description', 'image_url', 'content', 'meta_id', 'created_at', 'cate_id')
                ->first();
        
        if( $detail ){           

            $hotArr = Articles::where( ['cate_id' => $detail->cate_id, 'is_hot' => 1] )->where('id', '<>', $id)->orderBy('id', 'desc')->limit(5)->get();
            $otherArr = Articles::where( ['cate_id' => $detail->cate_id] )->where('id', '<>', $id)->orderBy('id', 'desc')->limit(4)->get();
           
           if( $detail->meta_id > 0){
               $meta = MetaData::find( $detail->meta_id )->toArray();
               $seo['title'] = $meta['title'] != '' ? $meta['title'] : $detail->name;
               $seo['description'] = $meta['description'] != '' ? $meta['description'] : $detail->name;
               $seo['keywords'] = $meta['keywords'] != '' ? $meta['keywords'] : $detail->name;
            }else{
                $seo['title'] = $seo['description'] = $seo['keywords'] = $detail->name;
            } 
            $socialImage = $detail->image_url; 
          
            $tagSelected = Articles::getListTag($id);
            $cateDetail = ArticlesCate::find($detail->cate_id);

            $kmHot = Articles::getList(['is_hot' => 1, 'cate_id' => 2, 'limit' => 5]);   
            $colorList = Color::all(); 
            $priceList = PriceRange::all();
            $hotProductList = Product::getList(['is_hot' => 1, 'limit' => 5]);

            return view('frontend.news.news-detail', compact('title',  'hotArr', 'detail', 'otherArr', 'seo', 'socialImage', 'tagSelected', 'cateDetail',  'kmHot', 'colorList', 'priceList', 'hotProductList'));
        }else{
            return redirect()->route('home');
        }
    }
}

