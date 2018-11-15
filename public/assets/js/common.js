/*
 * ---------------------------------------------------
 * 1. Scroll to Top
 * 2. Sticky Menu
 * 3. select2
 * 4. datetimepicker
 * 5. Not Overflow datetimepicker
 */

(function($){
  "use strict";
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('.edit').click(function(){
    $('#txtId').val($(this).data('text'));
    $('#txtContent').val($(this).html());
    $('#editContentModal').modal('show');
  });
  $('#btnSaveContent').click(function(){
    $.ajax({
      url : $('#route-save-content').val(),
      type : "POST",
      data : {
        id : $('#txtId').val(),
        content : $('#txtContent').val()
      },
      success:  function(){
        window.location.reload();
      }

    });
  });

  $(document).on('keypress', '.txtSearch', function(e) {
    var obj = $(this);
    if (e.which == 13) {
      if ($.trim(obj.val()) == '') {
        return false;
      }
    }
  });
  
  $(document).on('keypress', '#txtNewsletter', function(e){
    if(e.keyCode==13){
        $('#btnNewsletter').click();
    }
  });
    
  $('#btnNewsletter').click(function() {
      var email = $.trim($('#txtNewsletter').val());        
      if(validateEmail(email)) {
          $.ajax({
            url: $('#route-newsletter').val(),
            method: "POST",
            data : {
              email: email,
            },
            success : function(data){
              if(+data){
                alert('Đăng ký nhận bản tin thành công.');
              }
              else {
                alert('Địa chỉ email đã được đăng ký trước đó.');
              }
              $('#txtNewsletter').val("");
            }
          });
      } else {
          alert('Vui lòng nhập địa chỉ email hợp lệ.')
      }
  });

  /**==============================
  ***  Sticky Menu
  ===============================**/
  $('.fix-header').sticky({ topSpacing: 0 });

 /**==============================
  ***  owl-carousel
  ===============================**/
  
  $(".owl-carousel").each(function(index, el) {
    var config = $(this).data();
    config.navText = ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'];
    config.smartSpeed="300";
    if($(this).hasClass('owl-style2')){
      config.animateOut="fadeOut";
      config.animateIn="fadeIn";    
    }
    $(this).owlCarousel(config);
  });

  $(".owl-carousel-vertical").each(function(index, el) {
    var config = $(this).data();
    config.navText = ['<span class="icon-up"></spam>','<span class="icon-down"></span>'];
    config.smartSpeed="900";
    config.animateOut="";
      config.animateIn="fadeInUp";
    $(this).owlCarousel(config);
  });

  /**==============================
  ***  return to top
  ===============================**/
  $(window).scroll(function() {
    if ($(this).scrollTop() >= 200) {
      $('#return-to-top').addClass('td-scroll-up-visible');
    } else {
      $('#return-to-top').removeClass('td-scroll-up-visible');
    }
  });
  $('#return-to-top').click(function() {
    $('body,html').animate({
      scrollTop : 0
    }, 'slow');
  });

  /*  [ Main Menu ]
  - - - - - - - - - - - - - - - - - - - - */
  $(".toggle-nav").on( 'click', function() {
    $( this ).toggleClass('has-open');
    $("header .menu").toggleClass("has-open");
    $("body").toggleClass("menu-open");
  });

  /** Menu, Menu Mega Responsive **/
  $(document).ready(function(){
    $('.menu ul li.parent').append('<span class="plus"></span>');
    $('.menu ul li.parent .plus').click(function(){
      $(this).toggleClass('open').siblings('.submenu, .product-menu').slideToggle();
    });
  });

  /**==============================
  ***  box-products .toggle-menu
  ===============================**/
  $('.box-products .toggle-menu').click(function() {
    $(this).next().slideToggle();
  });

  /**==============================
  *** header_main
  ===============================**/
  $(window).on('scroll', function() {
    var jheader = $('.header_main'),
      i = $(window).scrollTop();
    if (jheader.hasClass('header_main-simple'))
      return;
    i > jheader.innerHeight() ? jheader.addClass("on-scroll") : i <= jheader.innerHeight() && jheader.removeClass('on-scroll');
    i > window.prevScrollTop + 20 ? (jheader.removeClass("on-scroll-up"), window.prevScrollTop = i) : i < window.prevScrollTop - 20 && (jheader.addClass("on-scroll-up"), window.prevScrollTop = i);
  });

  /**==============================
  ***  bxSlider home
  ===============================**/
  $('#contenhomeslider').bxSlider({
    pager: false,
    auto: true,  
    nextText: '<i class="fa fa-angle-right"></i>',
    prevText: '<i class="fa fa-angle-left"></i>',
    autoStart:true
  });

})(jQuery); // End of use strict
  $(document).ready(function(){
    $('.btn-addcart-product').click(function(){
      var quantity = $('#quantity').val();
      var product_id = $(this).data('id');
      addToCart(product_id, quantity);
    });
  });
  $(document).on('click', '.del_item', function() {
    if(confirm('Quý khách chắc chắn muốn xóa sản phẩm này?')){
      var id = $(this).data('id');
      $(this).parents('.tr-wrap').remove();
      update_product_quantity(id, 0, 'ajax');      
    }
  });
 function addToCart(product_id, quantity) {
   $.ajax({
     url: $('#route-add-to-cart').val(),
     method: "GET",
     data : {
       id: product_id,
       quantity : quantity
     },
     success : function(data){
        location.href = $('#route-cart').val();
     }
   });
 } 
 function update_product_quantity(id, quantity, type) {
    $.ajax({
        url: $('#route-update-product').val(),
        method: "POST",
        data: {
            id: id,
            quantity: quantity
        },
        success: function(data) {
                      
        }
    });
}
function validateEmail(email) {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}
window.fbAsyncInit = function() {
  FB.init({
    appId      : $('#fb-app-id').val(),
    cookie     : true,  // enable cookies to allow the server to access
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.7' // use graph api version 2.7
  });
};
 (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
$(document).ready(function() {
  $('.login-by-facebook-popup').click(function() {
    FB.login(function(response){
      if(response.status == "connected")
      {
         // call ajax to send data to server and do process login
        var token = response.authResponse.accessToken;
        $.ajax({
          url: $('#route-ajax-login-fb').val(),
          method: "POST",
          data : {
            token : token
          },
          success : function(data){
            if(!data.success) {
              location.reload();
            } else {
              location.href = $('#route-cap-nhat-thong-tin').val();
            }
          }
        });

      }
    }, {scope: 'public_profile,email'});
  });  
});