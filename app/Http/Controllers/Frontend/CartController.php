<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CateParent;
use App\Models\Cate;
use App\Models\Product;
use App\Models\ProductImg;
use App\Models\Orders;
use App\Models\OrderDetail;
use App\Models\Settings;
use App\Models\Customer;
use App\Models\BaoKimPayment;

use Helper, File, Session, Auth;
use Mail;

class CartController extends Controller
{

    public function __construct(){      
        
    }
    public function index(Request $request)
    {
        $getlistProduct = Session::get('products');
        if(!empty($getlistProduct)){
        $listProductId = array_keys($getlistProduct);
        $arrProductInfo = Product::whereIn('product.id', $listProductId)
                            ->leftJoin('product_img', 'product_img.id', '=','product.thumbnail_id')
                            ->select('product_img.image_url', 'product.*')->get();
        }
        $seo['title'] = $seo['description'] = $seo['keywords'] = "Giỏ hàng";
        return view('frontend.cart.index', compact('arrProductInfo', 'getlistProduct', 'seo'));
    }
    public function payment(Request $request){        

        $getlistProduct = Session::get('products');
        if(empty($getlistProduct)){
            return redirect()->route('home');   
        }
        $listProductId = array_keys($getlistProduct);
        $arrProductInfo = Product::whereIn('product.id', $listProductId)
                            ->leftJoin('product_img', 'product_img.id', '=','product.thumbnail_id')
                            ->select('product_img.image_url', 'product.*')->get();
        $seo['title'] = $seo['description'] = $seo['keywords'] = "Thanh toán";
        $cityList = City::all();
        return view('frontend.cart.payment', compact('arrProductInfo', 'getlistProduct', 'seo', 'cityList'));
    }
    public function addressInfo(Request $request){        
        if(!Session::has('products')) {
            return redirect()->route('home');
        }
        $addressInfo = Session::get('address_info'); 
        if(empty($addressInfo) && Session::get('userId') > 0){
            $detailCustomer = Customer::find(Session::get('userId'));
            $addressInfo['fullname'] = $detailCustomer->full_name;
            $addressInfo['email'] = $detailCustomer->email;
            $addressInfo['address'] = $detailCustomer->address;
            $addressInfo['phone'] = $detailCustomer->phone;
        }
        $getlistProduct = Session::get('products');
        
        $listProductId = array_keys($getlistProduct);
    
        $arrProductInfo = Product::whereIn('product.id', $listProductId)->get();
        
        
        $seo['title'] = $seo['description'] = $seo['keywords'] = "Thời gian & địa chỉ nhận hàng";

        return view('frontend.cart.address-info', compact('arrProductInfo', 'getlistProduct', 'seo', 'addressInfo'));
    }

    public function storeAddress(Request $request){
        $dataArr = $request->all();
        
        Session::put('address_info', $dataArr);

        return redirect()->route('payment-method');
    }

    public function paymentInfo(Request $request){     
        
        $addressInfo = Session::get('address_info');     
        
        $getlistProduct = Session::get('products');
        
        $listProductId = array_keys($getlistProduct);
    
        $arrProductInfo = Product::whereIn('product.id', $listProductId)->get();        
        
        $seo['title'] = $seo['description'] = $seo['keywords'] = "Phương thức thanh toán";
        
        return view('frontend.cart.payment-method', compact('getlistProduct', 'listProductId', 'arrProductInfo', 'seo', 'addressInfo'));
    }

    public function shortCart(Request $request)
    {
        $getlistProduct = Session::get('products');       
        if(!empty($getlistProduct)){
            $listProductId = array_keys($getlistProduct);        
            $arrProductInfo = Product::whereIn('product.id', $listProductId)
                            ->leftJoin('product_img', 'product_img.id', '=','product.thumbnail_id')
                            ->select('product_img.image_url', 'product.*')->get();        
        }else{
            $arrProductInfo = Product::where('id', -1)->get();       
        }
        return view('frontend.cart.ajax.short-cart', compact('arrProductInfo', 'getlistProduct'));
    }

    public function update(Request $request)
    {
        $listProduct = Session::get('products');
        if($request->id > 0){
            if($request->quantity) {
                $listProduct[$request->id] = $request->quantity;
            } else {
                unset($listProduct[$request->id]);
            }
            Session::put('products', $listProduct);
        }
        return 'sucess';
    }

    public function addProduct(Request $request)
    {
        $id = $request->id;
        $quantity = 1;
        if($id > 0){
            $listProduct = Session::get('products');
            
            if(!empty($listProduct[$request->id])) {
                $listProduct[$request->id] += $quantity;
            } else {
                $listProduct[$request->id] = $quantity;
            }

            Session::put('products', $listProduct);
        }

        return 'sucess';
    }        

