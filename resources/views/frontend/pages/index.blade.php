@extends('frontend.layout')
@include('frontend.partials.meta')
@section('content')
<div class="container">
    <div class="block-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{!! route('home') !!}">Trang chủ</a></li>
            <li class="active">{!! $detailPage->title !!}</li>
        </ol>
    </div>
</div><!-- /.breadcrumb -->
<main class="main-content clearfix">
	<div class="container">
		<div class="box box-shadow">
			<div class="sidebar-content sidebar-first">
				@include('frontend.pages.sidebar')
				<div class="content wysiwyg wysiwyg-news">
				    <h1 class="page-title">{!! $detailPage->title !!}</h1>
				    <div>
				    	{!! $detailPage->content !!}
				    </div>
				</div>
			</div>
		</div>
	</div>
</main><!-- /.main -->
@stop