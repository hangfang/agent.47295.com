<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class ProductController extends BasicController{
    /**
     * 商品详情
     * @return boolean
     */
    public function detailAction(){
        $productId = $this->_request->getQuery('product_id');
        if(!$productId){
            header('location: /shop/index/notfound');exit;
        }
        
        if(!$product = Kissbaby_ProductModel::getRow(['product_id'=>$productId])){
            header('location: /shop/index/notfound');exit;
        }
        
        $product['product_description'] = str_replace('{CDN_URL}', CDN_URL, $product['product_description']);
        
        Kissbaby_ProductModel::update(['product_views'=>++$product['product_views']], ['product_id'=>$productId]);
        
        $this->_view->assign('title', $product['product_name']);
        $this->_view->assign('product', $product);
        $this->_view->assign('related', Kissbaby_ProductModel::getList(['category_id'=>$product['category_id'], 'product_id!='=>$productId], '*', '0,4'));
        $this->_view->assign('category', Kissbaby_CategoryModel::getRow(['category_id'=>$product['category_id']]));
        return true;
    }
    
    /**
     * 新品到货
     * @return boolean
     */
    public function latestAction(){
        $this->_view->assign('title', '新品到货');
        $this->_view->assign('latestProduct', Kissbaby_LatestProductModel::getList([], '*', '0,12'));
        return true;
    }
}