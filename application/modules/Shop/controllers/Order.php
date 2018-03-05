<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class OrderController extends BasicController{
    public function indexAction(){
        header('location: /shop/index/notfound?code=404&msg=敬请期待...&title=订单中心');exit;
    }
}