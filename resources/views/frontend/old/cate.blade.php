@extends('frontend.layout')
@include('frontend.partials.meta')
@section('content')
<div class="block_cate_top">
  <ul class="list">
    <li style="margin-left:0px"><a href="{{ route('old-device') }}" title="Máy cũ nổi bật">Máy cũ nổi bật</a></li>
    @foreach( $cateParentList as $loaiSp)
    <li @if($loaiDetail->id == $loaiSp->id) class="actused" @endif><a href="{{ route('old-cate', $loaiSp->slug ) }}" title="{!! $loaiSp->name !!} cũ">{!! $loaiSp->name !!} cũ</a></li>
    @endforeach   
  </ul>
</div><!-- /block_cate_top -->
@if($loaiDetail->id != 9)
<div class="block_filter">
<form method="GET" action="{{ url()->current() }}" id="filterForm">
    <ul class="list">
        @if($loaiDetail->id == 2)
        <li class="parent-brand"><a href="javascript:void(0);" class="old-cid {{ $cate_id == 6 ? "actived" : "" }}" data-value="6" title="Apple" class="old-cid">Apple</a></li>
        <li class="parent-brand"><a href="javascript:void(0);" class="old-cid {{ $cate_id == 8 ? "actived" : "" }}" data-value="8" title="Dell" class="old-cid">Dell</a></li>        
        @elseif($loaiDetail->id == 10)
        <li class="parent-brand"><a href="javascript:void(0);" class="old-cid {{ $cate_id == 57 ? "actived" : "" }}" data-value="57" title="Apple" class="old-cid">Apple</a></li>
        <li class="parent-brand"><a href="javascript:void(0);" class="old-cid {{ $cate_id == 56 ? "actived" : "" }}" data-value="56" title="Samsung" class="old-cid">Samsung</a></li>       
        @elseif($loaiDetail->id == 8)
        <li class="parent-brand"><a href="javascript:void(0);" class="old-cid {{ $cate_id == 37 ? "actived" : "" }}" data-value="37" title="Apple" class="old-cid">Apple</a></li>
        <li class="parent-brand"><a href="javascript:void(0);" class="old-cid {{ $cate_id == 38 ? "actived" : "" }}" data-value="38" title="Samsung" class="old-cid">Samsung</a></li>       
        @endif

        <li class="manu-other filter-brand">
            <a href="javascript:void(0);" onclick="return false;" title="Hãng Khác">Hãng <span>Khác</span> <i class="fa fa-caret-down"></i></a>
            <div class="manufacture">
                <ul>
                    <li><a  href="javascript:void(0)" class="rm-cate @if(!$cate_id) actived @endif" title="Tất cả hãng sản xuất">Tất cả hãng</a></li>
                    @foreach( $cateArrByLoai[$loaiDetail->id] as $cate)
                    <li><a href="javascript:void(0);" class="old-cid {{ $cate_id == $cate->id ? "actived" : "" }}" data-value="{!! $cate->id !!}" title="{!! $cate->name !!}">{!! $cate->name !!}</a></li> 
                    @endforeach
                    
                                      
                </ul>
            </div>
        </li>
        <li class="filter-price"><a href="javascript:void(0);" data-fm="0" data-to="1000000" class="old-price {{ $price_to == 1000000 ?  "actived" : "" }}"  title="">Dưới 1tr</a></li>
        <li class="filter-price"><a href="javascript:void(0);" data-fm="0" data-to="2000000" class="old-price {{ $price_to == 2000000 ?  "actived" : "" }}"  title="">Dưới 2tr</a></li>
        <li class="manu-other">
            <a href="javascript:void(0);" onclick="return false;" title="Hãng Khác">Giá <span>Khác</span> <i class="fa fa-caret-down"></i></a>
            <div class="manufacture">
                <ul>
                    <li><a class="{{ $price_from == 0 && $price_to == 500000000 ?  "actived" : "" }} rm-price" href="#" title="Tất cả mức giá">Tất cả mức giá</a></li>
                    <li><a href="javascript:void(0);" data-fm="0" data-to="1000000"  class="old-price {{ $price_to == 1000000 ?  "actived" : "" }}" title="Dưới 1 triệu">Dưới 1 triệu</a></li>
                    <li><a href="javascript:void(0);" data-fm="0" data-to="2000000" class="old-price {{ $price_to == 2000000 ?  "actived" : "" }}" title="Dưới 2 triệu">Dưới 2 triệu</a></li>
                    <li><a href="javascript:void(0);" data-fm="0" data-to="3000000" class="old-price {{ $price_to == 3000000 ?  "actived" : "" }}" title="Dưới 3 triệu">Dưới 3 triệu</a></li>
                    <li><a href="javascript:void(0);" data-fm="0" data-to="6000000" class="old-price {{ $price_to == 6000000 ?  "actived" : "" }}" title="Dưới 6 triệu">Dưới 6 triệu</a></li>
                    <li><a href="javascript:void(0);" data-fm="0" data-to="8000000" class="old-price {{ $price_to == 8000000 ?  "actived" : "" }}" title="Dưới 8 triệu">Dưới 8 triệu</a></li>
                    <li><a href="javascript:void(0);" data-fm="0" data-to="10000000" class="old-price {{ $price_to == 10000000 ?  "actived" : "" }}" title="Dưới 10 triệu">Dưới 10 triệu</a></li>
                    <li><a href="javascript:void(0);" data-fm="10000001" data-to="500000000" class="old-price {{ $price_from == 10000001 && $price_to == 500000000 ?  "actived" : "" }}" title="Trên 10 triệu">Trên 10 triệu</a></li>
                </ul>
            </div>
        </li>       
        <li class="manu-other pull-right">
            <a href="#" onclick="return false;" title="{{ $sort == 1 ? "Giá giảm dần" : "Giá tăng dần" }}">{{ $sort==1 ? "Giá giảm dần" : "Giá tăng dần" }} <i class="fa fa-caret-down"></i></a>
            <div class="manufacture manufunction manuprice">
                <ul>
                    <li><a href="javascript:void(0)" class="{{ $sort == 1 ? "actived" : "" }} old-sort" data-value="1" href="#" title="Giá giảm dần">Giá giảm dần</a></li>
                    <li><a href="javascript:void(0)" class="{{ $sort==2 ? "actived" : "" }} old-sort" data-value="2" title="Giá tăng dần">Giá tăng dần</a></li>
                </ul>
            </div>
        </li>
        <input type="hidden" name="c" value="{{ $cate_id }}" id="c">
        <input type="hidden" name="pf" value="{{ $price_from }}" id="pf">
        <input type="hidden" name="pt" value="{{ $price_to }}" id="pt">
        <input type="hidden" name="s" value="{{ $sort }}" id="s">
    </ul>
