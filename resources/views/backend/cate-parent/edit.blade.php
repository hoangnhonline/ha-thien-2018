@extends('backend.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Danh mục cha     
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="{{ route('cate-parent.index') }}">Danh mục cha</a></li>
      <li class="active">Chỉnh sửa</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <a class="btn btn-default" href="{{ route('cate-parent.index') }}" style="margin-bottom:5px">Quay lại</a>
    <a class="btn btn-primary btn-sm" href="{{ route('cate-parent', $detail->slug ) }}" target="_blank" style="margin-top:-6px"><i class="fa fa-eye" aria-hidden="true"></i> Xem</a>
    <div class="row">
      <!-- left column -->

      <div class="col-md-7">
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Chỉnh sửa</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form role="form" method="POST" action="{{ route('cate-parent.update') }}">
            {!! csrf_field() !!}
            <input type="hidden" name="id" value="{{ $detail->id }}">
            <div class="box-body">
              @if(Session::has('message'))
              <p class="alert alert-info" >{{ Session::get('message') }}</p>
              @endif
              @if (count($errors) > 0)
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif
              
               <!-- text input -->
              <div class="form-group">
                <label>Tên danh mục <span class="red-star">*</span></label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $detail->name }}">
              </div>
              <div class="form-group">
                <label>Slug <span class="red-star">*</span></label>
                <input type="text" class="form-control" readonly="readonly" name="slug" id="slug" value="{{ $detail->slug }}">
              </div>            
              <div class="form-group">
                <label>Mô tả</label>
                <textarea class="form-control" rows="4" name="description" id="description">{{ $detail->description }}</textarea>
              </div>     
              
              <div class="form-group" style="margin-top:10px;margin-bottom:10px">  
                  <label class="col-md-3 row">Thumbnail ( 230x230 px)</label>    
                  <div class="col-md-9">
                    <img id="thumbnail_image_url" src="{{ old('image_url', $detail->image_url) ? Helper::showImage( old('image_url', $detail->image_url) ) : URL::asset('public/admin/dist/img/img.png') }}" class="img-thumbnail" width="145" height="85">
                 
                    <button class="btn btn-default btn-sm btnSingleUpload" data-set="image_url" data-image="thumbnail_image" type="button"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Upload</button>
                  </div>
                   <input type="hidden" name="image_url" id="image_url" value="{{ old('image_url', $detail->image_url) }}"/>
                </div>
              <div class="form-group" style="margin-top:10px;margin-bottom:10px">  
                  <label class="col-md-3 row">Icon ( 18 x 18 px)</label>    
                  <div class="col-md-9">
                    <div style="background-color: #000;width: 40px;text-align: center;"><img id="thumbnail_icon_url" src="{{ old('icon_url', $detail->icon_url) ? Helper::showImage( old('icon_url', $detail->icon_url) ) : URL::asset('public/admin/dist/img/img.png') }}" class="img-thumbnail" width="30" height="30"></div>
                 
                    <button class="btn btn-default btn-sm btnSingleUpload" data-set="icon_url" data-image="thumbnail_image" type="button"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Upload</button>
                  </div>
                   <input type="hidden" name="icon_url" id="icon_url" value="{{ old('icon_url', $detail->icon_url) }}"/>
                </div>
              <div class="form-group">
                <label>Màu sắc <span class="red-star">*</span></label>
                <input type="text" class="form-control mau" name="color_code" id="color_code" value="{{ $detail->color_code }}">
              </div>
              <div class="form-group">
                <label>Màu active <span class="red-star">*</span></label>
                <input type="text" class="form-control mau" name="color_active" id="color_active" value="{{ $detail->color_active }}">
              </div>
              <div class="form-group">
                <label>Ẩn/hiện</label>
                <select class="form-control" name="status" id="status">                  
                  <option value="0" {{ $detail->status == 0 ? "selected" : "" }}>Ẩn</option>
                  <option value="1" {{ $detail->status == 1 ? "selected" : "" }}>Hiện</option>
                </select>
              </div>
            
            </div>         
            <!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Lưu</button>
              <a class="btn btn-default" class="btn btn-primary" href="{{ route('cate-parent.index')}}">Hủy</a>
            </div>
            
        </div>
        <!-- /.box -->     

      </div>
      <div class="col-md-5">
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Thông tin SEO</h3>
          </div>
          <!-- /.box-header -->     
            <!-- /.box-header -->
            <div class="box-body">
              <input type="hidden" name="meta_id" value="{{ $detail->meta_id }}">
              <div class="form-group">
                <label>Meta title </label>
                <input type="text" class="form-control" name="meta_title" id="meta_title" value="{{ !empty((array)$meta) ? $meta->title : "" }}">
              </div>
              <!-- textarea -->
              <div class="form-group">
                <label>Meta desciption</label>
                <textarea class="form-control" rows="4" name="meta_description" id="meta_description">{{ !empty((array)$meta) ? $meta->description : "" }}</textarea>
              </div>  

              <div class="form-group">
                <label>Meta keywords</label>
                <textarea class="form-control" rows="2" name="meta_keywords" id="meta_keywords">{{ !empty((array)$meta) ? $meta->keywords : "" }}</textarea>
              </div>  
              <div class="form-group">
                <label>Custom text</label>
                <textarea class="form-control" rows="2" name="custom_text" id="custom_text">{{ !empty((array)$meta) ? $meta->custom_text : ""  }}</textarea>
              </div>
            
          </div>
        <!-- /.box -->     

      </div>
      <!--/.col (left) -->      
    </div>
    </form>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<input type="hidden" id="route_upload_tmp_image" value="{{ route('image.tmp-upload') }}">
@stop
