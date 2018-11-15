<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CateParent;
use App\Models\Cate;
use App\Models\Settings;
use App\Models\Color;
use App\Models\Text;

use Request, Auth;

class ViewComposerServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//Call function composerSidebar
		$this->composerMenu();	
		
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Composer the sidebar
	 */
	private function composerMenu()
	{
			
		view()->composer( '*' , function( $view ){
			$cateArrByLoai = [];	
			$cateParentList = CateParent::where(['status' => 1])->orderBy('display_order')->get();

	        if( $cateParentList ){
	            foreach ( $cateParentList as $key => $value) {	            	          		
	            	$cateArrByLoai[$value->id] = Cate::where(['status' => 1, 'parent_id' => $value->id])
	                    ->orderBy('display_order')
	                    ->select('name', 'slug', 'id')
	                    ->get();
	            }
	        }    
	        $settingArr = Settings::whereRaw('1')->lists('value', 'name');
	        $routeName = \Request::route()->getName();
	  
	        $colorList = Color::orderBy('display_order')->get();

	        $textList = Text::whereRaw('1')->lists('content', 'id');              
	        
	        $isEdit = Auth::check();	    
			$view->with( [
					'cateParentList' => $cateParentList, 
					'settingArr' => $settingArr,
					'cateArrByLoai' => $cateArrByLoai,
					'routeName' => $routeName,
					'colorList' => $colorList,
					'textList' => $textList,
					'isEdit' => $isEdit
					] );
		});
	}
	
}
