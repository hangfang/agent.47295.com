<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class AccountController extends BasicController{
    public function indexAction(){
        header('location: /shop/index/notfound?code=404&msg=敬请期待...&title=账户中心');exit;
    }
}