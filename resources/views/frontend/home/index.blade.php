@extends('frontend.layout')
@include('frontend.partials.meta')  
@section('content')
<?php 
$bannerArr = DB::table('banner')->where(['object_id' => 1, 'object_type' => 3])->orderBy('display_order', 'asc')->get();   
?>
@if($bannerArr)
<div id="home-slider">
  <div class="container">
    <div class="header-top-right-wapper">
      <div class="homeslider">
        <ul id="contenhomeslider">
          <?php $i = 0; ?>
          @foreach($bannerArr as $banner)
          <?php $i++; ?>
          <li>
            @if($banner->ads_url !='')
            <a href="{{ $banner->ads_url }}" title="banner slide {{ $i }}">
            @endif
            <img src="{{ Helper::showImage($banner->image_url) }}" alt="banner slide {{ $i }}">
            @if($banner->ads_url !='')
            </a>
            @endif
          </li>
          @endforeach            
        </ul>
      </div>
    </div>
  </div>
</div><!-- /#home-slider -->
@endif

<div class="box-products products-hot">
    <div class="container">
      <div class="box-product-head">
        <span class="box-title" style="color: red"> sản phẩm HOT</span>
      </div>
      <div class="box-product-content">
              <ul class="products owl-carousel nav-center" data-dots="false" data-loop="true" data-nav = "true" data-margin = "10" data-responsive='{"0":{"items":2},"600":{"items":3},"1000":{"items":4}}'>
                    @foreach($hotProduct as $obj)                     
                    <li>
                      <div class="product product-kind-1">
                        <div class="product_image">
                          <a href="{{ route('product', [$obj->slug]) }}" title="{!! $obj->name !!}">
                            <img class="img-responsive" alt="product" src="{{ Helper::showImageThumb($obj->image_url) }}" />
                          </a>
                        </div>
                        <div class="product_header">
                          <h3 class="product_title">
                            <a href="{{ route('product', [$obj->slug]) }}" title="{!! $obj->name !!}">{!! $obj->name !!}</a>
                          </h3>
                        </div>
                        <div class="product_info">
                            <div class="product_price _product_price">
                              <span class="price">
                                <span class="price_value" itemprop="price">
                                  @if($obj->is_sale == 1 && $obj->price_sale > 0)
                                  {{ number_format($obj->price_sale) }}
                                  @else
                                      {{ number_format($obj->price) }}
                                  @endif
                                </span><span class="price_symbol">đ</span>
                                @if( $obj->is_sale == 1 && $obj->sale_percent > 0 ) 
                                <span class="price_discount">-{{ $obj->sale_percent }}%</span>
                                @endif
                              </span>
                            </div>
                            <div class="clearfix"></div>
                            @if($obj->is_fbshare == 1)
                            <div class="product_price">
                            <span class="price price_fb" style="font-size: 15px;color:red">
                                <span class="price_value" title="Giá sau khi share Facebook" itemprop="price"><i class="fa-facebook-official fa" style="color:#1f6284" title="Giá sau khi share Facebook"></i> {!! number_format($obj->price_share) !!}</span><span class="price_symbol">đ</span>
                            </span>      
                            </div>                          
                            @else
                            @if( $obj->is_sale == 1 && $obj->sale_percent > 0 )
                            <div class="product_price product_price-list-price _product_price_old " style="display: inline-block;">
                                <span class="price price-list-price">
                                <span class="price_value">{{ number_format($obj->price) }}</span><span class="price_symbol">đ</span>
                                </span>
                            </div>
                            @endif
                            @endif
                            <div class="product_views">
                              <i class="hd hd-user"></i> {{ number_format(Helper::slm($obj->id)) }}
                            </div>
                        </div>
                      </div>
                    </li> 
                    @endforeach
              </ul>
      </div>
    </div>
  </div><!-- /.cosmetic -->
  <?php 
  $j = 0;
  ?>
