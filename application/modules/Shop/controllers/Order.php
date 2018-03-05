<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class OrderController extends BasicController{
    public function indexAction(){
        $this->_view->assign('code', '404');
        $this->_view->assign('msg', '敬请期待...');
        $this->_view->assign('title', '订单中心');
        return true;
    }
}