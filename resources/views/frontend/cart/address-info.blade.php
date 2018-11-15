@extends('frontend.layout')
@include('frontend.partials.meta')
@section('content')
<?php $total = 0; ?>
<div class="container">
    <div class="block-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}" title="Trang chủ">Trang chủ</a></li>           
            <li class="active">Thông tin thanh toán</li>
        </ol>
    </div>
</div><!-- /.breadcrumb -->
<div id="main" class="main-content clearfix page-checkout">
  <div class="container"> 
  <div class="content-sidebar-2">
    <div class="content">     
        <div class="box">
          <div class="box_header">
              <h2 class="box_title">Thông tin thanh toán</h2>
          </div><!-- /.block-progress-steps -->
          <div class="box_body address-container">                        
            <div class="form form-blocking form-general check-out-step-2">
              <div class="address-edit editable-section editing @if(Session::get('userId')) col-md-12 @else col-md-6 @endif">
                <div class="row form">
                  <div class="col-md-12">
                    <form id="addressForm" action="{{ route('store-address') }}" method="POST" class="form-billing">
                        {{ csrf_field() }}
                    <div class="form-group">
                      <span class="input-addon">Họ tên</span>
                      <input type="text" class="form-control req" id="fullname" name="fullname" placeholder="Họ và tên" value="{!! isset($addressInfo['fullname']) ? $addressInfo['fullname'] : "" !!}">
                    </div>
                    <div class="form-group">
                      <span class="input-addon">Email</span>
                      <input type="email" class="form-control req" id="email" name="email" placeholder="Email" value="{!! isset($addressInfo['email']) ? $addressInfo['email'] : "" !!}" @if(Session::get('userId')) readonly="readonly" @endif>
                    </div>
                    <div class="form-group">
                      <span class="input-addon">Điện thoại</span>
                      <input type="text" class="form-control req" id="phone" name="phone" placeholder="Số điện thoại" value="{!! isset($addressInfo['phone']) ? $addressInfo['phone'] : "" !!}">
                    </div>
                    <div class="form-group">
                      <span class="input-addon">Địa chỉ</span>
                      <textarea class="form-control req" name="address" id="address" placeholder="Địa chỉ">{{ isset($addressInfo['address']) ? $addressInfo['address'] : "" }}</textarea>
                    </div>                                
                    <div class="text-right" style="margin-top: 10px">
                      <button type="submit" id="btnSave"  class="btn btn-primary">
                        Tiếp tục <i class="fa fa-long-arrow-right"></i>
                      </button>
                    </div>
                  </form>
                  
                  </div><!-- /.col-md-6 -->
                  
         
                </div><!-- /.row -->
                <div class="row">
          
      </div><!-- /.row -->
              </div>
              @if(!Session::get('userId'))
              <div class="col-md-6">
                <p>Đăng nhập để nhận nhiều ưu đãi từ <strong>muanhanhgiatot.vn</strong></p>
                <div><a style="    height: 40px;   padding-top: 10px;    font-size: 15px !important;" data-url="javascript:;" title="Đăng nhập bằng Facebook" class="user-name-loginfb login-by-facebook-popup"><i class="fa fa-facebook-square"></i>Đăng nhập bằng Facebook</a></div>
              </div>
              @endif
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
  .error{
    border : 1px solid red !important;
  }
</style>
@stop
@section('js')
<script type="text/javascript">
  $(document).ready(function(){    
    $('#btnSave').click(function(){      
        var errReq = 0;
        var parent = $(this).parents('form');
        parent.find('.req').each(function(){
          var obj = $(this);
          if(obj.val() == ''){
            errReq++;
            obj.addClass('error');
            obj.prev().addClass('error');
          }else{
            obj.removeClass('error');
            obj.prev().removeClass('error');
          }
        });
        $('span').removeClass('error');
        if(errReq > 0){          
         $('html, body').animate({
              scrollTop: parent.offset().top
          }, 500);
          return false;
        }       

    });
    $('.req').blur(function(){    
        var obj = $(this);
        if(obj.val() != ''){
          obj.removeClass('error');
          obj.prev().removeClass('error');
        }else{
          obj.addClass('error');
          obj.prev().addClass('error');
        }
        $('span').removeClass('error');
      });
  });
</script>
@endsection