@foreach($cateParentHot as $parent)
@if($productArr[$parent->id]->count() > 0)
<?php $j++; ?>
  <div class="box-products cosmetic">
    <div class="container">
      <div class="box-product-head" style="border-top: 1px solid {{ $parent->color_code }};">
        <a href="{{ route('cate-parent', $parent->slug) }}"  title="{!! $parent->name !!}"><span class="box-title" style="background: {{ $parent->color_code }}; border-color: {{ $parent->color_code }}"><img src="{{ Helper::showImage($parent->icon_url) }}" style="display: inline-block; vertical-align: middle;margin-top: -4px;" alt="{!! $parent->name !!}"> {!! $parent->name !!}</span></a>
        <ul class="box-tabs nav-tab">            
            <li><a href="{{ route('cate-parent', $parent->slug) }}">XEM TẤT CẢ <i class="hd hd-long-arrow-right"></i></a></li>
        </ul>
      </div>
      <div class="box-product-content">
        
        <div class="box-product-list">
          <div class="tab-container">
            <div class="product-wrapper">              
              <div class="tab-panel active">
                <ul class="products nav-center">
                    @foreach($productArr[$parent->id] as $obj)   

                    <li class="col-md-3">
                      <div class="product product-kind-1">
                        <div class="product_image">
                          <a href="{{ route('product', [$obj->slug]) }}" title="{!! $obj->name !!}">
                            <img class="img-responsive" alt="product" src="{{ Helper::showImageThumb($obj->image_url) }}" />
                          </a>
                        </div>
                        <div class="product_header">
                          <h3 class="product_title">
                            <a href="{{ route('product', [$obj->slug]) }}" title="{!! $obj->name !!}">{!! $obj->name !!}</a>
                          </h3>
                        </div>
                        <div class="product_info">
                            <div class="product_price _product_price">
                              <span class="price">
                                <span class="price_value" itemprop="price">
                                  @if($obj->is_sale == 1 && $obj->price_sale > 0)
                                  {{ number_format($obj->price_sale) }}
                                  @else
                                      {{ number_format($obj->price) }}
                                  @endif
                                </span><span class="price_symbol">đ</span>
                                @if( $obj->is_sale == 1 && $obj->sale_percent > 0 ) 
                                <span class="price_discount">-{{ $obj->sale_percent }}%</span>
                                @endif
                              </span>
                            </div>
                            <div class="clearfix"></div>
                            @if($obj->is_fbshare == 1)
                            <div class="product_price">
                            <span class="price price_fb" style="font-size: 15px;color:red">
                                <span class="price_value" title="Giá sau khi share Facebook" itemprop="price"><i class="fa-facebook-official fa" style="color:#1f6284" title="Giá sau khi share Facebook"></i> {!! number_format($obj->price_share) !!}</span><span class="price_symbol">đ</span>
                            </span>      
                            </div>                          
                            @else
                            @if( $obj->is_sale == 1 && $obj->sale_percent > 0 )
                            <div class="product_price product_price-list-price _product_price_old " style="display: inline-block;">
                                <span class="price price-list-price">
                                <span class="price_value">{{ number_format($obj->price) }}</span><span class="price_symbol">đ</span>
                                </span>
                            </div>
                            @endif
                            @endif
                            <div class="product_views">
                              <i class="hd hd-user"></i> {{ number_format(Helper::slm($obj->id)) }}
                            </div>
                        </div>
                      </div>
                    </li> 
                    @endforeach
                </ul>
              </div>
              
            </div><!-- /.product-wrapper -->
          </div>
        </div>
      </div>
    </div>
  </div><!-- /.cosmetic -->
  @endif
@endforeach

  <div class="row-blog">
    <div class="container">
      <div class="blog-list">
        <h2 class="page-heading">
                  <span class="page-heading-title">Cẩm nang</span>
              </h2>
              <div class="blog-list-wapper">
                <ul class="owl-carousel" data-dots="false" data-loop="true" data-nav = "true" data-margin = "30" data-autoplayTimeout="1000" data-autoplayHoverPause = "true" data-responsive='{"0":{"items":1},"600":{"items":3},"1000":{"items":4}}'>
                      @foreach($articlesHotList as $obj)
                      <li>
                          <div class="post-thumb image-hover2">
                              <a href="{{ route('news-detail', [$obj->cate->slug, $obj->slug, $obj->id]) }}" title="{!! $obj->title !!}"><img src="{{ Helper::showImageThumb($obj->image_url, 2) }}" alt="{!! $obj->title !!}"></a>
                          </div>
                          <div class="post-desc">
                              <h5 class="post-title">
                                  <a href="{{ route('news-detail', [$obj->cate->slug, $obj->slug, $obj->id]) }}" title="{!! $obj->title !!}">{!! $obj->title !!}</a>
                              </h5>
                              <div class="post-meta">
                                  <span class="date">{{ date('d/m/Y', strtotime($obj->created_at)) }}</span>                                  
                              </div>
                              <div class="readmore">
                                  <a href="{{ route('news-detail', [$obj->cate->slug, $obj->slug, $obj->id]) }}" title="{!! $obj->title !!}">Chi tiết</a>
                              </div>
                          </div>
                      </li>
                      @endforeach
                  </ul>
              </div>
      </div>
    </div>
  </div>

@stop