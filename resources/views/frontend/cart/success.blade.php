@extends('frontend.layout')
@include('frontend.partials.meta')
@section('content')
<div class="container">
    <div class="block-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}" title="Trang chủ">Trang chủ</a></li>           
            <li class="active">Hoàn tất đơn hàng</li>
        </ol>
    </div>
</div><!-- /.breadcrumb -->
<div id="main" class="main-content clearfix">
  <div class="container">
    <div class="box box-thank">
      <div class="box-text-thank">
          <div class="text-thank">Đặt hàng thành công</div>
          <div class="icon-thank">
              <i class="fa fa-check-circle" aria-hidden="true"></i>
          </div>
           <p style="font-size: 18px"><b>Cảm ơn quý khách đã mua hàng !</b> Chúng tôi liên hệ xác nhận đến số điện thoại của quý khách và sẽ giao hàng đến cho quý khách trong thời gian từ 2-3 ngày sau khi xác nhận.<br /><br /></p>
          <!--<p class="box-text-thankp">&nbsp;
              Xin vui lòng tham khảo<br>
              <a href="#" target="_blank"> Thời gian nhận hàng
              dự kiến</a> <span>|</span><a href="#" target="_blank"> Chính sách đổi trả </a>
          </p>
          <p class="add-info-call">
              <span>1900 6760</span>
              7h - 19h, thứ 2 đến thứ 7<br>
              cs@hotdeal.vn
          </p>-->
          <p><b style="font-size: 18px">Chân thành cảm ơn quý khách.</b></p>
      </div>
      <div class="box-bt-dk" style="margin-top: 30px">
          <a class="bt-tt-mua btn-primary" href="{{ route('home') }}">Trở về trang chủ</a>            
      </div>
    </div><!-- /.box box-thank -->
  </div>
</div>
@stop
@section('js')
<script type="text/javascript">
  $(document).ready(function(){
    setTimeout(function(){
      location.href="{{ route('home') }}";
    }, 5000);
  });
</script>
@stop