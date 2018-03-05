<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include $viewPath.'header.php';
?>
<div class="single_top">
	 <div class="container"> 
	  <div class="error-404 text-center">
			<h1><?php echo $code;?></h1>
			<p><?php echo $msg;?></p>
			<a class="b-home" href="/shop/index/index">回到首页</a>
		  </div>
      </div>
</div>
<?php include $viewPath.'footer.php';?>