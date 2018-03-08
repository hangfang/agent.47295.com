<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

//获取分类列表
define('CATEGORY_LIST', 'http://kiss.api.niaobushi360.com/index.php?route=module/category&device=android&version=111');
//获取商品列表
define('PRODUCT_LIST', 'http://kiss.api.niaobushi360.com/index.php?route=product/search&device=android&version=111&keyword=%s&tcid=&cid=%s&bid=popular&sort=popular&order=desc&page=%s&limit=%s');
//获取商品详情
define('PRODUCT_DETAIL', 'http://kiss.api.niaobushi360.com/index.php?route=product/product/appGetProductInfo&product_id=%s&device=android&version=111');
//新品到货
define('LATEST_PRODUCT', 'http://kiss.api.niaobushi360.com/index.php?route=product/search&device=android&version=111&keyword=&tcid=&cid=&bid=date_available&sort=date_available&order=desc&page=%s&limit=%s');
//首页推荐
define('HOME_RECOMMAND', 'http://kiss.api.niaobushi360.com/index.php?route=module/special/appGetHomeInfoNew&device=android&version=111');
//活动列表
define('ACTIVITY_LIST', 'http://kiss.api.niaobushi360.com/index.php?route=module/special/appGetSalesNew&limit=20&page=0&device=android&version=111');
//活动商品列表
define('ACTIVITY_PRODUCT_LIST', 'http://kiss.api.niaobushi360.com/index.php?route=product/sale/getSaleInfo&sale_id=%s&sort=popular&order=desc&page=%s&limit=%s&device=android&version=111');

//图片的域名
define('H5_HTTP_SERVER', 'http://7xl26a.com2.z0.glb.qiniucdn.com/image/');
define('H5_HTTP_SERVER_', 'http://7xl26a.com2.z0.glb.qiniucdn.com//image/');
define('IMAGE_PATH', BASE_PATH.DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'kissbaby'.DIRECTORY_SEPARATOR);
define('IMAGE_URL', BASE_URL.'/upload/kissbaby/');

class KissbabyController extends BasicController{
    private static $_OSS_CLIENT = null;
    public static function getOssInstance(){
        if(self::$_OSS_CLIENT instanceof Oss_Client){
            return self::$_OSS_CLIENT;
        }
        
        return self::$_OSS_CLIENT = new Oss_Client(OSS_ACCESS_ID, OSS_ACCESS_KEY, OSS_ENDPOINT);
    }
    
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
        foreach($categoryList as $_cate){
            $_banner = empty($_cate['banner'][0]['image']) ? '' : str_replace('\\', '/', $_cate['banner'][0]['image']);
            if($_banner){
                $this->__saveImage($_banner);
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
            
            $_state = true;
            if(!Kissbaby_CategoryModel::getRow(['category_id'=>$_cate['category_id']])){
                if(!Kissbaby_CategoryModel::insert($_replace)){
                    log_message('error', $msg = __FUNCTION__.', 插入父分类失败. replace:'.print_r($_replace, true));
                    echo date('Y-m-d H:i:s', $now).' '.$msg."\n";
                    $_state = false;
                }
            }else{
                unset($_replace['create_time']);
                if(!Kissbaby_CategoryModel::update($_replace, ['category_id'=>$_cate['category_id']])){
                    log_message('error', $msg = __FUNCTION__.', 插更新父分类失败. replace:'.print_r($_replace, true));
                    echo date('Y-m-d H:i:s', $now).' '.$msg."\n";
                    $_state = false;
                }
            }
            
            if($_state){
                echo 'update kissbaby category succ..., name:'.$_cate['name']."\n";
            }
            
            foreach($_cate['children'] as $_subCate){
                if($_subCate['image']){
                    $this->__saveImage($_subCate['image']);
                }
            
                $_replace = [
                    'parent_id'         =>  $_subCate['parent_id'],
                    'category_id'       =>  $_subCate['category_id'],
                    'category_name'     =>  $_subCate['name'],
                    'category_order'    =>  $_subCate['sort_order'],
                    'category_image'    =>  $_subCate['image'],
                    'category_banner'   =>  '',
                    'create_time'       =>  $now,
                    'ts'                =>  date('Y-m-d H:i:s', $now)
                ];
                
                $_state = true;
                if(!Kissbaby_CategoryModel::getRow(['category_id'=>$_subCate['category_id']])){
                    if(!Kissbaby_CategoryModel::insert($_replace)){
                        log_message('error', $msg = __FUNCTION__.', 插入父分类失败. replace:'.print_r($_replace, true));
                        echo date('Y-m-d H:i:s', $now).' '.$msg."\n";
                        $_state = false;
                    }
                }else{
                    unset($_replace['create_time']);
                    if(!Kissbaby_CategoryModel::update($_replace, ['category_id'=>$_subCate['category_id']])){
                        log_message('error', $msg = __FUNCTION__.', 插更新父分类失败. replace:'.print_r($_replace, true));
                        echo date('Y-m-d H:i:s', $now).' '.$msg."\n";
                        $_state = false;
                    }
                }
                
                if($_state){
                    echo 'update kissbaby sub category succ..., name:'.$_subCate['name']."\n";
                }
            }
        }
        
        exit(date('Y-m-d H:i:s', $now).' '.'更新分类成功'."\n");
    }
    
