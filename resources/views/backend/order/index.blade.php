@extends('backend.layout')
@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Đơn hàng
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ route( 'orders.index' ) }}">Đơn hàng</a></li>
    <li class="active">Danh sách</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      @if(Session::has('message'))
      <p class="alert alert-info" >{{ Session::get('message') }}</p>
      @endif
      <button id="btnExport" data-link="{{ route('orders.export') }}" class="btn btn-info btn-sm" style="margin-bottom:5px">Xuất Excel</button>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Bộ lọc</h3>
        </div>
        <div class="panel-body">
          <form class="form-inline" id="searchForm" role="form" method="GET" action="{{ route('orders.index') }}">            
            <div class="form-group">              
              <select class="form-control" name="status" id="status">
                <option value="-1"
                @if(-1 == $s['status'])
                  selected 
                  @endif   
                >--Tất cả trạng thái--</option>
                 @foreach($list_status as $index => $status)
                  <option value="{{$index}}" 
                  @if($index == $s['status'])
                  selected 
                  @endif                    
                    >{{$status}}</option>
                  @endforeach
              </select>
            </div>
             
            <div class="form-group">              
              <input type="text" class="form-control" name="name" placeholder="Email hoặc Tên khách hàng" value="{{ $s['name'] }}" style="width:250px">
            </div>  
            <div class="form-group">              
              <input type="text" class="form-control datepicker" placeholder="Từ ngày" name="date_from" value="{{ $s['date_from'] }}">
            </div> 
            <div class="form-group">              
              <input type="text" class="form-control datepicker" placeholder="Đến ngày" name="date_to" value="{{ $s['date_to'] }}">
            </div>                 
            <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
          </form>         
        </div>
      </div>
      <div class="box">

        <div class="box-header with-border">
          <h3 class="box-title">Danh sách ( {{ $orders->total() }} đơn hàng )</h3>
        </div>

        <!-- /.box-header -->
        <div class="box-body">
          <div style="text-align:center">
           {{ $orders->appends( $s )->links() }}
          </div>  
          <table class="table table-bordered" id="table-list-data">           
             <tr>
              <th style="width: 1%">No.</th>
              <th style="width: 1%;white-space:nowrap;width:200px"> Đơn hàng</th>
              <th style="text-align:center;width:150px">Ngày đặt hàng</th>
              <th style="text-align:left;width:200px">Giao hàng đến</th>           
              <th style="text-align:right;width:100px">Tổng hoá đơn</th>
              <th width="120px" style="white-space:nowrap">Trạng thái</th>
              <th width="1%" style="white-space:nowrap"> </th>
            </tr>
            <tbody>

              @if($orders->count() > 0)
              <?php $i = 0; ?>
                @foreach($orders as $order)
                <?php $i++; ?>
                <tr>
                <td style="text-align:center">{{ $i }}</td>                
                <td>
                <a href="" style="font-size:14px; font-weight:bold">
                <?php 

                ?>
                #{{ str_pad($order->id, 6,'0', STR_PAD_LEFT) }}</a> 
                <span style="color:#555"> bởi {{$order->fullname}}</span>
                <br>
                <a href="mailto:">{{ $order->email }}</a>
                <br>
                {{ $order->phone }}
                </td>
                <td style="text-align:center;width:150px;white-space:nowrap">{{ date('d-m-Y H:i ', strtotime($order->created_at))}}</td>
                <td>                
                @if( $order->is_other_address == 0 )
                <a href="http://maps.google.com/maps?&q={{ $order->address }}" target="_blank"> 
                {{ $order->fullname }}<br> {{ $order->address }} <br> {{ $order->phone }}
                <br>
                {{ $order->email }}
                </a>
                @else
                <a href="http://maps.google.com/maps?&q={{ $order->other_address }}" target="_blank"> 
                {{ $order->other_fullname }}<br> {{ $order->other_address }} <br> {{ $order->other_phone }}
                <br>
                {{ $order->other_email }}
                </a>
                @endif
                @if($order->notes)
                <br><p style="color:red;margin-top: 10px;"><u>Ghi chú: </u><i>{{ $order->notes}}</i></p>
                @endif
                </td>
                             
                <td style="text-align:right;width:100px"><strong style="font-size: 17px">{{ number_format($order->total_payment) }}</strong>
                  <br>
                  <div class="form-group form-inline">
                    <label>Phí ship</label>
                    <input type="text" name="" value="{{ number_format($order->shipping_fee) }}" class="phiship number" data-id="{{ $order->id }}" class="form-control" data-status="{{ $order->status }}" data-customer="{{ $order->customer_id }}" style="text-align: right;padding-right: 5px;margin-top: 10px;width: 80px">
                </div>
                </td>
                <td>
                  <select class="select-change-status form-control" order-id="{{$order->id}}" customer-id="{{$order->customer_id}}" >
                    @foreach($list_status as $index => $status)
                    <option value="{{$index}}"
                      @if($index == $order->status)
                        selected
                      @endif
                      >{{$status}}</option>
                    @endforeach
                  </select>
                </td>
                <td style="text-align:right">                 
                  <a href="{{ route('order.detail', $order->id)}}?status={{ $s['status'] }}&name={{ $s['name'] }}&date_from={{ $s['date_from'] }}&date_to={{ $s['date_to'] }}" class="btn btn-info btn-sm">Chi tiết</a> 
                </td>
                </tr>
                @endforeach
              @else
              <tr>
                <td colspan="5">Không có dữ liệu.</td>
              </tr>
              @endif
          </tbody>
          </table>
          <div style="text-align:center">
           {{ $orders->appends( $s )->links() }}
          </div> 
        </div>
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
</section>
<!-- /.content -->
</div>
@stop
@section('javascript_page')
<script type="text/javascript">

$(document).ready(function(){
  $('#status').change(function(){
    $('#searchForm').submit();

  });
  $('#btnExport').click(function(){
    $('#searchForm').attr('action', $(this).data('link'));
    $('#searchForm').submit();
  })
  $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' });
  $('.select-change-status').change(function(){
    var status_id = $(this).val();
    var order_id  = $(this).attr('order-id');
    var customer_id = $(this).attr('customer-id');
    update_status_orders(status_id, order_id, customer_id);
  });
  $('.phiship').change(function(){
    var status_id = $(this).data('status');
    var order_id  = $(this).data('id');
    var customer_id = $(this).data('customer');
    var shipping_fee = $(this).val();
    update_orders(status_id, order_id, customer_id, shipping_fee);
  });
  function update_orders(status_id, order_id, customer_id, shipping_fee){
    $.ajax({
      url: '{{route('orders.update')}}',
      type: "POST",
      data: {
        status_id : status_id,
        order_id : order_id,
        customer_id : customer_id,
        shipping_fee : shipping_fee
      },
      success: function (response) {
        location.reload()
      },
      error: function(response){

      }
    });
  }
  function update_status_orders(status_id, order_id, customer_id) {
    $.ajax({
      url: '{{route('orders.update')}}',
      type: "POST",
      data: {
        status_id : status_id,
        order_id : order_id,
        customer_id : customer_id
      },
      success: function (response) {
        location.reload()
      },
      error: function(response){

      }
    });
  }

});

</script>
@stop