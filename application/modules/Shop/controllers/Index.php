<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class IndexController extends BasicController{
    public function indexAction(){
        $limit = ['limit'=>12];
        $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
        $homeRecommandProduct = Kissbaby_HomeRecommandProductModel::getList([], '*', $limit);
        
        if($this->_request->isXmlHttpRequest()){
            lExit($homeRecommandProduct);
        }
        
        $this->_view->assign('title', '精选推荐');
        $this->_view->assign('homeRecommandProduct', $homeRecommandProduct);
        return true;
    }
    
    public function notFoundAction(){
        $this->_view->assign('code', empty($tmp=$this->_request->getQuery('code')) ? '404' : $tmp);
        $this->_view->assign('msg', empty($tmp=$this->_request->getQuery('msg')) ? '页面发生错误' : $tmp);
        $this->_view->assign('title', empty($tmp=$this->_request->getQuery('title')) ? '页面发生错误' : $tmp);
        return true;
    }
}