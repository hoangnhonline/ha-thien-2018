@extends('frontend.layout')
@include('frontend.partials.meta')
@section('content')
<div class="block block_breadcrumb">
    <ol class="breadcrumb">
        <li><a href="{!! route('home') !!}">Trang chủ</a></li>            
        <li class="active">Bảo hành</li>
    </ol>
</div><!-- /block_breadcrumb -->
<div class="block_news row">
    <div class="col-md-9 col-sm-9 col-xs-12 block_cate_left">
        <div class="block_news_content">
            <h1 class="article-title">Bảo hành</h1>
            
            <div class="block" style="margin-top:30px" id="content-of-page">
            <form method="GET" action="{{ route('bao-hanh') }}">
                <div id="warranty_service">
                <div id="searchcontainer">                    
                    <input placeholder="Nhập mã IMEI hoặc serial number của sản phẩm tại đây" class="warranty_input_search_serial" id="input_imei" name="serial_no" value="{{ $serial_no }}">
                    <input type="submit" value="KIỂM TRA" class="warranty_botton_search" id="btnSearch">
                    <div class="space"></div>
                </div>
                @if($kq!=4)
                <div id="infowarranty">
                    @if($kq == 1)
                    <p style="color:#0084CB">Sản phẩm còn bảo hành đến : <strong style="font-size:17px">{{ date('d/m/Y', strtotime($end_date)) }}</strong> </p>
                    @elseif($kq == 2)
                    <p style="color:#db0000">Sản phẩm đã hết bảo hành. </p>
                    @elseif($kq == 3)
                    <p style="color:#db0000">Sản phẩm không tồn tại</p>
                    @endif
                </div>
                @endif
            </div>
            </form>
            </div><!-- /block -->            
        </div>
    </div><!-- /block_cate_left -->

    @include('frontend.news.sidebar')
</div><!-- /block_categories -->
<style type="text/css">
    .block_news_related ul li a{
        font-size: 12px;
        height: 30px;
        display: block;
        overflow-y: hidden;
    }
    #warranty_service {
    float: left;
    width: 100%;
    min-height: 460px;
    background: #fff;
    padding-top: 5px;
    margin: 10px 0;
}#infowarranty {
    text-align: center;
    padding-top: 20px;
    float: left;
    width: 80%;
    max-height: 350px;
    overflow-x: hidden;
    overflow-y: auto;
    font-size: 17px;
    margin-top: 5px;
    padding-left: 10px;
    padding-right: 10px;
}
#warranty_service #searchcontainer {
    float: left;
    width: 99%;
    background: #ebeef4;
    height: 50px;
    margin-left: 5px;
}
.warranty_botton_search {
    color: #fff;
    font-weight: 700;
    cursor: pointer;
    width: 100px;
    height: 30px;
    background: #db0000;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    float: left;
    border: 1px none #d0cab5;
    margin: 10px 10px 20px 20px;
}
.warranty_input_search_serial {
    width: 70%;
    height: 31px;
    line-height: 25px;
    -moz-border-radius: 0;
    -webkit-border-radius: 0;
    float: left;
    border: 1px solid #d0cab5;
    font-size: 12px;
    margin: 10px 0 20px 2%;
    padding: 5px;
}
</style>
@endsection  
@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnSearch').click(function(){
            if($('#input_imei').val()== ''){
                alert('Chưa nhập thông tin');
                return false;
            }
        });
    });
</script>
@stop