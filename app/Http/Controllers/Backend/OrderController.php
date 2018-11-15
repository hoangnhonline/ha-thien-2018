<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Orders;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Settings;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Mail;
class OrderController extends Controller
{
    protected $list_status = [
        0 => 'Đã tiếp nhập đơn hàng',
        1 => 'Hàng đang được chuẩn bị',    
        3 => 'Đã được chuyển đi',
        4 => 'Đã giao thành công',
        5 => 'Đã huỷ'   
      ];      
    public function index(Request $request){     
        $s['status'] = $status = isset($request->status) ? $request->status : -1;
        $s['date_from'] = $date_from = isset($request->date_from) && $request->date_from !='' ? $request->date_from : date('d-m-Y');
        $s['date_to'] = $date_to = isset($request->date_to) && $request->date_to !='' ? $request->date_to : date('d-m-Y');
        $s['name'] = $name = isset($request->name) && trim($request->name) != '' ? trim($request->name) : '';       

        $query = Orders::whereRaw('1');
        if( $status > -1){
            $query->where('status', $status);
        }
        if( $date_from ){
            $dateFromFormat = date('Y-m-d', strtotime($date_from));
            $query->whereRaw("DATE(created_at) >= '".$dateFromFormat."'");
        }
        if( $date_to ){
            $dateToFormat = date('Y-m-d', strtotime($date_to));
            $query->whereRaw("DATE(created_at) <= '".$dateToFormat."'");
        }
        if( $name != '' ){            
            $query->whereRaw(" ( email LIKE '%".$name."%' ) OR ( fullname LIKE '%".$name."%' )");
        }
        $orders = $query->orderBy('orders.id', 'DESC')->paginate(20);
        $list_status = $this->list_status;
       
        return view('backend.order.index', compact('orders', 'list_status','s'));
    }

    public function download()
    {
        $s['status'] = $status = isset($request->status) ? $request->status : -1;
        $s['date_from'] = $date_from = isset($request->date_from) && $request->date_from !='' ? $request->date_from : date('d-m-Y');
        $s['date_to'] = $date_to = isset($request->date_to) && $request->date_to !='' ? $request->date_to : date('d-m-Y');
        $s['name'] = $name = isset($request->name) && trim($request->name) != '' ? trim($request->name) : '';       

        $query = Orders::whereRaw('1');
        if( $status > -1){
            $query->where('status', $status);
        }
        if( $date_from ){
            $dateFromFormat = date('Y-m-d', strtotime($date_from));
            $query->whereRaw("DATE(created_at) >= '".$dateFromFormat."'");
        }
        if( $date_to ){
            $dateToFormat = date('Y-m-d', strtotime($date_to));
            $query->whereRaw("DATE(created_at) <= '".$dateToFormat."'");
        }
        if( $name != '' ){            
            $query->whereRaw(" ( email LIKE '%".$name."%' ) OR ( fullname LIKE '%".$name."%' )");
        }
        $orders = $query->orderBy('orders.id', 'DESC')->get();
        $contents = [];        
        $i = 0;
        ob_end_clean();
        ob_start();
        foreach ($orders as $data) {
            $sp = "";
            foreach($data->order_detail as $detail){
                $sp .= $detail->product->name."\n\r";
            }
            $i++;
            $contents[] = [
                'STT' => $i,
                'Khách hàng' => $data->fullname."-".$data->phone,
                'Ngày đặt' => date('d-m-Y H:i', strtotime($data->created_at)),
                'Sản phẩm' => $sp,
                'Tổng tiền' => number_format($data->total_payment)
            ];
        }       

        Excel::create('orders_' . date('YmdHi'), function ($excel) use ($contents) {
            // Set sheets
            $excel->sheet('Đơn hàng', function ($sheet) use ($contents) {
                $sheet->fromArray($contents, null, 'A1', false, true);
            });
        })->download('csv');

    }
    public function orderDetail(Request $request, $order_id)
    {
        $order = Orders::find($order_id);
        $order_detail = OrderDetail::where('order_id', $order_id)->get();
        $list_status = $this->list_status;
        $s['status'] = $request->status;
        $s['name'] = $request->name;
        $s['date_from'] = $request->date_from;
        $s['date_to'] = $request->date_to;

        return view('backend.order.detail', compact('order', 'order_detail', 'list_status', 's'));
    }

