<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * 将kissbaby的OSS图片都访问一遍，使CDN生效
 */
class CdnController extends BasicController{
    /**
     * 商品分类图片
     */
    public function categoryAction(){
        $categoryList = Kissbaby_CategoryModel::getList([], 'category_image,category_banner');
        $total = count($categoryList);
        $index = 1;
        foreach($categoryList as $_category){
            if($_category['category_image']){
                file_get_contents(str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_category['category_image']));
                echo $index.'/'.$total.' category_image done'."\n";
            }
            
            if($_category['category_banner']){
                file_get_contents(str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_category['category_banner']));
                echo $index.'/'.$total.' category_banner done'."\n";
            }
            
            $index++;
        }
        
        exit(date('Y-m-d H:i:s').' '.'CDN分类图片成功'."\n");
    }
    
    /**
     * 商品详情的图片
     */
    public function productAction(){
        $productList = Kissbaby_ProductModel::getList([], 'product_image,product_description');
        $total = count($productList);
        $index = 1;
        foreach($productList as $_product){
            if($_product['product_image']){
                $_product['product_image'] = $_product['product_image'] ? explode(',', $_product['product_image']) : [];
                foreach($_product['product_image'] as $_image){
                    file_get_contents(str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_image));
                }
                
                echo $index.'/'.$total.' product_image done'."\n";
            }
            
            if($_product['product_description']){
                if(preg_match_all('/src="([^"]+)"/', $_product['product_description'], $matches)){
                    foreach($matches[1] as $_image){
                        file_get_contents(str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_image));
                    }
                    
                    echo $index.'/'.$total.' product_description done'."\n";
                }
            }
            
            $index++;
        }
        
        exit(date('Y-m-d H:i:s').' '.'CDN商品图片成功'."\n");
    }
    
    /**
     * 活动详情的图片
     */
    public function activityAction(){
        $activityList = Kissbaby_ActivityModel::getList([], 'activity_image');
        $total = count($activityList);
        $index = 1;
        foreach($activityList as $_activity){
            if($_activity['activity_image']){
                file_get_contents(str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_activity['activity_image']));
                echo $index.'/'.$total.' activity_image done'."\n";
            }
            
            $index++;
        }
        echo __FUNCTION__.',CDN活动图片成功'."\n";
    }
    
    /**
     * 活动商品的图片
     */
    public function activityProductAction(){
        $productList = Kissbaby_ActivityProductModel::getList([], 'product_image');
        $total = count($productList);
        $index = 1;
        foreach($productList as $_product){
            if($_product['product_image']){
                file_get_contents(str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_product['product_image']));
                echo $index.'/'.$total.' product_image done'."\n";
            }
            
            $index++;
        }
        echo __FUNCTION__.',CDN活动商品图片成功'."\n";
    }
    
    /**
     * 新品到货图片
     */
    public function latestProductAction(){
        $productList = Kissbaby_LatestProductModel::getList([], 'product_image');
        $total = count($productList);
        $index = 1;
        foreach($productList as $_product){
            if($_product['product_image']){
                file_get_contents(str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_product['product_image']));
                echo $index.'/'.$total.' product_image done'."\n";
            }
            
            $index++;
        }
        echo __FUNCTION__.',CDN新品到货图片成功'."\n";
    }
    
    /**
     * 首页推荐活动图片
     */
    public function recommandActivityAction(){
        $activityList = Kissbaby_HomeRecommandActivityModel::getList([], 'activity_image');
        $total = count($activityList);
        $index = 1;
        foreach($activityList as $_activity){
            if($_activity['activity_image']){
                file_get_contents(str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_activity['activity_image']));
                echo $index.'/'.$total.' activity_image done'."\n";
            }
            
            $index++;
        }
        echo __FUNCTION__.',CDN首页推荐活动图片成功'."\n";
    }
    
    /**
     * 首页推荐商品图片
     */
    public function recommandProductAction(){
        $productList = Kissbaby_HomeRecommandProductModel::getList([], 'product_image');
        $total = count($productList);
        $index = 1;
        foreach($productList as $_product){
            if($_product['product_image']){
                file_get_contents(str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_product['product_image']));
                echo $index.'/'.$total.' product_image done'."\n";
            }
            
            $index++;
        }
        echo __FUNCTION__.',CDN首页推荐商品图片成功'."\n";
    }
}
