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
                                   <li {{ \Request::route()->getName() == "account-info" ? "class=active" : "" }}>
                                    <a href="{{ route('account-info') }}" title="Thông tin tài khoản"> Thông tin tài khoản</a>
                                  </li>
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
                            <h2 class="box_title" style="text-transform:uppercase">Danh sách đơn hàng của tôi</h2>
                            <input type="hidden" name="orderId" id="orderId" value="13313200">
                        </div><!-- /.box_header -->
                        <div style="padding : 15px 10px">
                             <div class="dashboard-order">
                                <table class="table-responsive table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span class="hidden-xs hidden-sm hidden-md">Mã ĐH</span>
                                            <span class="hidden-lg">Code</span>
                                        </th>
                                        <th>Ngày mua</th>
                                        <th>Sản phẩm</th>
                                        <th style="text-align:right">Tổng tiền</th>
                                        <th style="text-align:center">
                                            <span class="hidden-xs hidden-sm hidden-md" >Trạng thái đơn hàng</span>
                                            <span class="hidden-lg">Trạng thái</span>
                                        </th>
                                        <!--                            <th></th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($orders->count() > 0)
                                    @foreach($orders as $order)
                                        <tr>
                                            <td style="text-align:center;"><a style="color:#ec1c24" href="{{ route('order-detail', $order->id)}}">{{ str_pad($order->id, 6, "0", STR_PAD_LEFT)}}</a></td>
                                            <td>{{ date('d/m/Y', strtotime($order->created_at)) }}</td>
                                            <td>                                        
                                            @foreach($order->order_detail as $detail)
                                            
                                            <p>{{ $detail->product->name }} [ <span style="color:#ec1c24">{{ $detail->amount }}</span> ]</p>
                                            @endforeach
                                            </td>
                                            <td style="text-align:right">{{ number_format($order->total_payment) }}&nbsp;₫</td>                                    
                                            <td style="text-align:center">
                                                <span class="order-status">
                                                    {{ $status[$order->status] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                    <tr><td colspan="5"><p style="margin: 10px;font-style: italic;">Không có dữ liệu</p></td></tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                </div><!-- /.header -->
            </div>
        </div>
    </div>

<style type="text/css">    
    .dashboard-order.have-margin {
        margin-bottom: 20px;
    }   
    table.table-responsive thead tr th {
        display: table-cell;
        padding: 8px;
        background: #f8f8f8;
        font-weight: 500;    
    }
    table.table-responsive tbody tr td{
        font-size: 14px !important;
    }
</style>
<div class="clearfix"></div>
@endsection


@section('javascript_page')
   <script type="text/javascript">
    $(document).ready(function() {

    });
  </script>
@endsection
