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
            header('location: /shop/index/notfound?code=404&title=异常&msg=请求非法');exit;
        }
        
        if(!$product = Kissbaby_ProductModel::getRow(['product_id'=>$productId])){
            header('location: /shop/index/notfound?code=404&title=异常&msg=商品数据丢失...');exit;
        }
        
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
        $total = Kissbaby_ProductModel::count();
        $productList = [];
        if($total){
            $limit = ['limit'=>12];
            $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
            $productList = Kissbaby_ProductModel::getList([], '*', $limit);
        }
        
        $result = ['list'=>$productList, 'total'=>$total];
        
        if($this->_request->isXmlHttpRequest()){
            lExit($result);
        }
        
        $this->_view->assign('title', '新品到货');
        $this->_view->assign('data', $result);
        return true;
    }
}