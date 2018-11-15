@extends('frontend.layout')
@include('frontend.partials.meta')
@section('content')
<div class="container">
    <div class="block-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}" title="Trở về trang chủ">Trang chủ</a></li>
            <li><a href="{!! route('news-list', $cateDetail->slug) !!}">{!! $cateDetail->name !!}</a></li>
            <li class="active">{!! $detail->title !!}</li>
        </ol>
    </div>
</div><!-- /.breadcrumb -->

<main class="main-content clearfix">
    <div class="container">
        <div class="row" id="block-main-container">
            @include('frontend.cate.sidebar')
            <div class="col-md-9 category-content">
                <div class="box box-shadow">
                    <div class="wysiwyg wysiwyg-news">
                    <h1 class="page-title">{!! $detail->title !!}</h1>
                    <div class="block block-share" id="share-buttons">
                        <div class="share-item">
                            <div class="fb-like" data-href="{{ url()->current() }}" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
                        </div>
                        <div class="share-item" style="max-width: 65px;">
                            <div class="g-plus" data-action="share"></div>
                        </div>
                        <div class="share-item">
                            <a class="twitter-share-button" href="https://twitter.com/intent/tweet?text={!! $detail->title !!}"></a>
                        </div>
                        <div class="share-item">
                            <div class="addthis_inline_share_toolbox share-item"></div>
                        </div>
                    </div><!-- /block-share-->
                    <div class="block-content">
                        <div class="block block-aritcle block-editor-content">
                            {!! $detail->content !!}
                        </div>
                        
                        @if($tagSelected->count() > 0)
                        <div class="block-tags">
                            <ul>
                                <li class="tags-first">Tags:</li>
                                @foreach($tagSelected as $tag)                            
                                <li class="tags-link"><a href="{{ route('tag', $tag->slug) }}" title="{!! $tag->name !!}">{!! $tag->name !!}</a></li>
                                @endforeach
                            </ul>
                        </div><!-- /block-tags -->
                        @endif
                    </div>
                </div><!-- /block-ct-news -->
                @if( $otherArr->count() > 0 )
                <div class="block-page-common block-aritcle-related">
                    <div class="block block-title" style="padding-bottom: 5px;
    border-bottom: 1px solid #0088cc;
    color: #0088cc;
    margin-top: 20px;
    margin-bottom: 10px;">
                        <h2 class="title-main">BÀI VIẾT LIÊN QUAN</h2>
                    </div>
                    <div class="block-content">
                        <ul class="list">
                            @foreach( $otherArr as $articles)
                            <li style="margin-bottom: 5px;"> <a href="{{ route('news-detail', [$articles->cate->slug, $articles->slug, $articles->id]) }}" title="{!! $articles->title !!}" ><i class="fa fa-circle-o" style="font-size:9px" aria-hidden="true"></i> {!! $articles->title !!}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div><!-- /block-ct-news -->
                @endif
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