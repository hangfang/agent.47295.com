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
            for($i=0,$len=count($activityList); $i<$len; $i++){
                $_activity = $activityList[$i];

                $_imgSrc = empty($_activity['activity_image']) ? '' : str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_activity['activity_image']);
                
                echo <<<EOF
<a href="/shop/activity/product?activity_id={$_activity['activity_id']}" class="weui_media_box weui_media_appmsg">
    <div class="weui_media_hd" style="width: 100%;height: 120px;margin-right:0px;">
        <img class="lazy weui_media_appmsg_thumb" src="{$_imgSrc}" onerror="this.src='{$STATIC_CDN_URL}{$staticDir}images/qrcode_for_gh_a103c9f558fa_258.jpg'" style="height:100%">
    </div>
</a>
EOF;
            }
        ?>
    </div>
</div>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>