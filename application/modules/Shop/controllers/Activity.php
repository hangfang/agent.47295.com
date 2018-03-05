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
            header('location: /shop/index/notfound');exit;
        }
        
        if(!$productList = Kissbaby_ActivityProductModel::getList(['activity_id'=>$activityId])){
            header('location: /shop/index/notfound');exit;
        }
        
        
        $this->_view->assign('title', '活动商品');
        $this->_view->assign('productList', $productList);
        return true;
    }
    
    /**
     * 活动列表
     * @return boolean
     */
    public function indexAction(){
        $this->_view->assign('title', '限时活动');
        $this->_view->assign('activityList', Kissbaby_ActivityModel::getList(['activity_status'=>1, 'activity_visible'=>1, 'start_time<='=>date('Y-m-d H:i:s'), 'end_time>='=>date('Y-m-d H:i:s')], '*', '', 'activity_order asc'));
        return true;
    }
}