@extends('frontend.layout')
@include('frontend.partials.meta')
@section('content')
<?php $total = 0; ?>
<div class="container">
    <div class="block-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}" title="Trang chủ">Trang chủ</a></li>           
            <li class="active">Phương thức thanh toán</li>
        </ol>
    </div>
</div><!-- /.breadcrumb -->
<div id="main" class="main-content clearfix page-checkout">
  <div class="container"> 
  <div class="content-sidebar-2">
    <div class="content">     
        <div class="box">
          <div class="box_header">
              <h2 class="box_title">Phương thức thanh toán</h2>
          </div><!-- /.block-progress-steps -->
          <div class="box_body address-container">                        
            <div class="form form-blocking form-general check-out-step-2">
              <div class="address-edit editable-section editing">
                <div class="row form">
                  <div class="col-md-12">
                      <form action="{{ route('payment') }}" method="POST" class="form-billing" id="paymentForm">
                      {{ csrf_field() }}
                      <div class="form-group">
                        <label class="choose-another"><input type="radio" name="method_id" checked="checked" value="1" class="radio-cus"> Thanh toán tiền mặt khi nhận hàng - COD</label>
                      </div>
                      <div class="form-group">
                        <label class="choose-another"><input type="radio" name="method_id" value="2" class="radio-cus"> Chuyển khoản ngân hàng</label>
                      </div>
                      <div class="form-group">
                        <div class="content-bank">                
                        <div class="form-group">
                          <div class="thumb col-md-3">
                            <img src="{{ URL::asset('public/assets/images/VIB.jpg') }}" alt="VIB">
                          </div>
                          <div class="des col-md-9">
                            <p class="title">Ngân hàng thươnag mại cổ phần Á Châu - Chi nhánh Thủ Đức</p>
                            <p class="info">
                              It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English
                            </p>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group" style="margin-top: 10px">
                          <div class="thumb col-md-3">
                            <img src="{{ URL::asset('public/assets/images/TCB.jpg') }}" alt="ACB">
                          </div>
                          <div class="des col-md-9">
                            <p class="title">Ngân hàng thươnag mại cổ phần Á Châu - Chi nhánh Thủ Đức</p>
                            <p class="info">
                              It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English
                            </p>
                          </div>
                        </div>
                          <div class="clearfix" style="margin-bottom: 30px"></div>
                          <div class="form-group">
                            <textarea class="form-control" rows="5" name="notes" placeholder="Ghi chú cho đơn hàng"></textarea>
                          </div>
                        </div>
                      </div>                      
                      <div class="form-group text-right">
                        <a href="{!! route('address-info') !!}" title="Quay Lại" id="btnBack" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Quay Lại</a>
                        <button title="Quay Lại" class="btn btn-primary" id="btnPayment" >Đặt hàng <i class="fa fa-long-arrow-right"></i></button>
                      </div>
                    </form>
                  
                  </div><!-- /.col-md-12 -->
                  
         
                </div><!-- /.row -->
                <div class="row">
          
      </div><!-- /.row -->
              </div>
              
            </div>
            <div class="clearfix"></div>
          </div><!-- /.block-progress-steps -->                      
        </div><!-- /.block-progress-steps -->      
    </div>
    <div class="sidebar sidebar-checkout sidebar-checkout-2">

        <div class="box">
            <div class="box_header">
                <h2 class="box_title">Thông tin đơn hàng</h2>
                <div class="box_tools">
                </div>
            </div>
            <div class="box_body">
                <div class="order-items">
                    @if(!empty(Session::get('products')))
              
                  @if( $arrProductInfo->count() > 0)
                      <?php $i = 0; ?>
                    @foreach($arrProductInfo as $product)
                    <?php 
                    $i++;
                    if(Helper::isShared(Session::get('userId'), $product->id)){
                      $price = $product->price_share;
                    }else{
                      $price = $product->is_sale ? $product->price_sale : $product->price; 
                    } 

                    $total += $total_per_product = ($getlistProduct[$product->id]*$price);
                    
                    ?>
                    <div class="order-item">
                        <div class="name">
                            <span class="quantity">{{ $getlistProduct[$product->id] }} x</span>
                            <a target="_blank" href="#" title="{!! $product->name !!}">{!! $product->name !!}</a>
                        </div>
                        <div class="price">
                            {!! number_format($total_per_product) !!} đ
                        </div>
                    </div>                  
                  @endforeach

                  @endif  

                  @endif

                </div>
                <hr>
                <ul class="order-summary">
                    <li>
                        <span class="k">Tổng sản phẩm</span>
                        <span class="v">{{ Session::get('products') ? array_sum(Session::get('products')) : 0 }}</span>
                    </li>
                    <li>
                        <span class="k">Tổng tạm tính</span>
                        <span class="v _sub-total">{{ number_format($total) }} đ</span>
                    </li>                    
                    
                    <li class="sep"></li>
                    <?php $totalCk = 0; ?>                    
                      @if(Session::get('cap_bac') > 0)
                      <?php $cap_bac = Session::get('cap_bac'); 
                      if($cap_bac == 1){
                        $ck = 3;
                      }elseif($cap_bac == 2){
                        $ck = 4;
                      }elseif($cap_bac == 3){
                        $ck = 5;
                      }
                      $totalCk = $total*$ck/100;
                      ?>
                      <li>
                          <span class="k">Chiết khấu <strong style="color:#0088cc">({{ $ck }}%)</strong></span>
                          <span id="order-subtotal" class="v"><strong style="color:#0088cc">{{ number_format($total*$ck/100) }} đ</strong></span>
                      </li>
                      @endif
                    <li class="total">
                        <span class="k">Tổng cộng</span>
                        <span class="v _total" id="cart_info_total _total" data-order="total">{{ number_format($total-$totalCk) }} đ</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
  </div><!-- /.content-sidebar-2 -->
</div>
</div>
<style type="text/css">
.thumb{
  padding-top: 5px;
}
  .des .title {
    font-size: 13px;
    font-weight: bold;
    color: #333333;
    margin-bottom: 10px;
}

.choose-another {
    color: #0088cc !important;
    font-weight: bold !important;
    font-size: 14px !important;
}
.content-bank {
    background: rgb(234, 234, 234);
    padding: 20px;
}
</style>
@stop
@section('js')
   <script type="text/javascript">
   $(document).ready(function(){
    $('#btnPayment').click(function(){       
        $(this).html('<i class="fa fa-spin fa-spinner"></i>').attr('disabled', 'disabled');
        $('#btnBack').hide();
        $('#paymentForm').submit();      
    });
  });
  </script>
@stop








