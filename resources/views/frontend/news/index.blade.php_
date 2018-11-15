@extends('frontend.layout')  
@include('frontend.partials.meta')
@section('content')
<div class="block block-breadcrumb">
  <div class="container">
    <ul class="breadcrumb">
      <li><a href="{{ route('home') }}" title="Trở về trang chủ">Trang chủ</a></li>
      <li class="active">{!! $cateDetail->name !!}</li>
    </ul>
  </div>
</div><!-- /block-breadcrumb -->
<div class="block block-two-col container">
  <div class="row">
        <div class="col-sm-9 col-xs-12 block-col-main">
      <div class="block-page-common block-sales">
        <div class="block block-title">
          <h1 class="title-main">{!! $cateDetail->name !!}</h1>
        </div>
        <div class="block-content">
          @if($articlesList)
          @foreach($articlesList as $obj)
          <div class="item">
            <div class="thumb">
              <a href="{{ route('news-detail', [ $obj->cate->slug, $obj->slug, $obj->id ]) }}">
                <img src="{{ $obj->image_url ? Helper::showImage($obj->image_url) : URL::asset('public/assets/images/no-img.jpg') }}" alt="{!! $obj->title !!}">
              </a>
            </div>
            <div class="des">
              <a href="{{ route('news-detail', [ $obj->cate->slug, $obj->slug, $obj->id ]) }}" title="{!! $obj->title !!}">{!! $obj->title !!}</a>
              <p class="date-post"><i class="fa fa-calendar"></i> {!! date( 'd/m/Y H:i', strtotime($obj->created_at) ) !!}</p>
              <p class="description">{!! $obj->description !!}</p>
            </div>
          </div><!-- /item -->
          @endforeach          
          @endif
        </div>
      </div><!-- /block-ct-news -->
      <nav class="block-pagination">
        {{ $articlesList->links() }}
      </nav><!-- /block-pagination -->
    </div><!-- /block-col-right -->
    @include('frontend.cate.sidebar')
  </div>
</div><!-- /block_big-title -->
@stop