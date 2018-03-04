<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $title;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo KISSBABY_KEYWORD;?>" />
<meta name="description" content="<?php echo KISSBABY_DESCRIPTION;?>" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="<?php echo $staticDir;?>css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- Custom Theme files -->
<link href="<?php echo $staticDir;?>css/style.css?v=2018030322" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<!--webfont-->
<!--<link href='http://fonts.useso.com/css?family=Lato:100,200,300,400,500,600,700,800,900' rel='stylesheet' type='text/css'>-->
<script type="text/javascript" src="<?php echo $staticDir;?>js/jquery-1.11.1.min.js"></script>
<!-- start menu -->
<script src="<?php echo $staticDir;?>js/jquery.easydropdown.js"></script>
<link href="<?php echo $staticDir;?>css/megamenu.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo $staticDir;?>js/megamenu.js"></script>
<script>$(document).ready(function(){$(".megamenu").megamenu();});</script>
<link rel="stylesheet" href="<?php echo $staticDir;?>css/etalage.css">
<script src="<?php echo $staticDir;?>js/jquery.etalage.min.js"></script>
</head>
<body>
<div class="header_top">
	<div class="container">
		<div class="one-fifth column row_1">
			<span class="selection-box"><select class="domains valid" name="domains">
		       <option>English</option>
		       <option>French</option>
		       <option>German</option>
		    </select></span>
         </div>
         <div class="cssmenu">
			<ul>
			    <li class="active"><a href="login.html">My Account</a></li> 
			</ul>
		 </div>
	</div>
</div>	
<div class="wrap-box"></div>
<div class="header_bottom">
    <div class="container">
        <div class="col-xs-9 header-bottom-left">
            <div class="col-xs-2 logo">
                <h1><a href="/agent/index/index"><span>琳玲</span>Dai购</a></h1>
            </div>
            <div class="col-xs-7 menu">
                <ul class="megamenu skyblue">
                    <li class="active grid"><a class="color1" href="index.html">精品推荐</a></li>
                    <li class="grid"><a class="color2" href="#">热销爆款</a></li>
                    <li><a class="color4" href="about.html">限时活动</a></li>				
                    <li><a class="color5" href="404.html">商品分类</a></li>
                    <li><a class="color6" style="display:none;" href="contact.html">更多</a></li>
                </ul> 
            </div>
		</div>
	    <div class="col-xs-3 header-bottom-right">
	       <ul class="icon1 sub-icon1 profile_img">
					 <li><a class="active-icon c1" href="#"> </a>
						<ul class="sub-icon1 list">
						  <h3>Recently added items(2)</h3>
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
	                        	<div class="total_left">CartSubtotal : </div>
	                        	<div class="total_right">$250.00</div>
	                        	<div class="clearfix"> </div>
	                        </div>
                            <div class="login_buttons">
							  <div class="check_button"><a href="checkout.html">Check out</a></div>
							  <div class="login_button"><a href="login.html">Login</a></div>
							  <div class="clearfix"></div>
						    </div>
					      <div class="clearfix"></div>
						</ul>
					 </li>
		      </ul>
              <div class="search">	  
				<input type="text" name="s" class="textbox" value="Search" onFocus="this.value = '';" onBlur="if (this.value == '') {this.value = 'Search';}">
				<input type="submit" value="Subscribe" id="submit" name="submit">
				<div id="response"> </div>
		     </div>
             <div class="clearfix"></div>
          </div>
        <div class="clearfix"></div>
	 </div>
</div>