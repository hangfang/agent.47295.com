<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .weui_media_desc .add_to_cart {float:right;}
</style>
<div class="weui_panel weui_panel_access">
    <div class="weui_panel_hd" style='display:none;'><?php echo $title;?></div>
    <div class="weui_panel_bd" id="cart">
        
    </div>
</div>
<script>
    $(function(){
        if(typeof localStorage.cart==='undefined'){
            layer.error('购物车空空如也,立即选货吧', function(){location.href='/shop/index/index'});
        }else{
            var json = localStorage.cart;
            var cart = JSON.parse(json);
            var html = '';
            for(var i in cart){
                var product = cart[i]; 
                html += '<div class="weui_media_box weui_media_appmsg">\
        <div class="weui_media_hd">\
            <img class="lazy weui_media_appmsg_thumb" src="'+ product['product_image'].replace('<?php echo CDN_URL_PLACEHOLDER;?>', '<?php echo IMG_CDN_URL;?>') +'" onerror="this.src=\'<?php echo STATIC_CDN_URL.$staticDir;?>images/qrcode_for_gh_a103c9f558fa_258.jpg\'" >\
        </div>\
        <div class="weui_media_bd">\
            <h4 class="weui_media_title" style="margin: 0px;">'+ product['product_name'] +'</h4>\
            <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;"><span class="weui_btn weui_btn_mini weui_btn_primary" style="line-height: 33px;position: absolute;bottom: 0;width: 59px;" onclick="location.href=\'/shop/product/detail?product_id='+ product['product_id'] +'\'">详情</span><span class="cart_plus" data=\''+ JSON.stringify(product) +'\'></span><span class="cart_number">'+ product['product_number'] +'</span><span class="cart_minus" data=\''+ JSON.stringify(product) +'\'></span></p>\
        </div>\
    </div>';

                $('#cart').html(html);
            }
        };
    });
</script>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>
<script>
    $('#cart').on('click', '.cart_plus', function(){
        cart.add($(this).attr('data'), $(this));
        var num = cart.refresh();
        $('.cart_number').html(num>99 ? 99 : num).show();
        return false;
    });

    $('#cart').on('click', '.cart_minus', function(){
        cart.minus($(this).attr('data'), $(this));
        var num = cart.refresh();
        $('.cart_number').html(num>99 ? 99 : num).show();
        return false;
    });
</script>