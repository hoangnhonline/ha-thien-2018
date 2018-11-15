<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h2 class="modal-title" id="myModalLabel">
	<i class="fa fa-shopping-cart"></i> GIỎ HÀNG CỦA TÔI
	<span>(Đang có <strong class="order_total_quantity">{!! Session::get('products') ? array_sum(Session::get('products')) : 0 !!}</strong> sản phẩm)</span>
	@if(!empty(Session::get('products')))
	<span><a href="{{ route('empty-cart') }}" class="empty_cart" onclick="return confirm('Quý khách có chắc chắn bỏ hết hàng ra khỏi giỏ?'); " ><i class="fa fa-remove"></i>Làm trống giỏ hàng</a></span>
	@endif
</h2>
</div>
<?php 
$total = 0;
?>
	<div class="modal-body">
		<div class="shopcart-ct">
			<div class="cart-box row">
				<div class="col-md-8">
					<form action="#" method="POST" id="frm_order_items">
						<div class="table cart-tbl">
							<div class="table-row thead">
								<div class="table-cell no-col">&nbsp;</div>
								<div class="table-cell product-col">Sản phẩm</div>
								<div class="table-cell price-col t-r">Đơn giá (đ)</div>
								<div class="table-cell numb-col t-c">Số lượng</div>
								<div class="table-cell total-col t-r">Thành tiền (đ)</div>
							</div><!-- /table-row -->
							@if( $arrProductInfo->count() > 0)
							<?php $i = 0; ?>
		                  @foreach($arrProductInfo as $product)
		                  <?php $i++; ?>
		                  <?php $price = $product->is_sale ? $product->price_sale : $product->price; ?>
							<div class="tr-wrap">
								<div class="table-row clearfix">
									<div class="table-cell no-col"><span>{!! $i !!}</span></div><!-- /table-cell no-col -->
									<div class="table-cell product-col">
										<figure class="img-prod">
											<img alt="{!! $product->name !!}" src="{{ Helper::showImage($product['image_url']) }}">
										</figure>
										<a href="{{ route('product', [$product->slug]) }}" target="_blank" title="{!! $product->name !!}">{!! $product->name !!}</a>
										<a href="javascript:void(0)" onclick="return confirm('Quý khách chắc chắn muốn xóa sản phẩm này?'); " title="Xóa" data-id="{{ $product->id }}" class="del_item">Xóa</a>
									</div><!-- /table-cell product-col -->
									<div class="table-cell price-col t-r">{!! number_format($price) !!}</div><!-- /table-cell price-col t-r -->
									<div class="table-cell numb-col t-c">
										<select data-id="{{$product->id}}" class="change_quantity form-control">
										<?php 
										$soluongton = DB::table('product')->where('id', $product->id)->first()->inventory;
										?>
											@for($i = 1; $i <= $soluongton; $i++ )
				                            <option value="{{$i}}"
				                            @if ($i == $getlistProduct[$product->id])
				                              selected
				                            @endif
				                            > {{$i}}
				                              @if($i == 50) + @endif
				                            </option>
				                            @endfor
										</select> 
									</div><!-- /table-cell numb-col t-c -->
									<?php 
									$total += $total_per_product = ($getlistProduct[$product->id]*$price);
									?>
									<div class="table-cell total-col t-r">{{ number_format($total_per_product)  }}</div><!-- /table-cell total-col t-r -->
								</div>									
							</div><!-- /tr-wrap -->
							@endforeach
							@endif

						</div><!-- /table cart-tbl -->
						
					</form>
				</div>
				<div class="col-md-4">
					<div class="checkout-box-col">
						<table class="total-tbl f-r">
							<tbody>
								<tr>
					                <td>Tổng tiền hàng:</td>
					                <td class="t-r">{!! number_format($total) !!} đ&nbsp;</td>
					            </tr>
					            <tr>
					                <td>Phí vận chuyển:</td>
					                <td class="t-r">
					                    <span class="ship_fee">0</span> đ
					                    <br>
					                </td>
					            </tr>						           
					            <tr class="all-total">
					                <td>Tổng số tiền:</td>
					                <td class="totalmn t-r">{!! number_format($total) !!} đ</td>
					            </tr>
					            @if( $arrProductInfo->count() > 0)
					            <tr>
					                <td colspan="2" class="t-r">
					                    Đã bao gồm VAT						                    
					                </td>
					            </tr>                    
					            @endif
					        </tbody>
						</table>
						<div class="f-r chck-out-btn">
							@if( $arrProductInfo->count() > 0)
				            <a href="{!! route('payment') !!}" class="checkout-btn">THANH TOÁN</a>
				            <a href="javascript:;" class="keep-buying">Hoặc tiếp tục mua hàng</a>
				            @endif
				        </div>
					</div>
				</div>
			</div>
		</div>
	</div>