    /**
     * 将kissbaby商品详情的图片替换到oss
     */
    public function updateProductImgAction(){
        $limit = 10;
        $offset = 0;
        $total = count(Kissbaby_ProductModel::count());
        do{
            $productList = Kissbaby_ProductModel::getList([], 'id,product_name,product_image,product_description', ['limit'=>$limit, 'offset'=>$offset>0 ? $offset+1 : $offset]);
            foreach($productList as $_product){
                
                if(!empty($_product['product_image'])){
                    foreach($_product['product_image'] as &$_image){
                        if(strpos($_image, H5_HTTP_SERVER)!==false || strpos($_image, H5_HTTP_SERVER_)!==false){
                            $_image = str_replace(H5_HTTP_SERVER, '', $_image);
                            $_image = str_replace(H5_HTTP_SERVER_, '', $_image);
                            $this->__saveImage($_image);
                        }
                    }

                    $_product['product_image'] = implode(',', $_product['product_image']);
                }else{
                    $_product['product_image'] = '';
                }

                if(!empty($_product['product_description']) &&  strpos($_product['product_description'], '{CDN_URL}')===false && preg_match_all('/src\="([^"]+)"/i', $_product['product_description'], $matches)){
                    for($i=1,$len=count($matches[1]); $i<$len; $i++){
                        $matches[1][$i] = str_replace(H5_HTTP_SERVER, '', $matches[1][$i]);
                        $matches[1][$i] = str_replace(H5_HTTP_SERVER_, '', $matches[1][$i]);
                        $this->__saveImage($matches[1][$i]);
                    }
                    unset($matches);
                    
                    $_product['product_description'] = str_replace('src=', 'class="lazy" data-original=', $_product['product_description']);
                    $_product['product_description'] = str_replace(H5_HTTP_SERVER, '{CDN_URL}', $_product['product_description']);
                    $_product['product_description'] = str_replace(H5_HTTP_SERVER_, '{CDN_URL}', $_product['product_description']);
                }else{
                    $_product['product_description'] = empty($_product['product_description']) ? '' : $_product['product_description'];
                }

                if(!Kissbaby_ProductModel::update($_update=['product_image'=>$_product['product_image'], 'product_description'=>$_product['product_description']], $_where=['id'=>$_product['id']])){
                    log_message('error', __FUNCTION__.', 更新商品图片为oss地址失败, update:'.print_r($_update, true).', where:'.print_r($_where, true));
                    echo $offset.'/'.$total.'  product update failed..., name:'.$_product['product_name']."\n";
                }else{
                    echo $offset.'/'.$total.'  product update succ..., name:'.$_product['product_name']."\n";
                }
                
                unset($_product, $_update, $_where);
                $offset++;
            }
            
            unset($productList);
        }while($offset+1<$total);
    }
    
