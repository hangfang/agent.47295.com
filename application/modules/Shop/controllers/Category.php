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
            if($this->_request->isXmlHttpRequest()){
                lExit(502, '请求非法');
            }
            
            header('location: /shop/index/succ?title=错误&msg=请求非法&detail=/shop/category/index');exit;
        }
        
        if(!$category = Kissbaby_CategoryModel::getRow(['category_id'=>$categoryId])){
            if($this->_request->isXmlHttpRequest()){
                lExit(502, '分类数据丢失...');
            }
            
            header('location: /shop/index/succ?title=错误&msg=分类数据丢失...&detail=/shop/category/index');exit;
        }
        
        $total = Kissbaby_ProductModel::count(['category_id'=>$categoryId]);
        $productList = [];
        if($total){
            $limit = ['limit'=>12];
            $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
            $productList = Kissbaby_ProductModel::getList(['category_id'=>$categoryId], '*', $limit);
        }
        
        $result = ['list'=>$productList, 'total'=>$total];
        
        if($this->_request->isXmlHttpRequest()){
            lExit($result);
        }
        
        if(!$result['list']){
            header('location: /shop/index/succ?title=错误&msg=商品数据丢失...&detail=/shop/category/index');exit;
        }
        
        $this->_view->assign('category', $category);
        $this->_view->assign('title', $category['category_name']);
        $this->_view->assign('data', $result);
        return true;
    }
    
    /**
     * 父分类列表
     * @return boolean
     */
    public function indexAction(){
        $this->_view->assign('title', '一级分类');
        
        if(!$categoryList = Kissbaby_CategoryModel::getList(['parent_id'=>0], '*', '', 'category_order asc')){
            header('location: /shop/index/succ?title=错误&msg=分类数据丢失...&detail=/shop/category/index');exit;
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
            header('location: /shop/index/succ?title=错误&msg=非法请求&detail=/shop/category/index');exit;
        }
        
        if(!$category = Kissbaby_CategoryModel::getRow(['category_id'=>$categoryId], 'category_name')){
            header('location: /shop/index/succ?title=错误&msg=一级分类数据丢失...&detail=/shop/category/index');exit;
        }
        
        if(!$categoryList = Kissbaby_CategoryModel::getList(['parent_id'=>$categoryId], '*', '', 'category_order asc')){
            header('location: /shop/index/succ?title=错误&msg=二级分类数据丢失...&detail=/shop/category/index');exit;
        }
        
        $this->_view->assign('title', $category['category_name']);
        $this->_view->assign('categoryList', $categoryList);
        return true;
    }
}