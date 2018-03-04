<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class ProductController extends BasicController{
    public function detailAction(){
        $productId = $this->_request->getQuery('product_id');
        if(!$productId){
            header('location: /shop/index/nofound');exit;
        }
        
        if(!$product = Kissbaby_ProductModel::getRow(['product_id'=>$productId])){
            header('location: /shop/index/nofound');exit;
        }
        $this->_view->assign('title', '商品详情');
        $this->_view->assign('product', $product);
        $this->_view->assign('related', Kissbaby_ProductModel::getList(['category_id'=>$product['category_id']], '*', '0,4'));
        $this->_view->assign('category', Kissbaby_CategoryModel::getRow(['category_id'=>$product['category_id']]));
        return true;
    }
}