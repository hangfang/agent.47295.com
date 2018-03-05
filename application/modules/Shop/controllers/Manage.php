<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class ManageController extends BasicController{
    public function orderListAction(){
        header('location: /shop/index/notfound?code=404&msg=敬请期待...&title=订单管理');exit;
    }
}