    /**
     * 从kissbaby获取商品信息
     */
    public function getProductAction(){
        $categoryList = Kissbaby_CategoryModel::getList();
        foreach($categoryList as $_cate){
            $_total = 100;
            $_limit = 20;
            $_page = 0;
            $_number = 0;
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
                foreach($productList['product'] as $_product){
                    $detail = http($_tmpPrd=['url'=>sprintf(PRODUCT_DETAIL, $_product['product_id'])]);
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
                    
                    if($_product=Kissbaby_ProductModel::getRow(['product_id'=>$detail['product_id']], 'product_id,product_image,product_description')){
                        
                        if(!empty($detail['description'])){
                            $detail['description'] = str_replace('src=', 'class="lazy" data-original=', $detail['description']);
                            $detail['description'] = str_replace(H5_HTTP_SERVER, '{CDN_URL}', $detail['description']);
                            $detail['description'] = str_replace(H5_HTTP_SERVER_, '{CDN_URL}', $detail['description']);
                            
                            if($detail['description']==$_product['product_description']){
                                echo ' product_description not changed...'."\n";
                                unset($_update['product_description']);
                            }
                        }
                        
                        if($detail['image']==$_product['product_image']){
                            echo ' product_image not changed...'."\n";
                            unset($_update['product_image']);
                        }
                        
                        if(false===Kissbaby_ProductModel::update($_update, $_where=['product_id'=>$detail['product_id']])){
                            log_message('error', '更新kissbaby商品详情失败, update:'.print_r($_update, true).', where:'.print_r($_where, true).', url:'.print_r($_tmpPrd['url'], true));
                            echo '   update kissbaby product detail failed..., url:'.$_tmpPrd['url']."\n";
                            continue;
                        }
                        
                        echo 'update kissbaby product detail succ..., name:'.$detail['name']."\n";
                    }else{
                    
                        $_update['product_image'] = empty($_update['product_image']) ? [] : explode(',', $_update['product_image']);
                        foreach($_update['product_image'] as &$_image){
                            if(strpos($_image, H5_HTTP_SERVER)===false && strpos($_image, H5_HTTP_SERVER_)===false){
                                $_image = H5_HTTP_SERVER.$_image;
                            }
                        }
                        $_update['product_image'] = implode(',', $_update['product_image']);
                        
                        if(!Kissbaby_ProductModel::insert($_update)){
                            log_message('error', '插入kissbaby商品详情失败, insert:'.print_r($_update, true).', url:'.print_r($_tmpPrd['url'], true));
                            echo '   insert kissbaby product detail failed..., url:'.$_tmpPrd['url']."\n";
                            continue;
                        }
                        
                        echo '  insert kissbaby product detail succ..., name:'.$detail['name']."\n";
                    }
                }
                
                echo 'category succ..., name:'.$_cate['category_name'].' page:'.$_page."\n";
                echo '-----------------------------------------------'."\n";
                $_page++;
                $_number += $_limit;
            }while($_total>$_number);
        }
        echo __FUNCTION__.',按分类更新商品成功'."\n";
    }
    
    /**
     * 从kissbaby获取商品信息
     */
    public function getSingleProductAction(){
        $productId = $this->_request->getParam('product_id');
        if(!$productId){
            log_message('error', '参数错误, product_id不能为空');
            echo '参数错误, product_id不能为空'."\n";
            exit;
        }
        
        $detail = http($_tmpPrd=['url'=>sprintf(PRODUCT_DETAIL, $productId)]);
        if(!$detail){
            log_message('error', '从kissbaby获取商品详情失败');
            echo 'get product detail from kissbaby failed...'."\n";
            exit;
        }

        if(empty($detail['product'])){
            log_message('error', 'kissbaby商品没有详情, url:'.$_tmpPrd['url']);
            echo 'product detail empty..., url:'.$_tmpPrd['url']."\n";
            exit;
        }


        $detail = $detail['product'];

        if(!empty($detail['images'])){
            foreach($detail['images'] as &$_image){
                $this->__saveImage($_image);
            }

            $detail['image'] = implode(',', $detail['images']);
        }else{
            $this->__saveImage($detail['image']);
        }
        
        if(!empty($detail['description']) && preg_match_all('/src\="([^"]+)"/i', $detail['description'], $matches)){
            for($i=1,$len=count($matches[1]); $i<$len; $i++){
                $matches[1][$i] = str_replace(H5_HTTP_SERVER, '', $matches[1][$i]);
                $matches[1][$i] = str_replace(H5_HTTP_SERVER_, '', $matches[1][$i]);
                $this->__saveImage($matches[1][$i]);
            }
            
            $detail['description'] = str_replace('src=', 'class="lazy" data-original=', $detail['description']);
            $detail['description'] = str_replace(H5_HTTP_SERVER, '{CDN_URL}', $detail['description']);
            $detail['description'] = str_replace(H5_HTTP_SERVER_, '{CDN_URL}', $detail['description']);
        }else{
            $detail['description'] = '';
        }

        $_update = [
            'category_id'   =>  0,
            'product_id'   =>  $detail['product_id'],
            'product_name'   =>  $detail['name'],
            'product_image'   =>  $detail['image'],
            'product_description'   => $detail['description'],
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
                echo '   update kissbaby product detail failed..., url:'.$_tmpPrd['url']."\n";
                exit;
            }

            echo 'update kissbaby product detail succ..., name:'.$detail['name']."\n";
        }else{
            if(!Kissbaby_ProductModel::insert($_update)){
                log_message('error', '插入kissbaby商品详情失败, insert:'.print_r($_update, true).', url:'.print_r($_tmpPrd['url'], true));
                echo '   insert kissbaby product detail failed..., url:'.$_tmpPrd['url']."\n";
                exit;
            }

            echo '  insert kissbaby product detail succ..., name:'.$detail['name']."\n";
        }
        echo __FUNCTION__.',按单个商品成功'."\n";
    }
    
    /**
     * 从kissbaby获取新品到货列表
     */
    public function getLatestProductAction(){
        if(false===Kissbaby_LatestProductModel::delete()){
            log_message('error', '删除kissbaby新品到货失败');
            echo '   delete kissbaby latest product failed...'."\n";
            exit;
        }
                
        $_total = 100;
        $_limit = 20;
        $_page = 0;
        $_number = 0;
        do{
            echo 'latest product start..., page:'.$_page."\n";
            $productList = http($_tmp=['url'=>sprintf(LATEST_PRODUCT, $_page, $_limit)]);
            if(!$productList){
                log_message('error', '从kissbaby获取新品到货列表失败');
                echo 'get latest product from kissbaby failed...'."\n";
                break;
            }

            if(empty($productList['product']) || empty($productList['total'])){
                log_message('error', 'kissbaby新品到货下没有商品, url:'.$_tmp['url']);
                echo 'latest product list empty..., url:'.$_tmp['url']."\n";
                break;
            }
            
            $_total = $productList['total'];
            //log_message('error', print_r($goodsList, true));exit;
            foreach($productList['product'] as $_product){
                if(!empty($_product['image'])){
                    $this->__saveImage($_product['image']);
                }
                    
                $_update = [
                    'product_id'   =>  $_product['product_id'],
                    'product_name'   =>  $_product['name'],
                    'product_image'   =>  empty($_product['image']) ? '' : $_product['image'],
                    'product_sale_price'   =>  $_product['sale_price'],
                    'product_vip_price'   =>  $_product['vip_price'],
                    'product_tag'   =>  $_product['tag'],
                    'product_purchased'   =>  $_product['purchased'],
                    'create_time'   =>  time(),
                    'ts'   =>  date('Y-m-d H:i:s'),
                ];

                if(!Kissbaby_LatestProductModel::insert($_update)){
                    log_message('error', '插入kissbaby新品到货失败, insert:'.print_r($_update, true));
                    echo '   insert kissbaby latest product failed..., name:'.$_product['name']."\n";
                    continue;
                }

                echo '  insert kissbaby latest product succ..., name:'.$_product['name']."\n";
            }
            
            echo 'latest product succ..., page:'.$_page."\n";
            echo '-----------------------------------------------'."\n";
            $_page++;
            $_number += $_limit;
        }while($_total>$_number);
        echo __FUNCTION__.',更新新品到货成功'."\n";
    }
    
    /**
     * 从kissbaby获取首页推荐
     */
    public function getHomeRecommandAction(){
        $recommand = http($_tmp=['url'=>sprintf(HOME_RECOMMAND)]);
        if(!$recommand){
            log_message('error', '从kissbaby获取首页推荐失败');
            echo 'get home recommand from kissbaby failed...'."\n";
            exit;
        }

        if(empty($recommand['banner']) && empty($recommand['single_product_list'])){
            log_message('error', '首页推荐kissbaby没有商品和banner, url:'.$_tmp['url']);
            echo 'home recommand empty..., url:'.$_tmp['url']."\n";
            exit;
        }

        $_update = [];
        $banner = $recommand['banner'] ? $recommand['banner'] : [];
        foreach($banner as $_banner){
            $_banner = $_banner[0];
            if(empty($_banner['image']) || !preg_match('/sale_id\=(\d+)$/', $_banner['url'], $matches)){
                continue;
            }
            
            $this->__saveImage($_banner['image']);
            
            $_update[] = [
                'activity_id'       =>  $matches[1],
                'activity_image'    =>  $_banner['image'],
                'create_time'   =>  time(),
                'ts'   =>  date('Y-m-d H:i:s'),
            ];
        }
        
        if(false===Kissbaby_HomeRecommandActivityModel::delete()){
            log_message('error', '删除kissbaby首页推荐活动失败');
            echo 'delete kissbaby home recommand activity failed...'."\n";
        }else if(false===Kissbaby_HomeRecommandActivityModel::batchInsert($_update)){
            log_message('error', '插入kissbaby首页推荐活动失败');
            echo 'insert kissbaby home recommand activity failed...'."\n";
        }else{
            echo 'insert kissbaby home recommand activity succ...'."\n";
        }

        $_update = [];
        foreach($recommand['single_product_list'] as $_product){
            if(!empty($_product['image'])){
                $this->__saveImage($_product['image']);
            }

            $_update[] = [
                'product_id'   =>  $_product['product_id'],
                'product_name'   =>  $_product['short_name'],
                'product_image'   =>  empty($_product['image']) ? '' : $_product['image'],
                'product_sale_price'   =>  $_product['sale_price'],
                'product_vip_price'   =>  $_product['vip_price'],
                'create_time'   =>  time(),
                'ts'   =>  date('Y-m-d H:i:s'),
            ];
        }

        if(false===Kissbaby_HomeRecommandProductModel::delete()){
            log_message('error', '删除kissbaby首页推荐商品失败');
            echo 'delete kissbaby home recommand product failed...'."\n";
        }else if(false===Kissbaby_HomeRecommandProductModel::batchInsert($_update)){
            log_message('error', '插入kissbaby首页推荐商品失败');
            echo 'insert kissbaby home recommand product failed...'."\n";
        }else{
            echo 'insert kissbaby home recommand product succ...'."\n";
        }
        
        echo __FUNCTION__.',更新首页推荐成功'."\n";
    }
    
    /**
     * 从kissbaby获取活动列表
     */
    public function getActivityAction(){
        $activityList = http($_tmp=['url'=>ACTIVITY_LIST]);
        foreach($activityList['sales'] as $_activity){
            if(!empty($_activity['banner_lg'])){
                $this->__saveImage($_activity['banner_lg']);
            }

            $_update = [
                'activity_id'   =>  $_activity['sale_id'],
                'activity_name'   =>  $_activity['name'],
                'activity_image'   =>  empty($_activity['banner_lg']) ? '' : $_activity['banner_lg'],
                'start_time'   =>  $_activity['date_start'],
                'end_time'   =>  $_activity['date_end'],
                'activity_status'   =>  $_activity['status'],
                'activity_visible'   =>  $_activity['visible'],
                'activity_order'   =>  $_activity['sort_order'],
                'create_time'   =>  time(),
                'ts'            =>  date('Y-m-d H:i:s')
            ];
                
            if(!Kissbaby_ActivityModel::getRow(['activity_id'=>$_activity['sale_id']], 'activity_id')){
                if(false===Kissbaby_ActivityModel::insert($_update)){
                    log_message('error', '删除kissbaby活动失败');
                    echo 'insert kissbaby activity failed...'."\n";
                    continue;
                }
            }else{
                if(false===Kissbaby_ActivityModel::update($_update, ['activity_id'=>$_activity['sale_id']])){
                    log_message('error', '更新kissbaby活动失败');
                    echo 'update kissbaby activity failed...'."\n";
                }
            }

            $_total = 100;
            $_limit = 20;
            $_page = 0;
            $_number = 0;
            do{
                echo 'activity start..., activity_id:'.$_activity['sale_id'].' page:'.$_page."\n";
                $productList = http($_tmpPrd=['url'=>sprintf(ACTIVITY_PRODUCT_LIST, $_activity['sale_id'], $_page, $_limit)]);
                if(!$productList){
                    log_message('error', '从kissbaby获取活动商品列表失败');
                    echo 'get activity product list from kissbaby failed...'."\n";
                    break;
                }

                if(empty($productList['sale']['products'])){
                    log_message('error', 'kissbaby活动下没有商品, url:'.$_tmpPrd['url']);
                    echo 'activity product list empty..., url:'.$_tmpPrd['url']."\n";
                    break;
                }
                
                //log_message('error', print_r($goodsList, true));exit;
                foreach($productList['sale']['products'] as $_product){
                    if(!empty($_product['image'])){
                        $this->__saveImage($_product['image']);
                    }
                    
                    $_update = [
                        'activity_id'   =>  $_activity['sale_id'],
                        'product_id'   =>  $_product['product_id'],
                        'product_name'   =>  $_product['name'],
                        'product_image'   =>  $_product['image'],
                        'product_sale_price'   =>  $_product['sale_price'],
                        'product_vip_price'   =>  $_product['vip_price'],
                        'create_time'   =>  time(),
                        'ts'   =>  date('Y-m-d H:i:s')
                    ];
                    
                    if(Kissbaby_ActivityProductModel::getRow($_where=['product_id'=>$_product['product_id'], 'activity_id'=>$_activity['sale_id']], 'product_id')){
                        if(false===Kissbaby_ActivityProductModel::update($_update, $_where)){
                            log_message('error', '更新kissbaby活动商品失败, update:'.print_r($_update, true).', where:'.print_r($_where, true));
                            echo '   update kissbaby activity product failed..., name:'.$_product['name']."\n";
                            continue;
                        }
                        
                        echo 'update kissbaby product detail succ..., name:'.$_product['name']."\n";
                    }else{
                        if(!Kissbaby_ActivityProductModel::insert($_update)){
                            log_message('error', '插入kissbaby活动商品失败, insert:'.print_r($_update, true));
                            echo '   insert kissbaby activity product detail failed..., name:'.$_product['name']."\n";
                            continue;
                        }
                        
                        echo '  insert kissbaby activity product detail succ..., name:'.$_product['name']."\n";
                    }
                }
                
                echo 'activity succ..., name:'.$_activity['name'].' page:'.$_page."\n";
                echo '-----------------------------------------------'."\n";
                $_page++;
                $_number += $_limit;
            }while($_total>$_number);
        }
        echo __FUNCTION__.',更新活动成功'."\n";
    }
    
    /**
     * 保存kissbaby图片
     * @param string $_imagePath 图片路径
     * @return boolean
     */
    private function __saveImage(&$_imagePath){
        if(empty($_imagePath)){
            return true;
        }
        
        $_path = explode('/', $_imagePath);
        $_fileName = array_pop($_path);
        $_path = implode('/', $_path);
        
        
        $_path = preg_replace('/[^0-9a-zA-Z_\/\.\\\]/', '', $_path);
        $_path = preg_replace('/\s/', '', $_path);
        $_path = IMAGE_PATH.$_path;
        $_path = str_replace('\\', '/', $_path);
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
        
        $rt = file_put_contents($_path = $_path.'/'.md5($_fileName).'.'.array_pop($_tmp), $_content);
        if(!$rt){
            log_message('error', __FUNCTION__.', 保存kissbaby图片失败, save path:'. $_path .' url:'.$_imagePath);
            return false;
        }
        
        $_imagePath = str_replace(str_replace(['\\'], ['/'], IMAGE_PATH), '', $_path);
        
        self::getOssInstance()->uploadFile(OSS_BUCKET, 'upload/kissbaby/'.$_imagePath, $_path);
        return true;
    }
}
