<div class="col-sm-3" id="left_column">  
  <!-- block category -->
      <div class="block left-module">
          <p class="title_block">Thông tin tài khoản</p>
          <div class="block_content">
              <!-- layered -->
              <div class="layered layered-category">
                  <div class="layered-content">
                      <ul class="tree-menu">
                         
                          <li {{ \Request::route()->getName() == "order-history" || \Request::route()->getName() == "order-detail" ? "class=active" : "" }}>
                              <a href="{{ route('order-history') }}" title="Đơn hàng của tôi"> Đơn hàng của tôi</a>
                          </li>                         
                          <li>
                              <a href="{{ route('logout') }}" title="Thoát tài khoản">Thoát tài khoản </a>
                          </li>
                      </ul>
                  </div>
              </div>
              <!-- ./layered -->
          </div>
      </div>
      <!-- ./block category  -->
      <!-- Banner silebar -->
      
      <!-- ./Banner silebar -->
</div> 
