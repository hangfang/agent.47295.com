<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class AccountController extends BasicController{
    public function indexAction(){
        $this->_view->assign('title', '账户中心');
        return true;
    }
}