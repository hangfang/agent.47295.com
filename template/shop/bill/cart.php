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
                productNumber += product['product_num'];
                product['product_image'] = typeof product['product_image']=='string' ? product['product_image'] : product['product_image'][0];
                
                var extra = '';
                <?php if(BaseModel::isAdmin()){?>
                    productVipPrice = new Number(product['product_vip_price']*product['product_num']+productVipPrice).toFixed(2);
                    extra += '<span class="weui_desc_extra" style="position: absolute;bottom: -3px;width: 120px;height: 40px;overflow: hidden;line-height: 38px;font-size: 11px;">成本:'+ product['product_vip_price'] +'</span>';
                <?php } ?>
                html += '<div class="weui_media_box weui_media_appmsg">\
        <div class="weui_media_hd">\
            <img class="lazy weui_media_appmsg_thumb" src="'+ product['product_image'].replace('<?php echo CDN_URL_PLACEHOLDER;?>', '<?php echo IMG_CDN_URL;?>') +'" onerror="this.src=\'<?php echo STATIC_CDN_URL.$staticDir;?>images/qrcode_for_gh_a103c9f558fa_258.jpg\'" >\
        </div>\
        <div class="weui_media_bd" style="height:auto;line-height:0;">\
            <h4 class="weui_media_title" style="margin: 0px;">'+ product['product_name'] +'</h4>\
            <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;">'+extra+'<span class="cart_plus" data=\''+ JSON.stringify(product).replace('\'', '###') +'\'></span><span class="cart_number">'+ product['product_num'] +'</span><span class="cart_minus" data=\''+ JSON.stringify(product).replace('\'', '###') +'\'></span></p>\
        </div>\
    </div>';
            }
            
            <?php
            $_select = '';
            if(BaseModel::isAdmin()){
                $_select = <<<EOF
<div class="weui_cell weui_cell_select weui_select_after">\
    <div class="weui_cell_hd">客户</div>\
    <div class="weui_cell_bd weui_cell_primary">\
        <select class="weui_select user_id" name="user_id">\
        %s\
        </select>\
    </div>\
</div>\
<div class="weui_cell weui_cell_select weui_select_after">\
    <div class="weui_cell_hd">订单</div>\
    <div class="weui_cell_bd weui_cell_primary">\
        <select class="weui_select" name="bill_code" id="bill_code">\
        </select>\
    </div>\
</div>
EOF;
                $_options = '';
                foreach($userList as $_user){
                    $_options .= <<<EOF
<option value="{$_user['id']}">{$_user['user_name']}</option>
EOF;
                }

                $_select = sprintf($_select, $_options);
            }
            ?>
            
            var select = '<?php echo $_select;?>';
            var extra = productVipPrice ? '<span class="weui_desc_extra cart_price_total" style="line-height:3;">成本:'+ productVipPrice +'</span>' : '';
            extra += '<span class="weui_desc_extra cart_number_total" style="position:absolute;line-height:3;right:3.1rem;">'+ productNumber +'件</span>';
            html += '<div class="weui_media_box weui_media_appmsg" style="padding-left: 0px;">\
    <div class="weui_media_hd" style="height:auto;line-height:0;">\
    <span class="weui_desc_extra">总计</span>\
    </div>\
    <div class="weui_media_bd">\
        <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;">\
            '+ extra +'\
        </p>\
    </div>\
</div>\
'+ select +'\
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
$(function(){
    $('#cart').on('click', '.cart_plus', function(){
        var data = $(this).attr('data');
        cart.add(data, $(this));
        
        var json = JSON.parse($(this).attr('data'));
        $(this).siblings('.cart_number').html(json.product_num>99 ? 99 : json.product_num).show();
        return false;
    });

    $('#cart').on('click', '.cart_minus', function(){
        var data = $(this).attr('data');
        cart.minus(data, $(this));
        
        var json = JSON.parse($(this).attr('data'));
        $(this).siblings('.cart_number').html(json.product_num>99 ? 99 : json.product_num).show();
        return false;
    });

    $('#cart').on('click', '.clear_cart', function(){
        layer.confirm('确定要清空购物车吗？', function(){
            delete localStorage.cart;
            location.href = '/shop/index/index';
        });
        return false;
    });
    
    $.ajax({
        url:'/shop/bill/index',
        type:'post',
        dataType:'json',
        data:{"offset":0},
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
            layer.loading(false);
            if(!data){
                layer.error('请求失败,请稍后再试...');
                return false;
            }

            if(data.rtn!=0){
                layer.error(data.error_msg);
                return false;
            }
            
            var options = '<option value="">新订单</option>';
            for(var i in data.data.list){
                var bill = data.data.list[i];
                var d = new Date(bill.create_time*1000);
                var datetime = d.getFullYear()+'-'+d.getMonth()+'-'+d.getDate()+' '+d.getHours()+':'+d.getMinutes()+':'+d.getSeconds();
                options += '<option value="'+ bill.bill_code +'">'+ datetime +'</option>';
            }
            
            $('#bill_code').html(options);
            return true;
        }
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
            productList.push({"product_num":json[i]['product_num'],"product_id":i});
        }
        
        if(!productList){
            layer.error('购物车空空如也~', function(){location.href='/shop/index/index'});
            return false;
        }
        
        layer.loading(true);
        $.ajax({
            url:'/shop/bill/add',
            type:'post',
            dataType:'json',
            data:{"product_list":productList, "bill_code":$('#bill_code').length?$('#bill_code').val():''},
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
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }
                
                delete localStorage.cart;
                layer.toast('下单成功', function(){location.href='/shop/bill/detail?bill_code='+data.data.bill_code;});
                return true;
            }
        });
        return false;
    });
    
    $('#cart').on('change', '.user_id', function(){
        var userId = $(this).val();
        if(!userId){
            layer.error('客户数据错误');
            return false;
        }
        
        layer.loading(true);
        $.ajax({
            url:'/shop/bill/getlist',
            type:'post',
            dataType:'json',
            data:{"user_id":userId},
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
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }
                
                
                return true;
            }
        });
        return false;
    });
});
</script>