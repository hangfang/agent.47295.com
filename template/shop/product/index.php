<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .weui_media_desc .add_to_cart {float:right;}
</style>
<div class="weui_cells_title">指定分类</div>
<div class="weui_cells">
    <div class="weui_cell weui_cell_select weui_select_after" style="float:left;">
        <div class="weui_cell_hd">
        </div>
        <div class="weui_cell_bd weui_cell_primary" style="-webkit-box-flex: 2;-webkit-flex: 2;-ms-flex: 2;flex: 2;">
            <select class="weui_select" name="category_id" id='category_id'>
                <option <?php $categoryId ? '' : 'selected'?> value="">父分类</option>
                <?php
                    foreach($category as $_category){
                        echo '<option '. ($_category['category_id']==$categoryId ? 'selected' : '') .' value='. $_category['category_id'] .'>'. $_category['category_name'] .'</option>';
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="weui_cell weui_cell_select weui_select_after">
        <div class="weui_cell_hd">
        </div>
        <div class="weui_cell_bd weui_cell_primary" style="-webkit-box-flex: 2;-webkit-flex: 2;-ms-flex: 2;flex: 2;">
            <select class="weui_select" name="sub_category_id" id='sub_category_id'>
                <option <?php $subCategoryId ? '' : 'selected'?> value="">子分类</option>
                <?php
                    foreach($subCategory as $_subCategory){
                        if($categoryId && $categoryId==$_subCategory['parent_id']){
                            echo '<option '. ($_subCategory['category_id']==$subCategoryId ? 'selected' : '') .' value='. $_subCategory['category_id'] .'>'. $_subCategory['category_name'] .'</option>';
                        }
                    }
                ?>
            </select>
        </div>
    </div>
</div>
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
                $_productData = json_encode($_product);
                $_extra = '';
                if(BaseModel::isAdmin()){
                    $_extra .= '<span class="weui_desc_extra">Vip价:￥'. $_product['product_vip_price'] .'</span>';
                }else{
                    $_extra .= '<span class="weui_desc_extra">浏览量:'. $_product['product_views'] .'</span>';
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
        var category = '<?php echo str_replace('\'', '\\\'', json_encode($category));?>';
        var total = <?php echo $data['total'];?>;
        var offset = 10;
        $(function(){
            var xhrIng = false;
            $('.weui_panel_ft').click(function(){
                var _this = this;
                if(offset>=total-1){
                    $(_this).remove();
                    return false;
                }

                var categoryId = $('#category_id').val();
                var subCategoryId = $('#sub_category_id').val();
                $.ajax({
                    url:'/shop/product/index',
                    type:'get',
                    dataType:'json',
                    data:{'offset':offset, 'length':10, 'category_id':categoryId, 'sub_category_id':subCategoryId},
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
                                    echo 'var extra = \'<span class="weui_desc_extra">Vip价:￥\'+ product[\'product_vip_price\'] +\'</span>\';';
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
        <p class="weui_media_desc">'+ extra +'<span class="weui_btn weui_btn_mini weui_btn_primary add_to_cart" data=\''+ JSON.stringify(product) +'\'>+购物车</span></p>\
    </div>\
</a>';
                        }

                        $('.weui_panel_bd').append(html);
                        offset += 10;
                        if(offset>=total-1){
                            $(_this).remove();
                            return false;
                        }
                        xhrIng = false;
                    }
                });
            });
            
            $('#category_id').change(function(){
                var categoryId = $('#category_id').val();
                var subCategoryId = $('#sub_category_id').val('');
                
                if(categoryId){
                    location.href = '/shop/product/index?category_id='+categoryId;
                    return false;
                }
            });
            
            $('#sub_category_id').change(function(){
                var categoryId = $('#category_id').val();
                var subCategoryId = $('#sub_category_id').val();
                if(subCategoryId){
                    for(var i in subCategoryId){
                        if(subCategoryId[i].category_id==subCategoryId){
                            categoryId = subCategoryId[i].parent_id;
                        }
                    }
                    
                    if(!categoryId){
                        layer.error('父分类不存在');
                        return false;
                    }
                    
                    $('#category_id').val(categoryId);
                    location.href = '/shop/product/index?category_id='+categoryId+'&sub_category_id='+subCategoryId;
                    return false;
                }
                
                layer.error('分类不存在');
                return false;
            });
        })
    })();
</script>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>