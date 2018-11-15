@extends('frontend.layout')
@include('frontend.partials.meta')
@section('content')
<div class="block block-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{!! route('home') !!}">Trang chủ</a></li>
            <li class="active">{!! $detailPage->title !!}</li>
        </ul>
    </div>
</div><!-- /block-breadcrumb -->
<div class="block block-two-col container">
    <div class="row">
        
        <div class="col-sm-9 col-xs-12 block-col-main">
            <div class="block-page-about">
                <div class="block-page-common">
                    <div class="block block-title">
                        <h1 class="title-main">{!! $detailPage->title !!}</h1>
                    </div>
                </div>
                <div class="block-article">
                    <div class="block block-content block-editor-content">
                        {!! $detailPage->content !!}
                    </div>
                </div>
            </div>
        </div><!-- /block-col-left -->
        @include('frontend.cate.sidebar')
    </div>
</div><!-- /block_big-title -->
@endsection  
