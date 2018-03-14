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
            if($this->_request->isXmlHttpRequest()){
                lExit(502, '请求非法');
            }
            
            header('location: /shop/index/succ?title=错误&msg=非法请求&detail=/shop/activity/index');exit;
        }
        
        if(!$activity = Kissbaby_ActivityModel::getRow(['activity_id'=>$activityId])){
            if($this->_request->isXmlHttpRequest()){
                lExit(502, '活动数据丢失...');
            }
            
            header('location: /shop/index/succ?title=错误&msg=活动数据丢失...&detail=/shop/activity/index');exit;
        }
        
        $total = Kissbaby_ActivityProductModel::count(['activity_id'=>$activityId]);
        $productList = [];
        if($total){
            $limit = ['limit'=>12];
            $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
            $productList = Kissbaby_ActivityProductModel::getList(['activity_id'=>$activityId], '*', $limit);
        }
        
        $result = ['list'=>$productList, 'total'=>$total];
        
        if($this->_request->isXmlHttpRequest()){
            lExit($result);
        }
        
        if(!$result['list']){
            header('location: /shop/index/succ?title=错误&msg=活动商品数据丢失...&detail=/shop/activity/index');exit;
        }
        
        $this->_view->assign('activity', $activity);
        $this->_view->assign('title', $activity['activity_name']);
        $this->_view->assign('data', $result);
        return true;
    }
    
    /**
     * 活动列表
     * @return boolean
     */
    public function indexAction(){
        $activityList = Kissbaby_ActivityModel::getList(['activity_status'=>1, 'activity_visible'=>1, 'start_time<='=>date('Y-m-d H:i:s'), 'end_time>='=>date('Y-m-d H:i:s')], '*', '', 'activity_order asc');
        
        if($this->_request->isXmlHttpRequest()){
            lExit($activityList);
        }
        
        if(!$activityList){
            header('location: /shop/index/succ?title=错误&msg=活动数据丢失...&detail=/shop/index/index');exit;
        }
        
        $this->_view->assign('title', '限时活动');
        $this->_view->assign('activityList', $activityList);
        return true;
    }
}