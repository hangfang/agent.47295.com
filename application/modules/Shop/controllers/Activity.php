<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class ActivityController extends BasicController{
    /**
     * 活动商品
     * @return boolean
     */
    public function productAction(){
        $activityId = $this->_request->getQuery('activity_id');
        if(!$activityId){
            header('location: /shop/index/notfound?code=404&title=异常&msg=请求非法');exit;
        }
        
        if(!$activity = Kissbaby_ActivityModel::getRow(['activity_id'=>$activityId])){
            header('location: /shop/index/notfound?code=404&title=异常&msg=活动数据丢失...');exit;
        }
        
        $limit = ['limit'=>12];
        $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
        $productList = Kissbaby_ActivityProductModel::getList(['activity_id'=>$activityId], '*', $limit);
            
        if($this->_request->isXmlHttpRequest()){
            lExit($productList);
        }
        
        if(!$productList){
            header('location: /shop/index/notfound?code=404&title=异常&msg=活动商品数据丢失...');exit;
        }
        
        
        $this->_view->assign('activity', $activity);
        $this->_view->assign('title', $activity['activity_name']);
        $this->_view->assign('productList', $productList);
        return true;
    }
    
    /**
     * 活动列表
     * @return boolean
     */
    public function indexAction(){
        $limit = ['limit'=>12];
        $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
        $activityList = Kissbaby_ActivityModel::getList(['activity_status'=>1, 'activity_visible'=>1, 'start_time<='=>date('Y-m-d H:i:s'), 'end_time>='=>date('Y-m-d H:i:s')], '*', $limit, 'activity_order asc');
        
        if($this->_request->isXmlHttpRequest()){
            lExit($activityList);
        }
        
        if(!$activityList){
            header('location: /shop/index/notfound?code=404&title=异常&msg=活动数据丢失...');exit;
        }
        
        $this->_view->assign('title', '限时活动');
        $this->_view->assign('activityList', $activityList);
        return true;
    }
}