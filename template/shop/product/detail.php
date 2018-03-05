<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include $viewPath.'header.php';
?>
<style>
    .menu_drop img {width:25.1rem; height:25.1rem;clear:both;display:block;}
</style>
<div class="single_top">
	 <div class="container"> 
	      <div class="single_grid">
				<div class="grid images_3_of_2">
						<ul id="etalage">
                            <?php
                                $_index = 0;
                                foreach($product['product_image'] as $_image){
                                    if($_index == 0){
                                        $_extraPre = '<a href="javascript:void(0);">';
                                        $_extraPost = '</a>';
                                    }
                                    
                                    echo <<<EOF
<li>
    {$_extraPre}
    <img class="etalage_thumb_image" src="{$_image}" class="img-responsive" />
    <img class="etalage_source_image" src="{$_image}" class="img-responsive" title="" />
    {$_extraPost}
</li>
EOF;
                                }
                            ?>
						</ul>
                        <div class="clearfix"></div>		
				  </div> 
				  <div class="desc1 span_3_of_2">
				  	<ul class="back">
                        <li><i class="back_arrow"> </i>Back to <a href="/shop/category/product?category_id=<?php echo $product['category_id'];?>"><?php echo empty($category['category_name']) ? '未知分类' : $category['category_name'];?></a></li>
                    </ul>
					<h1><?php echo $product['product_name'];?></h1>
					<ul class="price_single">
					  <li class="head"><h2>$<?php echo $product['product_vip_price'];?></h2></li>
					  <li class="head_desc"><a href="javascript:void(0);"><?php echo $product['product_views'];?> reviews</a><img src="<?php echo $staticDir;?>images/review.png" alt=""/></li>
					  <div class="clearfix"></div>
					</ul>
					<p style="display:none;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum</p>
				     <div class="dropdown_top">
				       <div class="dropdown_left">
					     <select class="dropdown" tabindex="10" data-settings='{"wrapperClass":"metro1"}'>
	            			<option value="0">规格：<?php echo $product['product_model'];?></option>	
			             </select>
			            </div>
			            <ul class="color_list">
							<li><a href="javascript:void(0)">销量: <span class="color1"><?php echo $product['product_purchased'];?></span>件</a></li>
						</ul>
                         
						 <div class="clearfix"></div>
                         
                         <div class="dropdown_left" style="visibility:hidden;">
                         <select class="dropdown" tabindex="10" data-settings='{"wrapperClass":"metro1"}'>
	            			<option value="0">规格：<?php echo $product['product_model'];?></option>	
			             </select>
                        </div>
                         <ul class="color_list">
							<li><a href="javascript:void(0);" class="btn1 btn2 btn-primary1"><span>加入购物车</span></a></li>
						</ul>
						 <div class="clearfix"></div>
			         </div>
				</div>
          	    <div class="clearfix"></div>
          	   </div>
          	 <div class="single_social_top" style="display:none;">   
          	  <ul class="single_social" style="display:none;">
				  <li><a href="#"> <i class="s_fb"> </i> <div class="social_desc">Share<br> on facebook</div><div class="clearfix"> </div></a></li>
				  <li><a href="#"> <i class="s_twt"> </i> <div class="social_desc">Tweet<br> this product</div><div class="clearfix"> </div></a></li>
				  <li><a href="#"> <i class="s_google"> </i><div class="social_desc">Google+<br> this product</div><div class="clearfix"> </div></a></li>
				  <li class="last"><a href="#"> <i class="s_email"> </i><div class="social_desc">Email<br> a Friend</div><div class="clearfix"> </div></a></li>
			  </ul>
			 </div>
			 <div class="menu_drop" style="margin-top:1.8rem;">
                <?php echo $product['product_description'];?>
	 		</div>
   </div>
    <?php if($related){?>
   <h3 class="m_2">猜你喜欢</h3>
    <div class="container">
        <div class="box_3">
            <?php
                foreach($related as $_related){
                    echo <<<EOF
<div class="col-md-3">
<div class="content_box"><a href="/shop/product/detail?product_id={$_related['product_id']}">
<img data-original="{$_related['product_image'][0]}" class="lazy img-responsive" alt="">
</a>
</div>
<h4><a href="/shop/product/detail?product_id={$_related['product_id']}">{$_related['product_name']}</a></h4>
<p>$ {$_related['product_vip_price']}</p>
</div>
EOF;
                }
            ?>
            <div class="clearfix"> </div>
        </div>
    </div>
    <?php } ?>
</div>
<script>
    jQuery(document).ready(function($){
        $('#etalage').etalage({
            thumb_image_width: 300,
            thumb_image_height: 400,
            source_image_width: 900,
            source_image_height: 1200,
            show_hint: true,
            click_callback: function(image_anchor, instance_id){
//alert('Callback example:\nYou clicked on an image with the anchor: "'+image_anchor+'"\n(in Etalage instance: "'+instance_id+'")');
            },
            change_callback:function(){
                
            }
        });

    });
</script>
<?php include $viewPath.'footer.php';?>