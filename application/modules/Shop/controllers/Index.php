<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class IndexController extends BasicController{
    public function indexAction(){
        $this->_view->assign('title', '精选推荐');
        $this->_view->assign('homeRecommandProduct', Kissbaby_HomeRecommandProductModel::getList());
        return true;
    }
    
    public function notFoundAction(){
        $this->_view->assign('code', '404');
        $this->_view->assign('msg', '页面发生错误');
        $this->_view->assign('title', '页面发生错误');
        return true;
    }
}