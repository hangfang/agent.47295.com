<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .weui_media_desc .add_to_cart {float:right;}
</style>
<div class="searchbar">
    <div class="hd" style='display: none;'>
        <h1 class="page_title">热搜排行</h1>
    </div>
    <div class="bd">
        <!--<a href="javascript:;" class="weui_btn weui_btn_primary">点击展现searchBar</a>-->
        <div class="weui_search_bar" id="search_bar">
            <form class="weui_search_outer" method="get" action="/shop/product/search">
                <div class="weui_search_inner">
                    <i class="weui_icon_search"></i>
                    <input type="text" class="weui_search_input" id="search_input" name="search" placeholder="搜索" required="">
                    <a href="javascript:" class="weui_icon_clear" id="search_clear"></a>
                </div>
                <label for="search_input" class="weui_search_text" id="search_text" style="margin-bottom: 0px;">
                    <i class="weui_icon_search"></i>
                    <span>搜索</span>
                </label>
            </form>
            <a href="javascript:" class="weui_search_cancel" id="search_cancel">取消</a>
        </div>
        <div class="weui_cells weui_cells_access search_show" id="search_show">
        </div>
    </div>
</div>
<div class="weui_panel weui_panel_access" style="<?php if(!$data['search']){echo 'display:none;';}?>" id="product_list">
    <div class="weui_panel_hd" style='display:none;'><?php echo $title;?></div>
    <div class="weui_panel_bd">
        <?php 
            $STATIC_CDN_URL = STATIC_CDN_URL;
            if(empty($data['list'])){
                echo <<<EOF
<a href="javascript:void(0)" class="weui_media_box weui_media_appmsg">
    <div class="weui_media_hd">
        <img class="weui_media_appmsg_thumb" src="{$STATIC_CDN_URL}{$staticDir}images/qrcode_for_gh_a103c9f558fa_258.jpg" >
    </div>
    <div class="weui_media_bd">
        <h4 class="weui_media_title"><span style="color:red;">{$data['search']}</span>未搜索到结果</h4>
        <p class="weui_media_desc"></p>
    </div>
</a>
EOF;
            }
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
                    $_extra .= '<span class="weui_desc_extra">浏览量:'. $_product['product_views'] .'</span>';
                }
                $_extra .= '<span class="weui_desc_extra">销量:'. $_product['product_purchased'] .'</span>';
                
                $_productName = str_replace($data['search'], '<span style="color:red;">'.$data['search'].'</span>', $_product['product_name']);
                echo <<<EOF
<a href="/shop/product/detail?product_id={$_product['product_id']}" class="weui_media_box weui_media_appmsg">
    <div class="weui_media_hd">
        <img class="lazy weui_media_appmsg_thumb" data-original="{$_imgSrc}" src="{$STATIC_CDN_URL}{$staticDir}images/qrcode_for_gh_a103c9f558fa_258.jpg" >
    </div>
    <div class="weui_media_bd">
        <h4 class="weui_media_title">{$_productName}</h4>
        <p class="weui_media_desc">{$_extra}<span class="weui_btn weui_btn_mini weui_btn_primary add_to_cart" data='{$_productData}'>+购物车</span></p>
    </div>
</a>
EOF;
            }
        ?>
    </div>
    <a class="weui_panel_ft" href="javascript:void(0);" style="<?php if($data['total']<10){ echo 'display:none;';}?>">查看更多</a>
</div>
<div class="weui_panel_bd" style="<?php if($data['search']){echo 'display:none;';}?>" id="history_hot">
    <div class="weui_media_box weui_media_text">
        <ul class="weui_media_info" id="history_search">
            <li class="weui_media_info_meta search_word" style="margin-bottom: 0.5rem;color: #000;width:100%;">搜索记录</li>
        </ul>
        <ul class="weui_media_info" id="hot_search">
            <li class="weui_media_info_meta search_word" style="margin-bottom: 0.5rem;color: #000;width:100%;">热门搜索</li>
            <?php
                foreach($data['hot'] as $_search){
                    echo '<li class="weui_media_info_meta search_word" style="margin-bottom: 0.5rem;"><a href="/shop/product/search?search='. $_search['search_word'] .'" style="color: pink;text-decoration:underline;">'. $_search['search_word'] .'</a></li>';
                }
            ?>
        </ul>
    </div>
