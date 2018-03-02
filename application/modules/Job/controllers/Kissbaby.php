<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

//获取分类列表
define('CATEGORY_LIST', 'http://kiss.api.niaobushi360.com/index.php?route=module/category&device=android&version=111');
//获取商品列表
define('PRODUCT_LIST', 'http://kiss.api.niaobushi360.com/index.php?route=product/search&device=android&version=111&keyword=%s&tcid=&cid=%s&bid=popular&sort=popular&order=desc&page=0&limit=20');
//获取商品详情
define('PRODUCT_DETAIL', 'http://kiss.api.niaobushi360.com/index.php?route=product/product/appGetProductInfo&product_id=%s&device=android&version=111');
define('H5_HTTP_SERVER', 'http://7xl26a.com2.z0.glb.qiniucdn.com/image/');
define('IMAGE_PATH', BASE_PATH.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'kissbaby'.DIRECTORY_SEPARATOR);
define('IMAGE_URL', BASE_URL.'/upload/kissbaby/');
class KissbabyController extends BasicController{
    /**
     * 从kissbaby更新商品分类
     */
    public function getCategoryAction(){
        $categoryList = http(['url'=>CATEGORY_LIST]);
        if(!$categoryList){
            log_message('error', '从kissbaby获取分类列表失败');
            exit('get category from kissbaby failed...'."\n");
        }
        
        $now = time();
        $db = Database::getInstance('kissbaby');
        $db->startTransaction();
        foreach($categoryList as $_cate){
            $_banner = empty($_cate['banner'][0]['image']) ? '' : str_replace('\\', '/', $_cate['banner'][0]['image']);
            if($_banner){
                $this->saveImage($_banner);
            }
            
            $_replace = [
                'parent_id'         =>  $_cate['parent_id'],
                'category_id'       =>  $_cate['category_id'],
                'category_name'     =>  $_cate['name'],
                'category_order'    =>  $_cate['sort_order'],
                'category_image'    =>  '',
                'category_banner'   =>  $_banner,
                'create_time'       =>  $now,
                'ts'                =>  date('Y-m-d H:i:s', $now)
            ];
            
            if(!Kissbaby_CategoryModel::replace($_replace)){
                $db->rollBack();
                log_message('error', $msg = __FUNCTION__.', 更新父分类失败. replace:'.print_r($_replace, true));
                exit(date('Y-m-d H:i:s', $now).' '.$msg."\n");
            }
            
            foreach($_cate['children'] as $_subCate){
                $_image = empty($_subCate['image']) ? '' : str_replace('\\', '/', $_subCate['image']);
                if($_image){
                    $this->saveImage($_image);
                }
            
                $_replace = [
                    'parent_id'         =>  $_subCate['parent_id'],
                    'category_id'       =>  $_subCate['category_id'],
                    'category_name'     =>  $_subCate['name'],
                    'category_order'    =>  $_subCate['sort_order'],
                    'category_image'    =>  $_image,
                    'category_banner'   =>  '',
                    'create_time'       =>  $now,
                    'ts'                =>  date('Y-m-d H:i:s', $now)
                ];
            
                if(!Kissbaby_CategoryModel::replace($_replace)){
                    $db->rollBack();
                    log_message('error', $msg = __FUNCTION__.', 更新子分类失败. replace:'.print_r($_replace, true));
                    exit(date('Y-m-d H:i:s', $now).' '.$msg."\n");
                }
            }
        }
        
        $db->commit();
        exit(date('Y-m-d H:i:s', $now).' '.'更新分类成功'."\n");
    }
    
    /**
     * 从kissbaby获取商品信息
     */
    public function getGoodsInfoAction(){
        $categoryList = Kissbaby_CategoryModel::getList();
        foreach($categoryList as $_cate){
            $goodsList = http($_tmp=['url'=>sprintf(PRODUCT_LIST, $_cate['category_name'], $_cate['category_id'])]);
            if(!$goodsList){
                log_message('error', '从kissbaby获取商品列表失败');
                echo 'get goods list from kissbaby failed...'."\n";
                continue;
            }
            
            if(empty($goodsList['product']) || empty($goodsList['total'])){
                log_message('error', 'kissbaby分类下没有商品, url:'.$_tmp['url']);
                echo 'goods list empty..., url:'.$_tmp['url']."\n";
                continue;
            }
            log_message('error', print_r($goodsList, true));exit;
        }
    }
    
    /**
     * 保存kissbaby图片
     * @param string $_imagePath 图片路径
     * @return boolean
     */
    private function __saveImage($_imagePath){
        $_path = explode('/', $_imagePath);
        $_fileName = array_pop($_path);
        $_path = IMAGE_PATH.implode('/', $_path);
        if(!is_dir($_path)){
            mkdir($_path, 0755, true);
        }

        $_tmp = explode('.', $_fileName);
        $rt = file_put_contents($_path.DIRECTORY_SEPARATOR.md5($_fileName).'.'.array_pop($_tmp), file_get_contents(constant('H5_HTTP_SERVER').$_imagePath));
        if(!$rt){
            log_message('error', __FUNCTION__.', 保存kissbaby图片失败, path:'.$_imagePath);
            return false;
        }
        
        return true;
    }
}