@extends('backend.layout')
@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Màu
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ route( 'color.index' ) }}">Màu</a></li>
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
      <a href="{{ route('color.create') }}" class="btn btn-info" style="margin-bottom:5px">Tạo mới</a>
      
      <div class="box">

        <div class="box-header with-border">
          <h3 class="box-title">Danh sách ( <span class="value">{{ $items->total() }} màu )</span></h3>
        </div>
        
        <!-- /.box-header -->
        <div class="box-body">

          <form action="{{ route('cap-nhat-thu-tu') }}" method="POST">
           @if( $items->count() > 0 ) 
          <button type="submit" class="btn btn-warning btn-sm">Cập nhật thứ tự</button>
          @endif
            {{ csrf_field() }}
            <input type="hidden" name="table" value="color">
            <table class="table table-bordered" id="table-list-data">
              <tr>
                <th style="width: 1%">#</th>    
                <th>Thứ tự</th>                        
                <th>Tên màu</th>
                <th>Mã màu</th>
                <th width="1%;white-space:nowrap">Thao tác</th>
              </tr>
              <tbody>
              @if( $items->count() > 0 )
                <?php $i = 0; ?>
                @foreach( $items as $item )
                  <?php $i ++; ?>
                <tr id="row-{{ $item->id }}">
                  <td><span class="order">{{ $i }}</span></td>      
                  <td width="100">
                    <input type="text" name="display_order[]" value="{{ $item->display_order}}" class="form-control" style="width:60px">
                    <input type="hidden" name="id[]" value="{{ $item->id }}">
                  </td>     
                  <td>                  
                    <a href="{{ route( 'color.edit', [ 'id' => $item->id ]) }}">{{ $item->name }}</a>
                  </td>
                  <td>
                    <a class="color_code" style="background-color:{{ $item->color_code }}">{{ $item->color_code }}</a>
                  </td>
                  <td style="white-space:nowrap">                  
                    <a href="{{ route( 'color.edit', [ 'id' => $item->id ]) }}" class="btn btn-warning btn-sm">Chỉnh sửa</a>                 
                    @if( $item->product->count() == 0 )
                    <a onclick="return callDelete('{{ $item->name }}','{{ route( 'color.destroy', [ 'id' => $item->id ]) }}');" class="btn btn-danger btn-sm">Xóa</a>
                    @endif                    
                  </td>
                </tr> 
                @endforeach
              @else
              <tr>
                <td colspan="3">Không có dữ liệu.</td>
              </tr>
              @endif

            </tbody>
            </table>
            @if( $items->count() > 0 )
            <button type="submit" class="btn btn-warning btn-sm">Cập nhật thứ tự</button>
            @endif
           </form>
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
  a.color_code {
    display: block;
    width: 50px;
    height: 50px;
    box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.29);
    border: 1px solid rgba(0, 0, 0, 0.2);
    text-align: center;
    line-height: 28px;
    font-size: 10px;
    color: #FFF;
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
</script>
@stop