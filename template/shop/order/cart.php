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
            var productVipPrice = 0;
            var productNumber = 0;
            
            for(var i in cart){
                var product = cart[i];
                productNumber += product['product_number'];
                var extra = '';
                <?php if(BaseModel::isAdmin()||1){?>
                    productVipPrice = new Number(product['product_vip_price']*product['product_number']+productVipPrice).toFixed(2);;
                    extra += '<span class="weui_desc_extra" style="position: absolute;bottom: -3px;width: 120px;height: 40px;overflow: hidden;line-height: 38px;font-size: 11px;">Vip价:￥'+ product['product_vip_price'] +'</span>';
                <?php } ?>
                html += '<div class="weui_media_box weui_media_appmsg">\
        <div class="weui_media_hd">\
            <img class="lazy weui_media_appmsg_thumb" src="'+ product['product_image'].replace('<?php echo CDN_URL_PLACEHOLDER;?>', '<?php echo IMG_CDN_URL;?>') +'" onerror="this.src=\'<?php echo STATIC_CDN_URL.$staticDir;?>images/qrcode_for_gh_a103c9f558fa_258.jpg\'" >\
        </div>\
        <div class="weui_media_bd" style="height:auto;line-height:0;">\
            <h4 class="weui_media_title" style="margin: 0px;">'+ product['product_name'] +'</h4>\
            <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;">'+extra+'<span class="cart_plus" data=\''+ JSON.stringify(product) +'\'></span><span class="cart_number">'+ product['product_number'] +'</span><span class="cart_minus" data=\''+ JSON.stringify(product) +'\'></span></p>\
        </div>\
    </div>';
            }
            
            var extra = productVipPrice ? '<span class="weui_desc_extra cart_price_total" style="line-height:3;">￥'+ productVipPrice +'</span>' : '';
            extra += '<span class="weui_desc_extra cart_number_total" style="position:absolute;line-height:3;right:3.1rem;">'+ productNumber +'件</span>';
            html += '<div class="weui_media_box weui_media_appmsg">\
    <div class="weui_media_hd" style="height:auto;line-height:0;">\
    <span class="weui_desc_extra">总计</span>\
    </div>\
    <div class="weui_media_bd">\
        <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;">\
            '+ extra +'\
        </p>\
    </div>\
</div>\
<div class="weui_media_box weui_media_appmsg">\
    <div class="weui_media_bd">\
        <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;display: block;">\
            <span class="weui_btn weui_btn_mini weui_btn_warn clear_cart" style="float: left;display: block;">清空购物车</span>\
            <span class="weui_btn weui_btn_mini weui_btn_primary create_order" style="float: right;display: block;margin:0;">下单</span>\
        </p>\
    </div>\
</div>';
            $('#cart').html(html);
        };
    });
</script>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>
<script>
    $('#cart').on('click', '.cart_plus', function(){
        var data = $(this).attr('data');
        cart.add(data, $(this));
        
        var json = JSON.parse($(this).attr('data'));
        $(this).siblings('.cart_number').html(json.product_number>99 ? 99 : json.product_number).show();
        return false;
    });

    $('#cart').on('click', '.cart_minus', function(){
        var data = $(this).attr('data');
        cart.minus(data, $(this));
        
        var json = JSON.parse($(this).attr('data'));
        $(this).siblings('.cart_number').html(json.product_number>99 ? 99 : json.product_number).show();
        return false;
    });

    $('#cart').on('click', '.clear_cart', function(){
        layer.confirm('确定要清空购物车吗？', function(){
            delete localStorage.cart;
            location.href = '/shop/index/index';
        });
        return false;
    });

    var xhrIng = false;
    $('#cart').on('click', '.create_order', function(){
        if(typeof localStorage.cart=='undefined' || localStorage.cart=='{}'){
            layer.error('购物车空空如也~', function(){location.href='/shop/index/index'});
            return false;
        }
        
        var json = JSON.parse(localStorage.cart);
        var productList = [];
        for(var i in json){
            productList.push({"product_num":json[i]['product_number'],"product_id":i});
        }
        
        if(!productList){
            layer.error('购物车空空如也~', function(){location.href='/shop/index/index'});
            return false;
        }
        
        $.ajax({
            url:'/shop/order/add',
            type:'post',
            dataType:'json',
            data:{"product_list":productList},
            beforeSend:function(xhr){
                if(xhrIng){
                    xhr.abort();
                    return false;
                }

                xhrIng = true;
            },
            complete:function(){
                xhrIng = false;
            },
            success:function(data, xhr){
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }
                
                layer.msg('下单成功', function(){location.href='/shop/order/detail?order_code'=data.data.order_code;});
                return true;
            }
        });
        return false;
    });
</script>