<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Kissbaby_ProductModel extends BaseModel {
    public static $_table = 'product';
    public static $_database = 'kissbaby';
    
//    public static function getRow($where = array(), $field = '*', $limit = array(), $order = '', $group = '') {
//        $product = parent::getRow($where, $field, $limit, $order, $group);
//        if(!empty($product['product_image'])){
//            $product['product_image'] = explode(',', $product['product_image']);
//            foreach($product['product_image'] as &$_image){
//                $_image = IMG_CDN_URL.$_image;
//            }
//            
//            $product['product_description'] = str_replace('{CDN_URL}', IMG_CDN_URL, $product['product_description']);
//        }
//        
//        return $product;
//    }
//    
//    public static function getList($where = array(), $field = '*', $limit = array(), $order = '', $group = '') {
//        $productList = parent::getList($where, $field, $limit, $order, $group);
//        if(!empty($productList)){
//            foreach($productList as &$_product){
//                $_product['product_image'] = explode(',', $_product['product_image']);
//                foreach($_product['product_image'] as &$_image){
//                    $_image = IMG_CDN_URL.$_image;
//                }
//                
//                $_product['product_description'] = str_replace('{CDN_URL}', IMG_CDN_URL, $_product['product_description']);
//            }
//        }
//
//        return $productList;
//    }
//    
//    public static function getIndexedList($where = array(), $index='', $field = '*', $limit = array(), $order = '', $group = '') {
//        $productList = parent::getList($where, $field, $limit, $order, $group);
//        
//        $id2Product = [];
//        if(!empty($productList)){
//            foreach($productList as &$_product){
//                $_product['product_image'] = explode(',', $_product['product_image']);
//                foreach($_product['product_image'] as &$_image){
//                    $_image = IMG_CDN_URL.$_image;
//                }
//                
//                
//                $_product['product_description'] = str_replace('{CDN_URL}', IMG_CDN_URL, $_product['product_description']);
//                if(empty($id2Product[$_product['product_id']])){
//                    $id2Product[$_product['product_id']] = [];
//                }
//                $id2Product[$_product['product_id']][] = $_product;
//            }
//        }
//        
//        return $id2Product;
//    }
}