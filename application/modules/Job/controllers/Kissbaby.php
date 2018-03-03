<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

//获取分类列表
define('CATEGORY_LIST', 'http://kiss.api.niaobushi360.com/index.php?route=module/category&device=android&version=111');
//获取商品列表
define('PRODUCT_LIST', 'http://kiss.api.niaobushi360.com/index.php?route=product/search&device=android&version=111&keyword=%s&tcid=&cid=%s&bid=popular&sort=popular&order=desc&page=%&limit=%s');
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
    public function getProductDetailAction(){
        $categoryList = Kissbaby_CategoryModel::getList();
        foreach($categoryList as $_cate){
            $_total = 100;
            $_limit = 20;
            $_page = 0;
            do{
                echo 'category start..., name:'.$_cate['category_name'].' page:'.$_page."\n";
                $productList = http($_tmpCate=['url'=>sprintf(PRODUCT_LIST, $_cate['category_name'], $_cate['category_id'], $_page, $_limit)]);
                if(!$productList){
                    log_message('error', '从kissbaby获取商品列表失败');
                    echo 'get goods list from kissbaby failed...'."\n";
                    break;
                }

                if(empty($productList['product']) || empty($productList['total'])){
                    log_message('error', 'kissbaby分类下没有商品, url:'.$_tmpCate['url']);
                    echo 'goods list empty..., url:'.$_tmpCate['url']."\n";
                    break;
                }
                
                $_total = $productList['total'];
                //log_message('error', print_r($goodsList, true));exit;
                foreach($productList['product'] as $_prouct){
                    $detail = http($_tmpPrd=['url'=>sprintf(PRODUCT_DETAIL, $_prouct['product_id'])]);
                    if(!$detail){
                        log_message('error', '从kissbaby获取商品详情失败');
                        echo '   get goods list from kissbaby failed...'."\n";
                        continue;
                    }

                    if(empty($detail['product'])){
                        log_message('error', 'kissbaby商品没有详情, url:'.$_tmpPrd['url']);
                        echo '   product detail empty..., url:'.$_tmpPrd['url']."\n";
                        continue;
                    }
                    
                    
                    $detail = $detail['product'];
                    
                    if(!empty($detail['images'])){
                        foreach($detail['images'] as $_image){
                            $this->__saveImage(str_replace('image/',  '', trim($_image, '/')));
                        }
                        
                        $detail['image'] = implode(',', $detail['images']);
                    }else{
                        $this->__saveImage(str_replace('image/',  '', trim($detail['image'], '/')));
                    }
                    
                    if(!empty($detail['description'])){
                        if(preg_match_all('/src\="([^"]+)"/', $detail['description'], $matches)){
                            $detail['description'] = [];
                            foreach($matches[1] as $_image){
                                //log_message('error', print_r(parse_url($_image), true));exit;
                                $_path = str_replace('//image/', '', parse_url($_image, PHP_URL_PATH));
                                $_path = str_replace('/image/',  '', $_path);
                                $this->__saveImage($_path);
                                $detail['description'][] = $_path;
                            }
                            
                            $detail['description'] = implode(',', $detail['description']);
                        }
                    }
                    
                    $_update = [
                        'category_id'   =>  $_cate['category_id'],
                        'product_id'   =>  $detail['product_id'],
                        'product_name'   =>  $detail['name'],
                        'product_image'   =>  $detail['image'],
                        'product_description'   =>  empty($detail['description']) ? '' : $detail['description'],
                        'product_sale_price'   =>  $detail['sale_price'],
                        'product_vip_price'   =>  $detail['vip_price'],
                        'product_tag'   =>  $detail['tag'],
                        'product_model'   =>  $detail['model'],
                        'product_purchased'   =>  $detail['purchased'],
                        'create_time'   =>  empty($detail['date_added']) ? time() : strtotime($detail['date_added']),
                        'ts'   =>  empty($detail['date_modified']) ? date('Y-m-d H:i:s') : $detail['date_modified'],
                    ];
                    
                    if(Kissbaby_ProductModel::getRow(['product_id'=>$detail['product_id']], 'product_id')){
                        if(false===Kissbaby_ProductModel::update($_update, $_where=['product_id'=>$detail['product_id']])){
                            log_message('error', '更新kissbaby商品详情失败, update:'.print_r($_update, true).', where:'.print_r($_where, true).', url:'.print_r($_tmpPrd['url'], true));
                            echo '   update kissbaby product detail tailed..., url:'.$_tmpPrd['url']."\n";
                            continue;
                        }
                        
                        echo 'update kissbaby product detail succ..., name:'.$detail['name']."\n";
                    }else{
                        if(!Kissbaby_ProductModel::insert($_update)){
                            log_message('error', '插入kissbaby商品详情失败, insert:'.print_r($_update, true).', url:'.print_r($_tmpPrd['url'], true));
                            echo '   insert kissbaby product detail tailed..., url:'.$_tmpPrd['url']."\n";
                            continue;
                        }
                        
                        echo '  insert kissbaby product detail succ..., name:'.$detail['name']."\n";
                    }
                }
                
                echo 'category succ..., name:'.$_cate['category_name'].' page:'.$_page."\n";
                echo '-----------------------------------------------'."\n";
                $_page++;
            }while($_total>$_page*$_limit);
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
        if(!file_exists($_path)){
            mkdir($_path, 0755, true);
        }

        $_tmp = explode('.', $_fileName);
        try{
            $ch = curl_init(H5_HTTP_SERVER.$_imagePath);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $_content = curl_exec($ch);
        }catch(Exception $e){
            log_message('error', __FUNCTION__.', 获取kissbaby图片失败, url:'.H5_HTTP_SERVER.$_imagePath);
            return true;
        }
        
        $rt = file_put_contents($_path = $_path.DIRECTORY_SEPARATOR.md5($_fileName).'.'.array_pop($_tmp), $_content);
        if(!$rt){
            log_message('error', __FUNCTION__.', 保存kissbaby图片失败, save path:'. $_path .' url:'.$_imagePath);
            return false;
        }
        
        return true;
    }
}