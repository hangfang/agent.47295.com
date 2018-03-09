<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $title;?></title>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo KISSBABY_KEYWORD;?>" />
<meta name="description" content="<?php echo KISSBABY_DESCRIPTION;?>" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="<?php echo $staticDir;?>css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- Custom Theme files -->
<link href="<?php echo $staticDir;?>css/style.css?v=<?php echo date('Y-m-d H:i:s');?>" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<!--webfont-->
<!--<link href='http://fonts.useso.com/css?family=Lato:100,200,300,400,500,600,700,800,900' rel='stylesheet' type='text/css'>-->
<script type="text/javascript" src="<?php echo $staticDir;?>js/jquery-1.11.1.min.js"></script>
<script src="<?php echo $staticDir;?>js/jquery.lazyload.js"></script>
<!-- start menu -->
<script src="<?php echo $staticDir;?>js/jquery.easydropdown.js"></script>
<link href="<?php echo $staticDir;?>css/megamenu.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo $staticDir;?>js/megamenu.js"></script>
<script>$(document).ready(function(){$(".megamenu").megamenu();});</script>
<link rel="stylesheet" href="<?php echo $staticDir;?>css/etalage.css">
<script src="<?php echo $staticDir;?>js/jquery.etalage.min.js"></script>
</head>
<body>
<script>
    var STATIC_CDN_URL = '<?php echo STATIC_CDN_URL;?>';
    var IMG_CDN_URL = '<?php echo IMG_CDN_URL;?>';
</script>
<div class="header_top">
	<div class="container">
		<div class="one-fifth column row_1">
			<span class="selection-box"><select class="domains valid" name="domains">
		       <option>中文</option>
		    </select></span>
         </div>
         <div class="cssmenu">
			<ul>
			    <li class="active"><a href="/shop/product/search">热门搜索</a></li> 
			    <li class="active" style="position:relative;">
                    <a href="javascript:void(0);" id="shopping-cart">购物车
                    <div class="col-xs-3 header-bottom-right" style="margin: 0px;z-index: 9999;display: none;position: absolute;top:-8px;left:-5px;">
                        <ul class="icon1 sub-icon1 profile_img">
                             <li><a class="active-icon c1" href="javascript:void(0);" style="transform: scale(0.5);display:none;"> </a>
                                <ul class="sub-icon1 list">
                                  <h3>我的宝贝</h3>
                                  <div class="shopping_cart">
                                      <div class="cart_box">
                                         <div class="message">
                                             <div class="alert-close"> </div> 
                                            <div class="list_img"><img src="<?php echo $staticDir;?>images/14.jpg" class="img-responsive" alt=""/></div>
                                            <div class="list_desc"><h4><a href="#">velit esse molestie</a></h4>1 x<span class="actual">
                                             $12.00</span></div>
                                              <div class="clearfix"></div>
                                          </div>
                                        </div>
                                        <div class="cart_box1">
                                          <div class="message1">
                                             <div class="alert-close1"> </div> 
                                            <div class="list_img"><img src="<?php echo $staticDir;?>images/15.jpg" class="img-responsive" alt=""/></div>
                                            <div class="list_desc"><h4><a href="#">velit esse molestie</a></h4>1 x<span class="actual">
                                             $12.00</span></div>
                                              <div class="clearfix"></div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="total">
                                        <div class="total_left">总价 : </div>
                                        <div class="total_right">$250.00</div>
                                        <div class="clearfix"> </div>
                                    </div>
                                    <div class="login_buttons">
                                      <div class="check_button"><a href="javascript:void(0);">清空</a></div>
                                      <div class="login_button"><a href="/shop/order/cart">下单</a></div>
                                      <div class="clearfix"></div>
                                    </div>
                                  <div class="clearfix"></div>
                                </ul>
                             </li>
                        </ul>
                    </div></a>
                </li> 
			    <li class="active"><a href="/shop/account/index">我的账户</a></li> 
			</ul>
		 </div>
	</div>
</div>	
<div class="wrap-box"></div>
<div class="header_bottom">
    <div class="container">
        <div class="col-xs-9 header-bottom-left">
            <div class="col-xs-2 logo">
                <h1><a href="/shop/index/index"><span>琳玲</span>港货</a></h1>
            </div>
            <div class="col-xs-7 menu">
                <ul class="megamenu skyblue">
                    <li class="<?php echo $moduleName=='shop' && $controllerName=='index' && $actionName=='index' ? 'active' : '';?> grid"><a class="color1" href="/shop/index/index">精品推荐</a></li>
                    <li class="<?php echo $moduleName=='shop' && $controllerName=='product' && $actionName=='latest' ? 'active' : '';?> grid" style="display:none;"><a class="color2" href="/shop/product/latest">新品到货</a></li>
                    <li class="<?php echo $moduleName=='shop' && $controllerName=='activity' ? 'active' : '';?> grid"><a class="color4" href="/shop/activity/index">限时活动</a></li>				
                    <li class="<?php echo $moduleName=='shop' && $controllerName=='category' ? 'active' : '';?> grid"><a class="color5" href="/shop/category/index">商品分类</a></li>
                </ul> 
            </div>
		</div>
        <div class="clearfix"></div>
	 </div>
</div>