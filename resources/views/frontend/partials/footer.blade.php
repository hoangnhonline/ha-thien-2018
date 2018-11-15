<footer id="footer" class="footer clearfix">
		<div class="footer_top">
		    <div class="container">
		        <div class="row">
		            <div class="col-md-8">
		                <div class="row footer-menu">
		                    <div class="col-md-6 footer-menu_column menu">
		                        <h3 class="menu_heading @if($isEdit) edit @endif" data-text="1">{!! $textList[1] !!}</h3>
		                        <ul class="menu_listing">
		                            <li><a href="{{ route('pages', 've-chung-toi') }}">Về chúng tôi</a></li>
		                            <li><a href="{{ route('pages', 'cach-thuc-thanh-toan') }}">Cách thức thanh toán</a></li>
		                            <li><a href="{{ route('pages', 'chinh-sach-bao-mat-thong-tin') }}">Chính sách bảo mật thông tin</a></li>		                            
		                            <li><a href="{{ route('contact') }}">Liên Hệ</a></li>
		                        </ul>
		                    </div>
		                    <div class="col-md-6 footer-menu_column menu">
		                        <h3 class="menu_heading @if($isEdit) edit @endif" data-text="2">{!! $textList[2] !!}</h3>
		                        <ul class="menu_listing">
		                            <li><a href="{{ route('pages', 'chinh-sach-giao-hang') }}">Chính sách giao hàng</a></li>
		                            <li><a href="{{ route('pages', 'chinh-sach-doi-tra-hang') }}">Chính sách đổi trả hàng</a></li>
		                            <li><a href="{{ route('pages', 'co-che-cong-doanh-so-va-ap-dung-chiet-khau-cho-thanh-vien') }}">Cơ chế cộng doanh số và áp dụng chiết khấu cho thành viên</a></li>
		                            <li><a href="{{ route('pages', 'co-che-giai-quyet-tranh-chap-khieu-nai') }}">Cơ chế giải quyết tranh chấp, khiếu nại</a></li>		                           
		                        </ul>
		                    </div>
		                    
		                </div>
		            </div>
		            <div class="col-md-4 support-info">
		                <div class="col-md-12">
		                    <h3 class="support-info_heading @if($isEdit) edit @endif" data-text="4">{!! $textList[4] !!}</h3>
		                    <div class="support-info_address @if($isEdit) edit @endif" data-text="5">{!! $textList[5] !!}</div>
		                    <div class="support-info_contact contact">
		                        <div class="contact_email @if($isEdit) edit @endif" data-text="6">{!! $textList[6] !!}</div>
		                        <div class="contact_email">Email: <a href="mailto:{!! $textList[7] !!}" data-text="7" @if($isEdit) class="edit" @endif>{!! $textList[7] !!}</a></div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
		<div class="footer_middle">
			<div class="container">
				<div class="col-fter-1 col-xs-0">					
					<p style="margin-bottom: 10px;"><strong data-text="8" @if($isEdit) class="edit" @endif>{!! $textList[8] !!}</strong></p>
					<div class="newsletter form-inline">
						<div class="form-group has-feedback">
							<div class="input-group">
								<input type="text" class="form-control newsletter_input" id="txtNewsletter" placeholder="Nhập email của bạn" value="">
							</div>
						</div>
						<div class="form-group">
							<div class="input-group">
								<button type="button" id="btnNewsletter" class="btn btn-primary newsletter_button">Đăng ký
								</button>
							</div>
						</div>
					</div>					
				</div>
				<div class="col-fter-3">
					<div class="social">
						<div class="social_heading footer-menu-heading @if($isEdit) edit @endif" data-text="9">{!! $textList[9] !!}</div>
						<div class="social_items">
							<a target="_blank" class="social_item social_item-facebook" href="#" rel="nofollow"><i class="fa fa-facebook"></i></a>
							<a target="_blank" class="social_item social_item-twitter" href="#" rel="nofollow"><i class="fa fa-twitter"></i></a>
							<a target="_blank" class="social_item social_item-google-plus" href="#" rel="nofollow"><i class="fa fa-google-plus"></i></a>
							<a target="_blank" class="social_item social_item-linkedin" href="#" rel="nofollow"><i class="fa fa-linkedin"></i></a>
							<a target="_blank" class="social_item social_item-youtube" href="#" rel="nofollow"><i class="fa fa-youtube"></i></a>
						</div>
					</div>
				</div>
				<div class="col-fter-4">
					<div class="hotline">
						<span class="hotline_text">HOTLINE&nbsp;</span>
						<span class="hotline_number @if($isEdit) edit @endif" data-text="11">{!! $textList[11] !!}</span>
					</div>
				</div>
				 <div class="col-fter-5">
		            <a target="_blank" href="http://online.gov.vn/HomePage/CustomWebsiteDisplay.aspx?DocId=40884">
		            <img class="img-responsive" src="http://online.gov.vn/Images/dathongbao.png" style="max-width: 165px;">
		            </a>
		        </div>
			</div>
		</div>
		<div class="footer_bottom ">
		    <div class="container">
	            <p class="text-center">
	                <strong class="font15 @if($isEdit) edit @endif" data-text="12">{!! $textList[12] !!}</strong><br>
	                <span data-text="13" @if($isEdit) class="edit" @endif>{!! $textList[13] !!}</span><br>
	                ĐT: <span data-text="14" @if($isEdit) class="edit" @endif>{!! $textList[14] !!}</span> - <br class="visible-xs visible-sm">Email: <a href="mailto:{!! $textList[16] !!}" data-text="16" @if($isEdit) class="edit" @endif>{!! $textList[16] !!}</a>
	            </p>
		    </div>
		</div>
	</footer><!-- /.footer -->