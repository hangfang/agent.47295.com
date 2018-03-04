<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class IndexController extends BasicController{
    public function indexAction(){
        $this->_view->assign('title', '精选推荐');
        $this->_view->assign('data', Kissbaby_HomeRecommandProductModel::getList());
        return true;
    }
}