    public function orderDetailDelete(Request $request)
    {
        $order_id = $request->order_id;
        $order_detail_id = $request->order_detail_id;

        $order = Orders::find($order_id);
        $order_detail = OrderDetail::find($order_detail_id);

        $order->tien_thanh_toan -= $order_detail->tong_tien;
        $order->tong_tien       -= $order_detail->tong_tien;
        $order->save();

        OrderDetail::destroy($order_detail_id);
        return 'success';
    }

    public function update(Request $request){
        $status_id   = $request->status_id;
        $order_id    = $request->order_id;
        $shipping_fee    = $request->shipping_fee ? $request->shipping_fee : 0;
        $customer_id = $request->customer_id;

        Orders::where('id', $order_id)->update([
            'status' => $status_id, 
            'shipping_fee' => $shipping_fee
        ]);
        //get customer to send mail
        $customer = Customer::find($customer_id);
        $order = Orders::find($order_id);
       
        switch ($status_id) {           
            case "4":
                $orderDetail = OrderDetail::where('order_id', $order_id)->get();
                foreach($orderDetail as $detail){
                    $product_id = $detail->product_id;                    
                    $so_luong = $detail->so_luong;
                    $modelProduct = Product::find($product_id);
                    $inventory =  $modelProduct->inventory - $so_luong;
                    $inventory  = $inventory > 0 ? $inventory : 0;
                    $modelProduct->update(['inventory' => $inventory]);
                }   
                if($customer_id > 0){
                    // check thứ hạng thành viên
                    $totalDoanhThu =  Orders::where(['customer_id' => $customer_id, 'status' => 4])->whereRaw("YEAR(created_at)=".date('Y'))->sum('total_payment');

                    $settingArr = Settings::whereRaw('1')->lists('value', 'name');
                    $adminMailArr = explode(',', $settingArr['email_cc']);
                    $email = $customer->email;
                    if($email != ''){
                        $emailArr = array_merge([$email], $adminMailArr);
                    }else{
                        $emailArr = $adminMailArr;
                    }
                
                    $date_apply = date("Y-m-d", strtotime("+1 day"));
                    if( $totalDoanhThu >= 20000000){
                        $customer->cap_bac = 3;
                        $customer->date_apply = $date_apply;
                        $customer->save();
                        Mail::send('frontend.cart.mail_member',
                        [                    
                            'cus'             => $customer,
                            'ck'    => 4,
                            'hang'    => "Platinum",
                            'total' => $totalDoanhThu,
                            'date_apply' => $date_apply
                        ],
                        function($message) use ($emailArr) {
                            $message->subject('Cập nhật thứ hạng thành viên');
                            $message->to($emailArr);
                            $message->from('muanhanhgiatot.vn@gmail.com', 'muanhanhgiatot.vn');
                            $message->sender('muanhanhgiatot.vn@gmail.com', 'muanhanhgiatot.vn');
                        });
                    }elseif( $totalDoanhThu >= 15000000){
                        $customer->cap_bac = 2;
                        $customer->date_apply = $date_apply;
                        $customer->save();
                        Mail::send('frontend.cart.mail_member',
                        [                    
                            'cus'             => $customer,
                            'ck'    => 3,
                            'hang'    => "Vàng",
                            'total' => $totalDoanhThu,
                            'date_apply' => $date_apply
                        ],
                        function($message) use ($emailArr) {
                            $message->subject('Cập nhật thứ hạng thành viên');
                            $message->to($emailArr);
                            $message->from('muanhanhgiatot.vn@gmail.com', 'muanhanhgiatot.vn');
                            $message->sender('muanhanhgiatot.vn@gmail.com', 'muanhanhgiatot.vn');
                        });

                    }elseif( $totalDoanhThu >= 10000000){
                        $customer->cap_bac = 1;
                        $customer->date_apply = $date_apply;
                        $customer->save();
                        Mail::send('frontend.cart.mail_member',
                        [                    
                            'cus'             => $customer,
                            'ck'    => 2,
                            'hang'    => "Bạc",
                            'total' => $totalDoanhThu,
                            'date_apply' => $date_apply
                        ],
                        function($message) use ($emailArr) {
                            $message->subject('Cập nhật thứ hạng thành viên');
                            $message->to($emailArr);
                            $message->from('muanhanhgiatot.vn@gmail.com', 'muanhanhgiatot.vn');
                            $message->sender('muanhanhgiatot.vn@gmail.com', 'muanhanhgiatot.vn');
                        });
                    }
                }

                break;           
            
            default:

                break;
        }
      
        return 'success';
    }
}
