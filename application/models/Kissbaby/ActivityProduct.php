<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Kissbaby_ActivityProductModel extends BaseModel {
    public static $_table = 'activity_product';
    public static $_database = 'kissbaby';
    
    public static function getRow($where = array(), $field = '*', $limit = array(), $order = '', $group = '') {
        $product = parent::getRow($where, $field, $limit, $order, $group);
        if(!empty($product['product_image'])){
            $product['product_image'] = CDN_URL.$product['product_image'];
        }
        
        return $product;
    }
    
    public static function getList($where = array(), $field = '*', $limit = array(), $order = '', $group = '') {
        $productList = parent::getList($where, $field, $limit, $order, $group);
        if(!empty($productList)){
            foreach($productList as &$_product){
                $_product['product_image'] = CDN_URL.$_product['product_image'];
            }
        }

        return $productList;
    }
    
    public static function getIndexedList($where = array(), $index='', $field = '*', $limit = array(), $order = '', $group = '') {
        $productList = parent::getList($where, $field, $limit, $order, $group);
        
        $id2Product = [];
        if(!empty($productList)){
            foreach($productList as &$_product){
                $_product['product_image'] = CDN_URL.$_product['product_image'];
                if(empty($id2Product[$_product['product_id']])){
                    $id2Product[$_product['product_id']] = [];
                }
                $id2Product[$_product['product_id']][] = $_product;
            }
        }
        
        return $id2Product;
    }
}