</form>
</div><!-- /block_filter -->
<div class="block_select_filter">
    @if($cate_id)
    <?php 
    $detailC = DB::table('cate')->where('id', $cate_id)->first();
    ?>
    <a href="javascript:;">{{ $detailC->name }} <i class="fa fa-times rm-cate"></i></a>
    @endif
    @if($price_from > 0 || ( $price_to != 500000000 && $price_from ==0))
    <?php 
    if($price_to == 1000000){
        $strPrice = "Dưới 1 triệu";
    }elseif($price_to == 2000000){
        $strPrice = "Dưới 2 triệu";
    }elseif($price_to == 3000000){
        $strPrice = "Dưới 3 triệu";
    }elseif($price_to == 6000000){
        $strPrice = "Dưới 6 triệu";
    }elseif($price_to == 8000000){
        $strPrice = "Dưới 8 triệu";
    }elseif($price_to == 10000000){
        $strPrice = "Dưới 10 triệu";
    }elseif($price_to == 500000000){
        $strPrice = "Trên 10 triệu";
    }
    ?>
    <a href="javascript:;">{{ $strPrice }} <i class="fa fa-times rm-price"></i></a>
    @endif
    @if($cate_id > 0 || ($price_from > 0 || $price_to != 500000000))
    <a class="reset" href="{{ url()->current() }}">Xóa tất cả <i class="fa fa-times"></i></a>
    @endif
