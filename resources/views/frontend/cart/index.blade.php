@extends('frontend.layout')
@include('frontend.partials.meta')
@section('content')
<?php $total = 0; ?>
<div class="container">
    <div class="block-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}" title="Trang chủ">Trang chủ</a></li>           
            <li class="active">Giỏ hàng</li>
        </ol>
    </div>
</div><!-- /.breadcrumb -->
<div id="main" class="main-content clearfix page-checkout">
    <div class="container">      
      <div class="content-sidebar-2">
        <div class="content">
          <form id="checkout-step-1" class="form form-blocking" action="#" method="post">
            <div class="box box-no-padding">
              <div class="box_header">
                              <h2 class="box_title">Thông tin sản phẩm</h2>
                          </div><!-- /.block-progress-steps -->
                          <div class="box_body">
                            <table class="table table-listing table-checkout">
                              <thead>
                                <tr>
                                    <th class="items0count" colspan="2">Sản phẩm trong giỏ hàng: <span class="badge">{{ Session::get('products') ? array_sum(Session::get('products')) : 0 }}</span></th>
                                    <th width="16%">Đơn giá</th>
                                    <th width="16%">Số lượng</th>
                                    <!-- <th width="16%">Giảm giá</th> -->
                                    <th width="16%">Thành tiền</th>
                                    <th class="action" width="1%">&nbsp;</th>
                                </tr>
                              </thead>
                              <tbody>   
                                @if(!empty(Session::get('products')))
                               <?php $total = 0; ?>
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
                                <tr>
                                  <td class="image" width="20%">
                                      <img alt="{!! $product->name !!}" src="{{ $product->image_url ? Helper::showImage($product->image_url) : URL::asset('public/assets/images/no-img.png') }}">
                                  </td>
                                  <td class="name">
                                      <a href="{{ route('product', [ $product->slug ]) }}" class="prod-tit" target="_blank" title="{!! $product->name !!}">{!! $product->name !!}</a>
                                      <div style="color: #999;">Mã SP : {!! $product->code !!}</div>
                                  </td>
                                  <td class="unit">                                    
                                    <p class="p-price"><b>{!! number_format( $price ) !!}đ</b></p>      
                                  </td>
                                  <td class="quantity">                                         
                                        <select data-id="{{ $product->id }}" size="1" class="quantity_modifier quantity-modifier quantity-modifier-select change_quantity">
                                          @for($i = 1; $i <= 20 ; $i ++) 
                                          <option value="{{ $i }}" @if( $getlistProduct[$product->id] == $i ) selected @endif>{{ $i }}</option>                 
                                          @endfor      
                                        </select>
                                    </td>
                                    <!-- <td class="discount"> đ</td> -->
                                    <td id="" class="total"><p class="p-price"><b>{{ number_format($total_per_product) }}đ</b></p></td>
                                    <td class="action"><a class="btn btn-xs p-del del_item" href="javascript:;" data-id="{{ $product->id }}"><i class="fa fa-trash"></i></a></td>
                                  </tr>
                                                   
                                  </tbody>

              
                                @endforeach

                                @endif  

                                @endif
                            </table>
                          </div><!-- /.block-progress-steps -->
                          <div class="box_footer">
                              <a href="{{ route('home') }}" title="Tiếp tục mua hàng" class="btn btn-main btn-default"><i class="fa fa-angle-left"></i> Tiếp tục mua hàng</a>                              
                              @if(!empty(Session::get('products')))
                              <a href="{{ route('address-info') }}" style="color: #FFF;text-transform: uppercase;" title="Tiến hành đặt hàng" class="btn btn-primary pull-right">Đặt hàng</a>
                              @endif
                          </div>
            </div><!-- /.block-progress-steps -->
          </form>
        </div>
        <div class="sidebar sidebar-checkout sidebar-checkout-1">
            <div class="box">
                <div class="box_header">
                    <h2 class="box_title">THÔNG TIN CHUNG</h2>
                </div>
                <div class="box_body">
                    <ul class="order-summary">
                        <li>
                            <span class="k">Tổng sản phẩm</span>
                            <span id="order-quantity" class="v">{{ Session::get('products') ? array_sum(Session::get('products')) : 0 }}</span>
                        </li>
                        <li>
                            <span class="k">Tổng tạm tính</span>
                            <span id="order-subtotal" class="v">{{ number_format($total) }} đ</span>
                        </li>
                        <li>
                            <span class="k">Phí giao hàng (<a target="_blank" href="/chinh-sach-giao-hang.html" title="Chi tiết chính sách giao hàng">?</a>)</span>
                            <span class="v"></span>
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
                            <span id="order-total" class="v">{{ number_format($total-$totalCk) }} đ</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
      </div><!-- /.content-sidebar-2 -->
    </div>
</div>
@stop
@section('js')
   <script type="text/javascript">
   $(document).ready(function(){
    $.ajax({
          url : $('#rating-route').val(),
          type : 'POST',
          dataType : 'html',
          data : $('#rating-form').serialize(),
          success : function(data){
              $('#rating-summary').html(data);
              var $input = $('input.rating');
              if ($input.length) {
                  $input.removeClass('rating-loading').addClass('rating-loading').rating();
              }
          }
      });

        $(document).ready(function($){  
          $('a.btn-order').click(function() {
                var product_id = $(this).data('id');
                addToCart(product_id);
                
              });
        });
        $('#btnDatHang').click(function(){
          $(this).html('<i class="fa fa-spin fa-spinner"><i>').attr('disabled', 'disabled');
          location.href=$(this).data('href');
        });
        $(document).on('change', '.change_quantity', function() {
            var quantity = $(this).val();
            var id = $(this).data('id');
            updateQuantity(id, quantity, 'ajax');
        });        
        
  }); 
function addToCart(product_id) {
  $.ajax({
    url: $('#route-add-to-cart').val(),
    method: "GET",
    data : {
      id: product_id
    },
    success : function(data){
       window.location.reload();
    }
  });
} 
function calTotalProduct() {
    var total = 0;
    $('.change_quantity ').each(function() {
        total += parseInt($(this).val());
    });
    $('.order_total_quantity').html(total);
}

function updateQuantity(id, quantity, type) {
    $.ajax({
        url: $('#route-update-product').val(),
        method: "POST",
        data: {
            id: id,
            quantity: quantity
        },
        success: function(data) {
            
            location.reload();            
        }
    });
}
  </script>
@endsection