</div>
<script>
    $(function(){
        var search = '<?php echo $data['search'];?>';
        var total = <?php echo $data['total'];?>;
        var offset = 10;
        var xhrIng = false;
        $('#search_input').on('keyup', function(e){
            $('#search_cancel').show();
            $('#product_list').empty();
            var keyword = $(this).val();
            if(keyword.length>0){
                
                if(e.keyCode==13){
                    $(this).closest('form').submit();
                    return false;
                }
                
                layer.loading(true);
                 $.ajax({
                     url:'/shop/product/search',
                     type:'get',
                     dataType:'json',
                     data:{"search":keyword},
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

                         if(typeof localStorage.search==='undefined'){
                             var tmp = {};
                         }else{
                             var json = localStorage.search;
                             var tmp = JSON.parse(json);
                         }

                         tmp[keyword] = keyword;
                         localStorage.search = JSON.stringify(tmp);

                         var html = '';
                         for(var i in data.data.list){
                             var product = data.data.list[i];
                             productName = product['product_name'].replace(search, '<span style="color:red;">'+ search +'</span>');
                             html += '<div class="weui_cell" style="padding:1px 5px 1px 8px;">\
                                         <div class="weui_cell_bd weui_cell_primary" style="padding-left:0px;">\
                                             <p style="height: 2rem;line-height: 2;overflow: hidden;margin: 0;">'+ (i-0+1) +'.<a href="/shop/product/detail?product_id='+ product['product_id'] +'" style="color:#777">'+ productName +'</a></p>\
                                         </div>\
                                     </div>';
                         }

                         if(data.data.total>10){
                             html += '<div class="weui_cell" style="padding:1px 5px 1px 8px;">\
                                         <div class="weui_cell_bd weui_cell_primary" style="padding-left:0px;">\
                                             <a class="weui_btn weui_btn_primary" href="/shop/product/search?search='+ keyword +'" style="color:#777">查看全部</a>\
                                         </div>\
                                     </div>';
                         }

                         $('#history_hot').hide();
                         $('#search_show').html(html).show();
                         return true;
                     }
                 });
            }
        });

        $('#search_text').on('click', function(){
            $(this).remove();
            $('#search_input').focus();
            $('#search_cancel').show();
        });

        $('#search_cancel,#search_clear').on('click', function(){
            $('#search_text').show();
            $('#search_input').val('');
            $('#search_show').hide();
             $('#history_hot').show();
        });
       
        $('.weui_panel_ft').click(function(){
            var _this = this;
            if(offset>=total){
                $(_this).remove();
                return false;
            }

            $.ajax({
                url:location.href,
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

                        productName = product['product_name'].replace(search, '<span style="color:red;">'+ search +'</span>');
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
       
       if(typeof localStorage.search==='undefined'){
            $('#history_search').append('<li class="weui_media_info_meta search_word" style="margin-bottom: 0.5rem;color: #777;width:100%;text-align:center;">还没有所搜历史</li>');
        }else{
            var json = localStorage.search;
            var tmp = JSON.parse(json);
            var html = '';
            var index = 0;
            for(var i in tmp){
                html += '<li class="weui_media_info_meta search_word" style="margin-bottom: 0.5rem;"><a href="/shop/product/search?search='+ i +'" style="color: #777;text-decoration:underline;">'+ i +'</a></li>';
                if(++index>30){
                    break;
                }
            }
            
            $('#history_search').append(html);
        }
    });
</script>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>