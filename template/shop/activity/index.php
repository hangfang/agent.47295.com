<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include $viewPath.'header.php';
?>
<div id="banner" class="banner" style="display:none;">
	<div class="container">
		<div class="banner_desc">
			<h1>琳玲Dai购</h1>
			<h2>最贴心的生活助手</h2>
			<div class="button">
                <a href="javascritp:void(0)" class="hvr-shutter-out-horizontal">Shop Now</a>
            </div>
		</div>
	</div>
</div>
<div class="content_top">
	<h3 class="m_1" style="display:none;">限时活动</h3>
	<div class="container">
	   <div class="box_1">
	       <div class="col-md-7-bak">
               <?php 
                $STATIC_CDN_URL = STATIC_CDN_URL;
                for($i=0,$len=count($activityList); $i<$len; $i++){
                    if(($i+1)%3==0){
                        echo '<div class="section group">';
                    }

                    $_activity = $activityList[$i];
                    $_activity['activity_image'] = str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_activity['activity_image']);
                    echo <<<EOF
<div class="col_1_of_3 span_1_of_3">
    <div class="shop-holder">
         <div class="product-img">
            <a href="/shop/activity/product?activity_id={$_activity['activity_id']}">
                <img width_bak="225" height_bak="265" data-original="{$_activity['activity_image']}" src="{$STATIC_CDN_URL}{$staticDir}images/default215x215.png" class="lazy img-responsive"  alt="item4" style="height:17rem;">
            </a>
            <a href="javascript:void(0);" class="button " style="background:transparent;border:none;"></a>
        </div>
    </div>
    <div class="shop-content" style="height: 50px;margin-top: .7rem;">
            <div><a href="/shop/activity/product?activity_id={$_activity['activity_id']}" rel="tag">{$_activity['activity_name']}</a></div>
    </div>
</div>
EOF;
                    if(($i+1)%3==0){
                        echo '<div class="clearfix"></div></div>';
                    }
                }
                ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
</div>
<?php include $viewPath.'footer.php';?>