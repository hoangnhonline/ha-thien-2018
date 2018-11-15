@extends('frontend.layout')

@section('header')
    @include('frontend.partials.header')
    
  @endsection

@include('frontend.partials.meta')
@section('content')
<div class="columns-container">
    <!-- breadcrumb -->
    <div class="breadcrumb clearfix mt10 mb05 pb05">
        <div class="container">
          <a class="home" href="{{ route('home') }}" title="Trang chủ">Trang chủ</a>
          <span class="navigation-pipe">&nbsp;</span>
          <span class="navigation_page">{{ $detail->name }}</span>
        </div>
    </div>
    <!-- ./breadcrumb -->
    
    <!-- ldp-banner-full -->
    <div class="ldp-banner-full">
        <img class="lazy" data-original="{{ Helper::showImage($detail->large_banner) }}" alt="{{ $detail->name }}">        
    </div>
    <div class="text-center">
      <h1 class="btn-thelechuongtrinh">{{ $detail->name }}</h1>
    </div>   
    <!-- ./ldp-banner-full -->
    <div class="container">           
        @if($detail->the_le != "")
        <div class="events-lp-default">
          <div class="box-coupon-wrapper">
            <div class="row coupon-1col">
              <div class="box-coupon col-sm-6 col-md-4 col-lg-3 sach10">
                <div class="wrapper">
                  <?php 
                  echo $detail->the_le;
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>       
        @endif
        <!-- row -->
        <div class="row">
            <!-- Center colunm-->
            <div class="center_column col-xs-12">

                <!-- view-product-list-->
                <div id="category-product-event" class="view-product-list" style="margin-bottom:20px">
                    @if($dataList->count() > 0)
                    <!-- PRODUCT LIST -->
                    <ul class="row product-list">
                        @foreach($dataList as $product)
                        <li class="col-xs-6 col-sm-4 col-md-3">
                            <div class="product-container">
                                <div class="left-block">
                                    @if( $product['is_sale'] == 1)
                                    <span class="discount">-{{
                                        100-round($product['price_sale']*100/$product['price'])
                                    }}%</span>
                                    @endif
                                    <a href="{{ route('product', $product['slug']) }}">
                                        <img class="img-responsive lazy" alt="{{ $detail->name }}" data-original="{{ Helper::showImage($product->image_url) }}" />
                                    </a>
                                    <!--<figure class="mask-info">
                                        <span>Màn hình: Retina HD, 5.5 inches</span><span>HĐH: iOS 9</span><span>CPU: A9 64 bit, RAM 2GB</span><span>Camera: 12.0MP, 1 SIM</span><span>Dung lượng pin: 2750 mAh</span>
                                        <div class="btn-action">
                                          <a class="btnorder" href="#">Đặt hàng</a>
                                          <a class="viewdetail" href="#">Chi tiết</a>
                                        </div>
                                    </figure>-->
                                </div>
                                <div class="right-block">
                                    <h2 class="product-name"><a title="{{ $product['name'] }}" href="{{ route('product', $product['slug']) }}">{{ $product['name'] }}</a></h2>
                                    <div class="content_price">
                                        <span class="price product-price">
                                            @if($product['price'] > 0)
                                            {{ $product['is_sale'] == 1 ? number_format($product['price_sale']) : number_format($product['price']) }}
                                            @else
                                            Liên hệ
                                            @endif                                            
                                        </span>
                                        @if( $product['is_sale'] == 1)
                                        <span class="price old-price">{{ number_format($product['price']) }}</span>
                                        @endif
                                    </div>
                                    @if($product['price'] > 0)
                                    <a class="add_to_cart_button" href="{{ route('product', $product['slug']) }}">Mua</a>
                                    @endif
                                </div>
                            </div>
                            
                        </li>
                        @endforeach
                       
                    </ul>
                    @endif
                    <!-- ./PRODUCT LIST -->
                </div>               
            </div>
            <!-- ./ Center colunm -->
        </div>
        <!-- ./row-->
    </div>
</div>
<!-- Modal -->
<div id="theleModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Thể lệ chương trình</h4>
      </div>
      <div class="modal-body">
        <?php 
        echo $detail->the_le;
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
      </div>
    </div>

  </div>
</div>
@endsection



@section('javascript_page')

<script type="text/javascript" src="{{ URL::asset('public/assets/lib/countdown/jquery.plugin.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/assets/lib/countdown/jquery.countdown.js') }}"></script>
@endsection