    public function saveOrder(Request $request)
    {
        if(!Session::has('products')) {
            return redirect()->route('home');
        }
        $getlistProduct = Session::get('products');
        $listProductId = array_keys($getlistProduct);

        $addressInfo = Session::get('address_info'); 
        $dataArr = $request->all();
        
        $arrProductInfo = Product::whereIn('product.id', $listProductId)
                            ->leftJoin('product_img', 'product_img.id', '=','product.thumbnail_id')
                            ->select('product_img.image_url', 'product.*')->get();
        $user_id = !Session::get('userId') ? null : Session::get('userId');
        $dataArr['total_bill'] = 0;
        $dataArr['total_product'] = array_sum($getlistProduct);
        $dataArr['address']  = $addressInfo['address'];        
        $dataArr['fullname']  = $addressInfo['fullname'];
        $dataArr['email']  = $email = $addressInfo['email'];
        $dataArr['phone']  = $addressInfo['phone'];
        $dataArr['method_id'] = $request->method_id;
        $dataArr['notes'] = $request->notes;
        $dataArr['customer_id'] = $user_id;
        if( isset($addressInfo['choose_other_address']) && $addressInfo['choose_other_address'] == 1 ){
            $dataArr['is_other_address']  = 1;
            $dataArr['other_address']  = $addressInfo['other_address'];            
            $dataArr['other_fullname']  = $addressInfo['other_fullname'];
            $dataArr['other_email']  = $addressInfo['other_email'];
            $dataArr['other_phone']  = $addressInfo['other_phone'];
        }
        $arrPrice = [];
        foreach ($arrProductInfo as $product) {
            if(Helper::isShared(Session::get('userId'), $product->id)){
                $price = $product->price_share;
            }else{
            $price = $product->is_sale ? $product->price_sale : $product->price; 
            }

            $dataArr['total_bill'] += $price * $getlistProduct[$product->id];
            $arrPrice[$product->id] = $price;
        }

        $dataArr['total_payment'] = $dataArr['total_bill'];

        // tinh chiet khau
        $totalCk = 0;                 
        if(Session::get('cap_bac') > 0){
              $cap_bac = Session::get('cap_bac'); 
              if($cap_bac == 1){
                $ck = 2;
              }elseif($cap_bac == 2){
                $ck = 3;
              }elseif($cap_bac == 3){
                $ck = 4;
              }
              $totalCk = $dataArr['total_bill']*$ck/100;

              $dataArr['total_payment'] = $dataArr['total_bill'] - $totalCk;
              $dataArr['discount'] = $totalCk;
        }

        $rs = Orders::create($dataArr);
        if($user_id){
            $detailCustomer = Customer::find($user_id);
            $detailCustomer->full_name = $addressInfo['fullname'];
            $detailCustomer->phone = $addressInfo['phone'];
            $detailCustomer->address = $addressInfo['address'];
            $detailCustomer->save();
        }
        $order_id = $rs->id;
        $orderDetail = Orders::find($order_id);
        Session::put('order_id', $order_id);   
       
        foreach ($arrProductInfo as $product) {            
            # code...
            $dataDetail['product_id']        = $product->id;
            $dataDetail['amount']     = $getlistProduct[$product->id];
            $dataDetail['price']      = $arrPrice[$product->id];
            $dataDetail['order_id']     = $order_id;
            $dataDetail['total']    = $getlistProduct[$product->id]*$arrPrice[$product->id];

            OrderDetail::create($dataDetail); 
        }
        
        $settingArr = Settings::whereRaw('1')->lists('value', 'name');
        $adminMailArr = explode(',', $settingArr['email_cc']);
        if($email != ''){

            $emailArr = array_merge([$email], $adminMailArr);
        }else{
            $emailArr = $adminMailArr;
        }
        
        // send email
        $order_id =str_pad($order_id, 6, "0", STR_PAD_LEFT);
        
        if(!empty($emailArr)){
            Mail::send('frontend.cart.email',
                [                    
                    'orderDetail'             => $orderDetail,
                    'arrProductInfo'    => $arrProductInfo,
                    'getlistProduct'    => $getlistProduct,            
                    'method_id' => $dataArr['method_id'],
                    'order_id' => $order_id                    
                ],
                function($message) use ($email, $adminMailArr, $order_id) {
                    $message->subject('Xác nhận đơn hàng hàng #'.$order_id);
                    $message->to($email);
                    $message->bcc($adminMailArr);
                    $message->from('muanhanhgiatot.vn@gmail.com', 'muanhanhgiatot.vn');
                    $message->sender('muanhanhgiatot.vn@gmail.com', 'muanhanhgiatot.vn');
            });
        }
        //Session::put('baokim', 0);
        /*if( $request->method_id == 3){
            Session::put('baokim', 1);
            $modelBaoKim = new BaoKimPayment();
            $url = $modelBaoKim->createRequestUrl($order_id, "hoangnhonline@gmail.com", $dataArr['total_payment'], 0, 0, "Đơn hàng $order_id của khách hàng ".$dataArr['fullname'], route('success'), route('success', ['cancel' => 1]), route('home'));
            return redirect($url);
        }*/
        return redirect()->route('success');
        
    }    
    public function success(Request $request){           
        if(empty(Session::get('products'))) {
            return redirect()->route('home');
        }
        $order_id = Session::get('order_id');
        //$order_id = Session::get('baokim');
        
        $orderDetail = Orders::find($order_id);
        /*
        $isCancel = $request->cancel ? 1 : 0;
        if( Session::get('baokim') == 1 && $isCancel == 0 ){
            $modelBaoKim = new BaoKimPayment();
            if( $modelBaoKim->verifyResponseUrl()){
                $transaction_status = $request->transaction_status;
                Orders::where('id', $order_id)->update('payment_status', $transaction_status);
            }
        }
        */
        $seo['title'] = $seo['description'] = $seo['keywords'] = "Mua hàng thành công";
        Session::put('products', []);
        Session::put('address_info', []);
        //Session::put('baokim', 0);        

        return view('frontend.cart.success', compact('order_id', 'seo', 'orderDetail'));
    }
    public function deleteAll(){
        Session::put('products', []);
        Session::put('address_info', []);
        return redirect()->route('cart');
    }
}

