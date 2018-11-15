<!-- Modal Cart -->
<div class="modal fade" id="Cart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i class="fa fa-times-circle"></i>
			</button>
			<div class="shopcart-ct">
				<div class="modal-body">
					<form action="#" method="POST" id="frm_order_items">
						<div class="table cart-tbl">
							<div class="table-row thead">
								<div class="table-cell product-col t-c">Sản phẩm</div>
								<div class="table-cell numb-col t-c">Số lượng</div>
								<div class="table-cell total-col t-c">Thành tiền</div>
							</div><!-- table-row thead -->
							<div class="tr-wrap">
								<div class="table-row clearfix">
									<div class="table-cell product-col">
										<figure class="img-prod">
											<img alt="Tên sản phẩm quần jeans chất lượng cao được thêm vào giỏ hàng" src="{{ URL::asset('public/assets/images/cart/1.jpg') }}">
										</figure>
										<a href="#" target="_blank" title="Tên sản phẩm quần jeans chất lượng cao được thêm vào giỏ hàng">Tên sản phẩm quần jeans chất lượng cao được thêm vào giỏ hàng</a>
										<p class="p-color">
											<span>Màu sắc sản phẩm:</span>
											<span>Đen</span>
										</p>
										<p class="p-size">
											<span>Size sản phẩm:</span>
											<span>39</span>
											<span>|</span>
											<a href="#" title="Xóa sản phẩm">Xóa</a>
										</p>
									</div>
									<div class="table-cell numb-col t-c">
										<select name="" size="1" class="change_quantity">
											<option value="0">0</option>
											<option value="1" selected="">1</option>
											<option value="2">2</option>
										</select>
									</div>
									<div class="table-cell total-col t-r">355.000đ</div><!-- /table-cell total-col t-r -->
								</div>
								<div class="table-row clearfix">
									<div class="table-cell product-col">
										<figure class="img-prod">
											<img alt="Tên sản phẩm quần jeans chất lượng cao được thêm vào giỏ hàng" src="{{ URL::asset('public/assets/images/cart/1.jpg') }}">
										</figure>
										<a href="#" target="_blank" title="Tên sản phẩm quần jeans chất lượng cao được thêm vào giỏ hàng">Tên sản phẩm quần jeans chất lượng cao được thêm vào giỏ hàng</a>
										<p class="p-color">
											<span>Màu sắc sản phẩm:</span>
											<span>Đen</span>
										</p>
										<p class="p-size">
											<span>Size sản phẩm:</span>
											<span>39</span>
											<span>|</span>
											<a href="#" title="Xóa sản phẩm">Xóa</a>
										</p>
									</div>
									<div class="table-cell numb-col t-c">
										<select name="" size="1" class="change_quantity">
											<option value="0">0</option>
											<option value="1" selected="">1</option>
											<option value="2">2</option>
										</select>
									</div>
									<div class="table-cell total-col t-r">355.000đ</div><!-- /table-cell total-col t-r -->
								</div>
							</div><!-- tr-wrap -->
						</div><!-- table cart-tbl -->
						<div class="block-btn">
							<a href="#" title="Xóa tất cả" class="btn btn-default">Xóa tất cả <i class="fa fa-trash-o"></i></a>
							<a href="cart.html" title="Xóa tất cả" class="btn btn-danger">Thanh toán <i class="fa fa-angle-right"></i></a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="editContentModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cập nhật nội dung</h4>
      </div>
      <form method="POST" action="{{ route('save-content') }}">
      {{ csrf_field() }}
      <input type="hidden" name="id" id="txtId" value="">
      <div class="modal-body">
        <textarea rows="5" class="form-control" name="content" id="txtContent"></textarea>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-primary" id="btnSaveContent">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>