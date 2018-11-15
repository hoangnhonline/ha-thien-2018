@extends('backend.layout')
@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Kho máy mới 
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ route( 'product.kho' ) }}">Kho máy mới</a></li>
    <li class="active">Danh sách</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      @if(Session::has('message'))
      <p class="alert alert-info" >{{ Session::get('message') }}</p>
      @endif      
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Bộ lọc</h3>
        </div>
        <div class="panel-body">
          <form class="form-inline" id="searchForm" role="form" method="GET" action="{{ route('product.kho') }}">
            <div class="form-group">
             
              <select class="form-control" name="parent_id" id="parent_id">
                <option value="">--Danh mục cha--</option>
                @foreach( $cateParentList as $value )
                <option value="{{ $value->id }}" {{ $value->id == $arrSearch['parent_id'] ? "selected" : "" }}>{{ $value->name }}</option>
                @endforeach
              </select>
            </div>
              <div class="form-group">
              

              <select class="form-control" name="cate_id" id="cate_id">
                <option value="">--Danh mục con--</option>
                @foreach( $cateArr as $value )
                <option value="{{ $value->id }}" {{ $value->id == $arrSearch['cate_id'] ? "selected" : "" }}>{{ $value->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">              
              <input type="text" class="form-control" name="name" value="{{ $arrSearch['name'] }}" placeholder="Tên sản phẩm...">
            </div>           
            <div class="form-group">
              <label><input type="checkbox" name="is_hot" value="1" {{ $arrSearch['is_hot'] == 1 ? "checked" : "" }}> Nổi bật</label>              
            </div>
            <div class="form-group">
              <label><input type="checkbox" name="is_sale" value="1" {{ $arrSearch['is_sale'] == 1 ? "checked" : "" }}> SALE</label>              
            </div>
            <div class="form-group">
              <label><input type="checkbox" name="out_of_stock" value="1" {{ $arrSearch['out_of_stock'] == 1 ? "checked" : "" }}> Hết hàng</label>              
            </div>
               
            <button type="submit" style="margin-top:-5px" class="btn btn-primary btn-sm">Lọc</button>
          </form>         
        </div>
      </div>
      <div class="box">

        <div class="box-header with-border">
          <h3 class="box-title">Danh sách ( {{ $items->total() }} sản phẩm )</h3>
        </div>
        
        <!-- /.box-header -->
        <div class="box-body">
          <div style="text-align:center">
           {{ $items->appends( $arrSearch )->links() }}
          </div>  
          <form action="{{ route('cap-nhat-thu-tu') }}" method="POST">
           @if( $items->count() > 0 && $arrSearch['is_hot'] == 1 && $arrSearch['parent_id'] > 0 ) 
          <button type="submit" class="btn btn-warning btn-sm">Cập nhật thứ tự</button>
          @endif
            {{ csrf_field() }}
            <input type="hidden" name="table" value="product">
          <table class="table table-bordered" id="table-list-data">
            <tr>
              <th style="width: 1%">#</th>
              <th style="text-align:left">Tên sản phẩm</th>
              <th style="text-align:right">Giá</th>                              
              <th width="100px" style="text-align:center">Hết hàng</th>
              <th width="100px" style="text-align:right">Số lượng</th>
              <th width="1%;white-space:nowrap">Thao tác</th>
            </tr>
            <tbody>
            @if( $items->count() > 0 )
              <?php $i = 0; ?>
              @foreach( $items as $item )
                <?php $i ++; 

                ?>
              <tr id="row-{{ $item->id }}">
                <td><span class="order">{{ $i }}</span></td>
                <td>                  
                  <a style="color:#333;font-weight:bold" href="{{ route( 'product.edit', [ 'id' => $item->id ]) }}">{{ $item->name }} {{ $item->name_extend }}</a>
                 <p style="margin-top:10px">
                    
                  </p>                  
                </td>
                <td class="text-right">@if( $item->is_sale == 1)
                   <b style="color:red">                  
                    {{ number_format($item->price_sale) }}
                   </b>
                   <span style="text-decoration: line-through">
                    {{ number_format($item->price) }}  
                    </span>
                    @else
                    <b style="color:red">                  
                    {{ number_format($item->price) }}
                   </b>
                    @endif </td>
                <td style="text-align:center">
                  <input type="checkbox" data-id="{{ $item->id }}" data-col="out_of_stock" data-table="product" class="change-value" value="1" {{ $item->out_of_stock == 1  ? "checked" : "" }}>
                </td>
                <td style="text-align:right">{{ number_format($item->inventory) }}</td>
                <td style="white-space:nowrap; text-align:right">
                  <a href="{{ route( 'product.edit', [ 'id' => $item->id ]) }}" class="btn btn-warning btn-sm">Chỉnh sửa</a>
                </td>
              </tr> 
              @endforeach
            @else
            <tr>
              <td colspan="9">Không có dữ liệu.</td>
            </tr>
            @endif

          </tbody>
          </table>
          </form>
          <div style="text-align:center">
           {{ $items->appends( $arrSearch )->links() }}
          </div>  
        </div>        
      </div>
      <!-- /.box -->     
    </div>
    <!-- /.col -->  
  </div> 
</section>
<!-- /.content -->
</div>
<style type="text/css">
#searchForm div{
  margin-right: 7px;
}
</style>
@stop
@section('javascript_page')
<script type="text/javascript">
function callDelete(name, url){  
  swal({
    title: 'Bạn muốn xóa "' + name +'"?',
    text: "Dữ liệu sẽ không thể phục hồi.",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then(function() {
    location.href= url;
  })
  return flag;
}
$(document).ready(function(){
  $('.change-value').change(function(){
    var obj = $(this);
    var val = 0;
    if(obj.prop('checked') == true){
      var val = 1;
    }
    $.ajax({
      url : "{{ route('change-value') }}",
      type :'POST',
      data : {
        id : obj.data('id'),
        value : val,
        column : obj.data('col'),
        table : obj.data('table')
      },
      success : function(data){
        console.log(data);
      }
    });
  });
  $('input.submitForm').click(function(){
    var obj = $(this);
    if(obj.prop('checked') == true){
      obj.val(1);      
    }else{
      obj.val(0);
    } 
    obj.parent().parent().parent().submit(); 
  });
  
  $('#parent_id').change(function(){
    $('#cate_id').val('');
    $('#searchForm').submit();
  });
  $('#cate_id, #is_old').change(function(){
    $('#searchForm').submit();
  });
  $('#table-list-data tbody').sortable({
        placeholder: 'placeholder',
        handle: ".move",
        start: function (event, ui) {
                ui.item.toggleClass("highlight");
        },
        stop: function (event, ui) {
                ui.item.toggleClass("highlight");
        },          
        axis: "y",
        update: function() {
            var rows = $('#table-list-data tbody tr');
            var strOrder = '';
            var strTemp = '';
            for (var i=0; i<rows.length; i++) {
                strTemp = rows[i].id;
                strOrder += strTemp.replace('row-','') + ";";
            }     
            updateOrder("product", strOrder);
        }
    });
});
function updateOrder(table, strOrder){
  $.ajax({
      url: $('#route_update_order').val(),
      type: "POST",
      async: false,
      data: {          
          str_order : strOrder,
          table : table
      },
      success: function(data){
          var countRow = $('#table-list-data tbody tr span.order').length;
          for(var i = 0 ; i < countRow ; i ++ ){
              $('span.order').eq(i).html(i+1);
          }                        
      }
  });
}
</script>
@stop