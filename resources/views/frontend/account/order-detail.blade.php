@extends('frontend.layout')  
@include('frontend.partials.meta')
@section('header')
    @include('frontend.partials.header')
    
  @endsection
@section('content')
<div id="main" class="main-content clearfix" style="margin-top:15px;">
        <div class="container">
            <div class="sidebar-content-2">
                <div class="sidebar sidebar-profile">
                    <div class="box box-no-padding">
                        <div class="box_header">
                            <h2 class="box_title" style="text-transform:uppercase">Thông tin chung</h2>
                        </div>
                        <div class="box_body">
                            <div class="sidebar_widget widget widget-profile">
                                <div class="profile">                                   
                                    <div class="profile_info">
                                        <div class="profile_name"><i class="hd hd-user"></i> {{ Session::get('username') }}</div>       
                                    </div>
                                </div>
                            </div>
                            <div class="sidebar_widget widget">
                                <ul class="side-menu">
                                   <li {{ \Request::route()->getName() == "order-history" || \Request::route()->getName() == "order-detail" ? "class=active" : "" }}>
                                  <a href="{{ route('order-history') }}" title="Đơn hàng của tôi"> Đơn hàng của tôi</a>
                              </li>                         
                              <li>
                                  <a href="{{ route('logout') }}" title="Thoát tài khoản">Thoát tài khoản </a>
                              </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div><!-- /.header -->
                <div class="content" style="">
                    <div class="box">
                        <div class="box_header">
                            <h2 class="box_title" style="text-transform:uppercase">Đơn hàng #{{ $str_order_id }} - {{ $status[$order->status] }}</h2>
                            <input type="hidden" name="orderId" id="orderId" value="13313200">
                        </div><!-- /.box_header -->
                        <div style="padding : 15px 10px">
                             <div class="account-order-detail">
                    
                      <p class="date mt10 mb20">Ngày đặt hàng:  {{ $ngay_dat_hang }}</p>
                      
                      <div class="address-1">
                        <h4 class="mb20"> Địa chỉ người nhận </h4>
                        <p style="font-weight:bold">{{ $customer->full_name }}</p>
                        <p>{{ $customer->address }}, 
                        @if(isset($customer->xa->name))
                          {{$customer->xa->name}}
                        @endif, 
                        @if(isset($customer->huyen->name))
                          {{$customer->huyen->name}},
                        @endif
                        @if(isset($customer->tinh->name))
                          {{$customer->tinh->name}}
                        @endif</p>
                        <p>ĐT: {{ $customer->phone }}</p>
                      </div>
                      
                      <div class="row mb20 mt20">
                        <div class="col-sm-7">
                          <div class="payment-1">
                            <h4 class="mb20">Phương thức vận chuyển</h4>
                            <p>Vận chuyển Tiết Kiệm (dự kiến giao hàng vào {{ $order->ngay_giao_du_kien }})</p>
                            @if($order->phi_giao_hang > 0)
                            <p>Phí vận chuyển : {{ number_format($order->phi_giao_hang)}}&nbsp;đ</p>
                            @else
                            Miễn phí vận chuyển
                            @endif
                          </div>
  
                        </div>
                        <div class="col-sm-5">
                          <div class="payment-2 has-padding">
                            <h4 class="mb20">Phương thức thanh toán</h4>
                            @if($order->method_id == 1)
                            <p>Giao hàng và thu tiền tại nhà </p>                            
                            @else
                            <p>Chuyển khoản ngân hàng</p>                           
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <h4 class="mb10">Sản phẩm mua</h4>
                    
                    <div class="table-responsive">
                      <table class="table table-bordered dashboard-order">
                        <thead>
                          <tr class="default">
                            <th class="text-nowrap"> <span class="hidden-xs hidden-sm hidden-md">Tên sản phẩm</span> <span class="hidden-lg">Sản phẩm</span> </th>                           
                            <th class="text-nowrap">Giá</th>
                            <th class="text-nowrap">Số lượng</th>                          
                            <th class="text-nowrap">Tổng cộng</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($orderDetail as $order) 
                                                 
                          <tr>
                        <td>
                        @if($order->product)
                        {{ $order->product->name }}
                        @endif
                        </td>
                           <td>
                             {{ number_format($order->price) }}
                           </td>
                          <td>
                            {{ number_format($order->amount) }}
                          </td>
                           
                            <td><strong class="hidden-lg hidden-md">Tổng cộng: </strong>{{ number_format($rowOrder->total) }}&nbsp;₫</td>
                          </tr>
                          @endforeach                         
                        </tbody>
                        <tfoot>
                                                
                          <tr>
                            <td colspan="3" class="text-right"><strong>Chi phí vận chuyển</strong></td>
                            <td><strong>{{ $order->phi_giao_hang > 0 ? number_format($order->phi_giao_hang)."&nbsp;₫" : "Miễn phí" }}</strong></td>
                          </tr>
                          @if($order->phi_cod > 0)
                          <tr>
                            <td colspan="3" class="text-right"><strong>Phí Thu Hộ</strong></td>
                            <td><strong>{{ $order->phi_cod > 0 ? number_format($order->phi_cod)."&nbsp;₫" : "Miễn phí" }}</strong></td>
                          </tr>
                          @endif
                          <tr>
                            <td colspan="3" class="text-right"><strong>Tổng cộng</strong></td>
                            <td><strong>{{ number_format($order->tong_tien)}}&nbsp;₫</strong></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>                    
                    <a href="{{ route('order-history')}}" class="btn btn-info btn-back"><i class="fa fa-caret-left"></i> Quay về đơn hàng của tôi</a>
                    @if($order->status == 0)
                    <button id="btnHuy" class="btn btn-danger" style="float:right"><i class="fa fa-times"></i> Hủy đơn hàng</button>
                    @endif
                        </div>
                        
                    </div>
                </div><!-- /.header -->
            </div>
        </div>
    </div>

<div class="clearfix"></div>
@endsection

@section('javascript_page')
   <script type="text/javascript">
    $(document).ready(function() {
      $('#btnHuy').click(function(){ 
        var obj = $(this);       
        if(confirm('Quý khách chắc chắn muốn hủy đơn hàng này?')){
          $.ajax({
            url : '{{ route('order-cancel') }}',
            type  : 'POST',
            data : {
              id : {{ $order->id }}
            },
            success : function(){
              swal({ title: '', text: 'Đã hủy đơn hàng #{{ $str_order_id }}', type: 'success' });
              obj.remove();
            }
          });
        }
      });
    });
  </script>
@endsection
