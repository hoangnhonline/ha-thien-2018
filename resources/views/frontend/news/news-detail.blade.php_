@extends('frontend.layout')
@include('frontend.partials.meta')
@section('content')
<div class="block block-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{ route('home') }}" title="Trở về trang chủ">Trang chủ</a></li>
            <li><a href="{!! route('news-list', $cateDetail->slug) !!}">{!! $cateDetail->name !!}</a></li>
            <li class="active">{!! $detail->title !!}</li>
        </ul>
    </div>
</div><!-- /block-breadcrumb -->
<div class="block block-two-col container">
    <div class="row">
        <div class="col-sm-9 col-xs-12 block-col-main">
            <div class="block block-page-common block-dt-sales">
                <div class="block block-title">
                    <h1 class="title-main">{!! $detail->title !!}</h2>
                </div>
                <div class="block-content">
                    <div class="block block-aritcle block-editor-content">
                        {!! $detail->content !!}
                    </div>
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
                <div class="block block-title">
                    <h2 class="title-main">BÀI VIẾT LIÊN QUAN</h2>
                </div>
                <div class="block-content">
                    <ul class="list">
                        @foreach( $otherArr as $articles)
                        <li> <a href="{{ route('news-detail', [$articles->cate->slug, $articles->slug, $articles->id]) }}" title="{!! $articles->title !!}" >{!! $articles->title !!}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div><!-- /block-ct-news -->
            @endif
        </div><!-- /block-col-right -->
        @include('frontend.cate.sidebar')
    </div>
</div><!-- /block_big-title -->
@stop  
