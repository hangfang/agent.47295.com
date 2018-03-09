<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class CategoryController extends BasicController{
    /**
     * 分类商品
     * @return boolean
     */
    public function productAction(){
        $categoryId = $this->_request->getQuery('category_id');
        if(!$categoryId){
            header('location: /shop/index/notfound?code=404&title=异常&msg=请求非法');exit;
        }
        
        if(!$category = Kissbaby_CategoryModel::getRow(['category_id'=>$categoryId])){
            header('location: /shop/index/notfound?code=404&title=异常&msg=分类数据丢失...');exit;
        }
        
        $limit = ['limit'=>12];
        $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
        $productList = Kissbaby_ProductModel::getList(['category_id'=>$categoryId], '*', $limit);
            
        if($this->_request->isXmlHttpRequest()){
            lExit($productList);
        }
        
        if(!$productList){
            header('location: /shop/index/notfound?code=404&title=异常&msg=商品数据丢失...');exit;
        }
        
        $this->_view->assign('category', $category);
        $this->_view->assign('title', $category['category_name']);
        $this->_view->assign('productList', $productList);
        return true;
    }
    
    /**
     * 父分类列表
     * @return boolean
     */
    public function indexAction(){
        $this->_view->assign('title', '一级分类');
        
        if(!$categoryList = Kissbaby_CategoryModel::getList(['parent_id'=>0], '*', '', 'category_order asc')){
            header('location: /shop/index/notfound?code=404&title=异常&msg=分类数据丢失...');exit;
        }
        
        $this->_view->assign('categoryList', $categoryList);
        return true;
    }
    
    /**
     * 子分类列表
     * @return boolean
     */
    public function subCategoryAction(){
        $categoryId = $this->_request->getQuery('category_id');
        if(!$categoryId){
            header('location: /shop/index/notfound?code=404&title=异常&msg=非法请求');exit;
        }
        
        if(!$categoryList = Kissbaby_CategoryModel::getList(['parent_id'=>$categoryId], '*', '', 'category_order asc')){
            header('location: /shop/index/notfound?code=404&title=异常&msg=分类数据丢失...');exit;
        }
        
        $this->_view->assign('title', '二级分类');
        $this->_view->assign('categoryList', $categoryList);
        return true;
    }
}