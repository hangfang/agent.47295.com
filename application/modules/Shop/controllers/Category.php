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
            header('location: /shop/index/notfound');exit;
        }
        
        if(!$productList = Kissbaby_ProductModel::getList(['category_id'=>$categoryId])){
            header('location: /shop/index/notfound');exit;
        }
        
        
        $this->_view->assign('category', $category = Kissbaby_CategoryModel::getRow(['category_id'=>$categoryId]));
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
        $this->_view->assign('categoryList', Kissbaby_CategoryModel::getList(['parent_id'=>0], '*', '', 'category_order asc'));
        return true;
    }
    
    /**
     * 子分类列表
     * @return boolean
     */
    public function subCategoryAction(){
        $categoryId = $this->_request->getQuery('category_id');
        if(!$categoryId){
            header('location: /shop/index/notfound');exit;
        }
        
        $this->_view->assign('title', '二级分类');
        $this->_view->assign('categoryList', Kissbaby_CategoryModel::getList(['parent_id'=>$categoryId], '*', '', 'category_order asc'));
        return true;
    }
}