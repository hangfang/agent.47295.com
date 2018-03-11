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
            header('location: /shop/index/succ?title=错误&msg=请求非法&detail=/shop/product/index');exit;
        }
        
        if(!$product = Kissbaby_ProductModel::getRow(['product_id'=>$productId])){
            header('location: /shop/index/succ?title=错误&msg=商品数据丢失...&detail=/shop/product/index');exit;
        }
        
        Kissbaby_ProductModel::update(['product_views'=>++$product['product_views']], ['product_id'=>$productId]);
        
        $this->_view->assign('title', $product['product_name']);
        $this->_view->assign('product', $product);
        $this->_view->assign('related', Kissbaby_ProductModel::getList(['category_id'=>$product['category_id'], 'product_id!='=>$productId], '*', '0,4'));
        $this->_view->assign('category', Kissbaby_CategoryModel::getRow(['category_id'=>$product['category_id']]));
        return true;
    }
    
    /**
     * 商品列表
     * @return boolean
     */
    public function indexAction(){
        $categoryList=Kissbaby_CategoryModel::getList(['parent_id'=>0]);
        if(!$categoryList){
            if($this->_request->isXmlHttpRequest()){
                lExit(502, '分类数据不存在');
            }
            header('location: /shop/index/succ?title=错误&msg=分类数据不存在...&detail=/shop/category/index');exit;
        }
        
        $categoryId = $this->_request->getQuery('category_id');
        $subCategoryId = $this->_request->getQuery('sub_category_id');
        
        $subCategoryList = [];
        if($categoryId){
            if(!in_array($categoryId, array_column($categoryList, 'category_id'))){
                if($this->_request->isXmlHttpRequest()){
                    lExit(502, '商品一级分类不存在');
                }
                header('location: /shop/index/succ?title=错误&msg=商品一级分类不存在...&detail=/shop/category/index');exit;
            }
        }
        
        $where = [];
        $categoryId && $where['parent_id'] = $categoryId;
        $subCategoryList = Kissbaby_CategoryModel::getList($where);
        if(!$subCategoryList || ($subCategoryId && !in_array($subCategoryId, array_column($subCategoryList, 'category_id')))){
            if($this->_request->isXmlHttpRequest()){
                lExit(502, '商品二级分类不存在');
            }
            header('location: /shop/index/succ?title=错误&msg=商品二级分类不存在...&detail=/shop/category/subcategory?category_id='.$categoryId);exit;
        }
        
        $where = [];
        if($subCategoryId){
            $where['category_id'] = $subCategoryId;
        }elseif($categoryId){
            $where['category_id'] = array_merge([$categoryId], array_column($subCategoryList, 'category_id'));
        }
            
        $total = Kissbaby_ProductModel::count($where);
        $productList = [];
        if($total){
            $limit = ['limit'=>12];
            $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
            $productList = Kissbaby_ProductModel::getList($where, '*', $limit, 'id asc');
        }
        
        $result = ['list'=>$productList, 'total'=>$total];
        
        if($this->_request->isXmlHttpRequest()){
            lExit($result);
        }
        
        if(!$productList){
            if($categoryId){
                header('location: /shop/index/succ?title=错误&msg=商品数据丢失...&detail=/shop/category/index');exit;
            }
            
            if($subCategoryId){
                header('location: /shop/index/succ?title=错误&msg=商品数据丢失...&detail=/shop/category/subcategory?category_id='.$subCategoryId);exit;
            }
        }
        
        $title = '';
        if($categoryId || $subCategoryId){
            foreach($subCategoryList as $_subCategory){
                if($subCategoryId == $_subCategory['category_id']){
                    $title = '商品列表-'.$_subCategory['category_name'];
                    break;
                }
            }
            
            if(!$title){
                foreach($categoryList as $_category){
                    if($categoryId == $_category['category_id']){
                        $title = '商品列表-'.$_category['category_name'];
                        break;
                    }
                }
            }
        }else{
            $title = '商品列表';
        }

        $this->_view->assign('title', $title);
        $this->_view->assign('category', $categoryList);
        $this->_view->assign('subCategory', $subCategoryList);
        $this->_view->assign('categoryId', $categoryId);
        $this->_view->assign('subCategoryId', $subCategoryId);
        $this->_view->assign('data', $result);
        return true;
    }
}