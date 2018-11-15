@extends('frontend.layout')

@section('header')
    @include('frontend.partials.header')
    
  @endsection

@include('frontend.partials.meta')
@section('content')
<div class="columns-container">

    <div id="columns" class="container">      
    
        <!-- breadcrumb -->
        <div class="breadcrumb clearfix">
          <a class="home" href="{{ route('home') }}" title="Trang chủ">Trang chủ</a>
          <span class="navigation-pipe">&nbsp;</span>
          <span class="navigation_page">Chương trình khuyến mãi</span>
        </div>
        <!-- ./breadcrumb -->  
        
        <!-- row -->
        <div class="row">
            <!-- Center colunm-->
            <div class="center_column col-xs-12">

                <!-- category-product-hot-->
                <div class="category-product-hot"> 
                
                   
                    @if( $dataList->count() > 0)
                    <div class="broadcast">
                      <h2 class="title-big"><i class="fa fa-tag" aria-hidden="true"></i> CHƯƠNG TRÌNH KHUYẾN MÃI</h2>
                      <ul class="broadcast-banner row">
                          @foreach( $dataList as $data )
                          <li class="col-sm-6 item-bnr">
                            <div class="thumb-box">
                              <a href="{{ route('detail-event', $data->slug) }}"><img class="lazy" data-original="{{ Helper::showImage($data->small_banner) }}" alt="{{ $data->name }}"></a>
                              <div class="countdown-lastest countdown" data-y="{{ date('Y', strtotime($data->to_date)) }}" data-m="{{ date('m', strtotime($data->to_date)) }}" data-d="{{ date('d', strtotime($data->to_date)) }}" data-h="{{ date('H', strtotime($data->to_date)) }}" data-i="{{ date('i', strtotime($data->to_date)) }}" data-s="{{ date('s', strtotime($data->to_date)) }}"></div>                             
                            </div>
                          </li>
                          @endforeach
                      </ul>
                    </div><!-- ./broadcast-->
                    @endif
                  
                </div>
                <!-- ./category-product-hot-->
            </div>
            <!-- ./ Center colunm -->
        </div>
        <!-- ./row-->
    </div>
</div>
@endsection



@section('javascript_page')

<script type="text/javascript" src="{{ URL::asset('public/assets/lib/countdown/jquery.plugin.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/assets/lib/countdown/jquery.countdown.js') }}"></script>
@endsection