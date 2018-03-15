<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class IndexController extends BasicController{
    public function indexAction(){
        $total = Kissbaby_HomeRecommandProductModel::count();
        $homeRecommandProduct = [];
        if($total){
            $limit = ['limit'=>10];
            $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
            $homeRecommandProduct = Kissbaby_HomeRecommandProductModel::getList([], 'product_id', $limit);
            
            $homeRecommandProduct = Kissbaby_ProductModel::getList(['product_id'=>array_column($homeRecommandProduct, 'product_id')], '*');
        }
        
        $result = ['list'=>$homeRecommandProduct, 'total'=>$total];
        if($this->_request->isXmlHttpRequest()){
            lExit($result);
        }
        
        $this->_view->assign('title', '精选推荐');
        $this->_view->assign('data', $result);
        return true;
    }
    
    public function notFoundAction(){
        $this->_view->assign('code', empty($tmp=$this->_request->getQuery('code')) ? '404' : $tmp);
        $this->_view->assign('msg', empty($tmp=$this->_request->getQuery('msg')) ? '页面发生错误' : $tmp);
        $this->_view->assign('title', empty($tmp=$this->_request->getQuery('title')) ? '页面发生错误' : $tmp);
        return true;
    }
    
    public function succAction(){
        $this->_view->assign('href', empty($tmp=$this->_request->getQuery('href')) ? BASE_URL : $tmp);
        $this->_view->assign('msg', empty($tmp=$this->_request->getQuery('msg')) ? '操作成功' : $tmp);
        $this->_view->assign('title', empty($tmp=$this->_request->getQuery('title')) ? '操作成功' : $tmp);
        $this->_view->assign('btn', empty($tmp=$this->_request->getQuery('btn')) ? '确定' : $tmp);
        $this->_view->assign('detail', empty($tmp=$this->_request->getQuery('detail')) ? BASE_URL : $tmp);
        return true;
    }
}