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
	<h3 class="m_1" style="display:none;">二级分类</h3>
	<div class="container">
	   <div class="box_1">
	       <div class="col-md-7-bak">
               <?php 
                for($i=0,$len=count($categoryList); $i<$len; $i++){
                    if(($i+1)%3==0){
                        echo '<div class="section group">';
                    }

                    $_category = $categoryList[$i];
                    $_imgSrc = !empty($_category['category_image']) ? KISSBABY_IMAGE_URL.$_category['category_image'] : '';
                    echo <<<EOF
<div class="col_1_of_3 span_1_of_3">
    <div class="shop-holder">
         <div class="product-img">
            <a href="/shop/category/product?category_id={$_category['category_id']}">
                <img width_bak="225" height_bak="265" data-original="{$_imgSrc}" class="lazy img-responsive"  alt="item4">
            </a>
            <a href="javascript:void(0);" class="button " style="background:transparent;border:none;"></a>
        </div>
    </div>
    <div class="shop-content" style="height: 50px;margin-top: .7rem;">
            <div><a href="/shop/category/product?category_id={$_category['category_id']}" rel="tag">{$_category['category_name']}</a></div>
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