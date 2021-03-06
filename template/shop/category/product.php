<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .weui_media_desc .add_to_cart {float:right;}
</style>
<div class="weui_panel weui_panel_access">
    <div class="weui_panel_hd" style='display:none;'><?php echo $title;?></div>
    <div class="weui_panel_bd">
        <?php 
            $STATIC_CDN_URL = STATIC_CDN_URL;
            for($i=0,$len=count($data['list']); $i<$len; $i++){
                $_product = $data['list'][$i];
                if(!empty($_product['product_image'])){
                    $_product['product_image'] = explode(',', $_product['product_image']);
                    $_product['product_image'] = $_product['product_image'][0];
                }else{
                    $_product['product_image'] = '';
                }

                $_imgSrc = empty($_product['product_image']) ? '' : str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_product['product_image']);
                $_productData = str_replace('\'', '###', json_encode($_product));
                $_extra = '';
                if(BaseModel::isAdmin()){
                    $_extra .= '<span class="weui_desc_extra">成本:'. $_product['product_vip_price'] .'</span>';
                }else{
                    //$_extra .= '<span class="weui_desc_extra">浏览量:'. $_product['product_views'] .'</span>';
                }
                $_extra .= '<span class="weui_desc_extra">销量:'. $_product['product_purchased'] .'</span>';
                
                echo <<<EOF
<a href="/shop/product/detail?product_id={$_product['product_id']}" class="weui_media_box weui_media_appmsg">
    <div class="weui_media_hd">
        <img class="lazy weui_media_appmsg_thumb" data-original="{$_imgSrc}" src="{$STATIC_CDN_URL}{$staticDir}images/qrcode_for_gh_a103c9f558fa_258.jpg" >
    </div>
    <div class="weui_media_bd">
        <h4 class="weui_media_title">{$_product['product_name']}</h4>
        <p class="weui_media_desc">{$_extra}<span class="weui_btn weui_btn_mini weui_btn_primary add_to_cart" data='{$_productData}'>+购物车</span></p>
    </div>
</a>
EOF;
            }
        ?>
    </div>
    <?php if($data['total']>10){ echo '<a class="weui_panel_ft" href="javascript:void(0);">查看更多</a>';}?>
</div>
<script>
    (function(){
        var total = <?php echo $data['total'];?>;
        var offset = 10;
        $(function(){
            var xhrIng = false;
            $('.weui_panel_ft').click(function(){
                var _this = this;
                if(offset>=total){
                    $(_this).remove();
                    return false;
                }

                $.ajax({
                    url:'/shop/category/product',
                    type:'get',
                    dataType:'json',
                    data:{'offset':offset, 'length':10},
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

                        var html = '';
                        for(var i=0,len=data.data.list.length;i<len;i++){

                            var product = data.data.list[i];

                            if(product['product_image']){
                                product['product_image'] = product['product_image'].split(',');
                                product['product_image'] = product['product_image'][0];
                            }else{
                                product['product_image'] = '';
                            }

                            <?php
                                if(BaseModel::isAdmin()){
                                    echo 'var extra = \'<span class="weui_desc_extra">成本:\'+ product[\'product_vip_price\'] +\'</span>\';';
                                }else{
                                    echo 'var extra = \'<span class="weui_desc_extra">浏览量:\'+ product[\'product_views\'] +\'</span>\';';
                                }
                            ?>

                            extra += '<span class="weui_desc_extra">销量:'+ product['product_purchased'] +'</span>';
                            var imgSrc = product['product_image'] ? product['product_image'].replace('<?php echo CDN_URL_PLACEHOLDER;?>', '<?php echo IMG_CDN_URL;?>') : '';
                            
                            html += '<a href="/shop/product/detail?product_id='+ product['product_id'] +'" class="weui_media_box weui_media_appmsg">\
    <div class="weui_media_hd">\
        <img class="weui_media_appmsg_thumb" src="'+ imgSrc +'" onerror="this.src=\'<?php echo $STATIC_CDN_URL.$staticDir;?>images/qrcode_for_gh_a103c9f558fa_258.jpg\'" >\
    </div>\
    <div class="weui_media_bd">\
        <h4 class="weui_media_title">'+ product['product_name'] +'</h4>\
        <p class="weui_media_desc">'+ extra +'<span class="weui_btn weui_btn_mini weui_btn_primary add_to_cart" data=\''+ JSON.stringify(product).replace('\'', '###') +'\'>+购物车</span></p>\
    </div>\
</a>';
                        }

                        $('.weui_panel_bd').append(html);
                        offset += 10;
                        if(offset>=total){
                            $(_this).remove();
                            return false;
                        }
                        xhrIng = false;
                    }
                });
            });
        })
    })();
</script>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>