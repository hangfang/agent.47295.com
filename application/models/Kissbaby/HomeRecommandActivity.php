<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Kissbaby_HomeRecommandActivityModel extends BaseModel {
    public static $_table = 'home_recommand_activity';
    public static $_database = 'kissbaby';
    
//    public static function getRow($where = array(), $field = '*', $limit = array(), $order = '', $group = '') {
//        $activity = parent::getRow($where, $field, $limit, $order, $group);
//        if(!empty($activity['activity_image'])){
//            $activity['activity_image'] = IMG_CDN_URL.$activity['activity_image'];
//        }
//        
//        return $activity;
//    }
//    
//    public static function getList($where = array(), $field = '*', $limit = array(), $order = '', $group = '') {
//        $activityList = parent::getList($where, $field, $limit, $order, $group);
//        if(!empty($activityList)){
//            foreach($activityList as &$_activity){
//                $_activity['activity_image'] = IMG_CDN_URL.$_activity['activity_image'];
//            }
//        }
//
//        return $activityList;
//    }
//    
//    public static function getIndexedList($where = array(), $index='', $field = '*', $limit = array(), $order = '', $group = '') {
//        $activityList = parent::getList($where, $field, $limit, $order, $group);
//        
//        $id2Activity = [];
//        if(!empty($activityList)){
//            foreach($activityList as &$_activity){
//                $_activity['activity_image'] = IMG_CDN_URL.$_activity['activity_image'];
//                if(empty($id2Activity[$_activity['activity_id']])){
//                    $id2Activity[$_activity['activity_id']] = [];
//                }
//                $id2Activity[$_activity['activity_id']][] = $_activity;
//            }
//        }
//        
//        return $id2Activity;
//    }
}