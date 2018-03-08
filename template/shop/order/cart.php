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
	<h3 class="m_1" style="display:none;">新品到货</h3>
	<div class="container" id="container">
        <div class="box_1">
            <div class="col-md-7-bak">
                <div class="col_1_of_3 span_1_of_3">
                     <div class="shop-holder">
                          <div class="cart-img">
                             <a href="/shop/product/detail?product_id=2253">
                                 <img width_bak="225" height_bak="265" data-original="data/JPgoods/2253\9f8316123ef70c4da8f8300b2d72ca59.jpg" src="http://agent.47295.com/static/shop/images/default.png" class="lazy img-responsive" alt="item4">
                             </a>
                             <a href="javascript:void(0);" class="button "></a>
                         </div>
                     </div>
                     <div class="shop-content" style="height: 50px;margin-top: .7rem;">
                         <div><a href="/shop/product/detail?product_id=2253" rel="tag">环保驱蚊剂</a></div>
                         <h3><a href="/shop/product/detail?product_id=2253">Non-charac</a></h3>
                         <span><span class="amount">会员价:$13.64</span></span>
                     </div>
                 </div>	
             </div>
            <div class="clearfix"></div>
         </div>
     </div>
</div>

<script>
    $(function(){
        $(document).scroll(function(){
            var tmp = $(document).height() / ($(document).scrollTop() + window.innerHeight);
            if(tmp < 1.05 ){
                console.log(tmp);
            }  
        });
    })
</script>
<?php include $viewPath.'footer.php';?>