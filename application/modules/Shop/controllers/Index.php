<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class IndexController extends BasicController{
    public function indexAction(){
        $data = [];
        $this->_view->assign('title', '新品到货');
        $this->_view->assign('data', $data);
        return true;
    }
}