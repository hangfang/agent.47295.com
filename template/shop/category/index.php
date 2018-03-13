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
            for($i=0,$len=count($categoryList); $i<$len; $i++){
                $_category = $categoryList[$i];

                $_imgSrc = empty($_category['category_banner']) ? '' : str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_category['category_banner']);
                
                echo <<<EOF
<a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
    <div class="weui_media_hd" style="width: 20rem;height: 120px;">
        <img class="lazy weui_media_appmsg_thumb" src="{$_imgSrc}" onerror="this.src='{$STATIC_CDN_URL}{$staticDir}images/qrcode_for_gh_a103c9f558fa_258.jpg'" style="height:100%">
    </div>
    <div class="weui_media_bd" style="width: 60px;height: 120px;">
        <p class="weui_media_desc" style="margin: 0px;position: relative;line-height: 1rem;width: 100%;height: 100%;"><span class="weui_btn weui_btn_mini weui_btn_primary" style="position:absolute;right:0px;margin:0px;bottom:0px;" onclick="location.href='/shop/category/subcategory?category_id={$_category['category_id']}'">选子分类</span><span class="weui_btn weui_btn_mini weui_btn_primary" style="position:absolute;right:0px;margin:0;bottom:43px;" onclick="location.href='/shop/category/product?category_id={$_category['category_id']}'">选商品</span></p>
    </div>
</a>
EOF;
            }
        ?>
    </div>
</div>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>