</div><!-- /block_select_filter -->
@endif
<div class="block block_product block_product_old">
    <h3 class="block_title block_title_link">
      <span>{!! $loaiDetail->name !!} CŨ GIÁ RẺ</span> 
      <span class="num">{{ $productList->total() }}</span>
    </h3>
    <div class="block_content">
      <div class="list_de_old">
        @if($productList->count() > 0)
        <ul class="pro_de_old">
          @foreach( $productList as $product )
          <li class="col-sm-5ths col-xs-6 product_item">
            <div class="de_old_img">
              <a href="{{ route('product', [$product->slug, $product->id]) }}" title="{!! $product->name !!}">
                <img width="150" height="150" alt="{!! $product->name !!}" class="lazy" data-original="{{ $product->image_url ? Helper::showImageThumb($product->image_url) : URL::asset('admin/dist/img/no-image.jpg') }}">
              </a>
              <figure class="product_detail_de">
                  @if( $loaiDetail->is_hover == 1)            
                  @foreach($hoverInfo as $info)
                  <?php 
                  $tmpInfo = explode(",", $info->str_thuoctinh_id);              
                  ?>

                  <p>
                  {!! $info->text_hien_thi !!}: 
                  <?php                  
                  $spThuocTinhArr = json_decode( $product->thuoc_tinh, true);                 
                  
                  $countT = 0; $totalT = count($tmpInfo);
                  foreach( $tmpInfo as $tinfo){
                      $countT++;
                      if(isset($spThuocTinhArr[$tinfo])){
                          echo $spThuocTinhArr[$tinfo];
                          echo $countT < $totalT ? ", " : "";
                      }
                  }
                   ?>                   
                   </p>
                  @endforeach
                  
                @endif   
              </figure>
            </div>
            <div class="product_info">
              <h3 class="product_name">
                <a href="{{ route('product', [$product->slug, $product->id]) }}" title="">{!! $product->name !!}</a>
              </h3>
              <div class="product_price">
              <p class="price_title price_now">Giá : <span>{{ number_format($product->price) }}₫</span></p>
                @if($product->price_new)
                <p class="price_title price_old">Giá máy mới: <span>{{ number_format($product->price_new) }}₫</span></p>

                <p class="price_title price_compare">Rẻ hơn máy mới: <span>{{ number_format($product->price_new - $product->price) }}₫</span></p>
                @endif
            </div> 
            @if($product->is_sale)
            <span class="sale_off">GIẢM {{ ceil(($product->price-$product->price_sale)*100/$product->price) }}%</span>
            @endif          
            </div>
          </li><!-- /product_item -->
          @endforeach
        </ul>
        @else
        <p style="font-style:italic">Không tìm thấy sản phẩm nào.</p>
        @endif
      </div>
    </div>
  </div><!-- /block_product -->
  <style type="text/css">
      .block_filter .list > li > a{
        color:#288ad6;
      }
      .block_filter .list li .manufacture{
        width: 259px;
      }
      .block_filter .list > li > a.actived{
        color: #db0000
      }
      .block_product h3.block_title.block_title_link{
        border-top: none;
      }
  </style>
@stop
@section('js')
<script>
$(document).ready(function(){
    $('.old-cid').click(function(){
        var cid = $(this).data('value');
        $('#c').val(cid);
        $(this).parents('form').submit();
    });
    $('.old-sort').click(function(){
        var sort = $(this).data('value');
        $('#s').val(sort);
        $(this).parents('form').submit();
    });
    $('.old-price').click(function(){
        var obj = $(this);
        var pf = obj.data('fm');
        var pt = obj.data('to');
        $('#pf').val(pf);
        $('#pt').val(pt);
        obj.parents('form').submit();
    });
    $('.rm-cate').click(function(){
        $('#c').val('');
        $(this).parents('form').submit();
    });
    $('.rm-price').click(function(){
        $('#pf').val(0);
        $('#pt').val(500000000);
        $(this).parents('form').submit();
    });
}); 
</script>
@stop