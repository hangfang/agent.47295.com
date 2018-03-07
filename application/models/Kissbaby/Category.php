<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Kissbaby_CategoryModel extends BaseModel {
    public static $_table = 'category';
    public static $_database = 'kissbaby';
    
    public static function getRow($where = array(), $field = '*', $limit = array(), $order = '', $group = '') {
        $category = parent::getRow($where, $field, $limit, $order, $group);
        if(!empty($category['category_image'])){
            $category['category_image'] = CDN_URL.$category['category_image'];
        }
        
        return $category;
    }
    
    public static function getList($where = array(), $field = '*', $limit = array(), $order = '', $group = '') {
        $categoryList = parent::getList($where, $field, $limit, $order, $group);
        if(!empty($categoryList)){
            foreach($categoryList as &$_category){
                $_category['category_image'] = CDN_URL.$_category['category_image'];
                $_category['category_banner'] = CDN_URL.$_category['category_banner'];
            }
        }

        return $categoryList;
    }
    
    public static function getIndexedList($where = array(), $index='', $field = '*', $limit = array(), $order = '', $group = '') {
        $categoryList = parent::getList($where, $field, $limit, $order, $group);
        
        $id2Category = [];
        if(!empty($categoryList)){
            foreach($categoryList as &$_category){
                $_category['category_image'] = CDN_URL.$_category['category_image'];
                $_category['category_banner'] = CDN_URL.$_category['category_banner'];
                if(empty($id2Category[$_category['category_id']])){
                    $id2Category[$_category['category_id']] = [];
                }
                $id2Category[$_category['category_id']][] = $_category;
            }
        }
        
        return $id2Category;
    }
}