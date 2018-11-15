@extends('frontend.layout')
@include('frontend.partials.meta')
@section('content')
<div class="container">
    <div class="block-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}" title="Trở về trang chủ">Trang chủ</a></li>
      		<li class="active">{!! $cateDetail->name !!}</li>
        </ol>
    </div>
</div><!-- /.breadcrumb -->
<main class="main-content clearfix">
    <div class="container">
        <div class="row" id="block-main-container">
            @include('frontend.cate.sidebar')
            <div class="col-md-9 category-content">
                <div class="box box-shadow" style="padding-top: 5px">    
                	<div class="wysiwyg wysiwyg-news">                
                    <h1 class="page-title">{!! $cateDetail->name !!}</h1>
					<div class="block-article-list">
						<ul class="article-list-news">
						    @if($articlesList)
      						@foreach($articlesList as $obj)
						    <li class="article-news-item">
						        <div class="article-news-item-head">
						            <a href="{{ route('news-detail', [ $obj->cate->slug, $obj->slug, $obj->id ]) }}">
						                <img src="{{ $obj->image_url ? Helper::showImageThumb($obj->image_url, 2) : URL::asset('public/assets/images/no-img.jpg') }}" alt="{!! $obj->title !!}">
						              </a>
						        </div>
						        <div class="article-news-item-description">
						            <a href="{{ route('news-detail', [ $obj->cate->slug, $obj->slug, $obj->id ]) }}" title="{!! $obj->title !!}">{!! $obj->title !!}</a>
						            <div class="nd-time">{!! date( 'd/m/Y H:i', strtotime($obj->created_at) ) !!}</div>
						            <p>{!! $obj->description !!}</p>
						        </div>
						    </li>
						    @endforeach
						    @endif						   
						</ul>
					</div><!-- /."block-article-list -->
					<div class="block-pagination pull-right">						
						{{ $articlesList->links() }}
					</div><!-- /.block-pagination -->
					<div class="clearfix"></div>
				</div>
				</div>
            </div><!-- /.col-md-9 -->
        </div>
    </div>
</main><!-- /.main -->
@stop
@section('js')
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-59b215c2a2658a8a"></script> 
<script src="https://apis.google.com/js/platform.js" async defer></script>
@stop