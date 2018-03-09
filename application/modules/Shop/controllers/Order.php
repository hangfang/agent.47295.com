<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class OrderController extends BasicController{
    /**
     * 订单列表
     */
    public function indexAction(){
        $limit = ['limit'=>12];
        $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
        
        $where = [];
        if($_SESSION['user']['user_type']=='customer'){
            $where['user_id'] = $_SESSION['user']['id'];
        }
        
        $orderStime = $this->_request->getPost('order_stime');
        if(!empty($orderStime)){
            $where['create_time>='] = strtotime($orderStime);
        }
        
        $orderEtime = $this->_request->getPost('order_etime');
        if(!empty($orderEtime)){
            $where['create_time<='] = strtotime($orderEtime.' 23:59:59');
        }
        
        $orderStatus = $this->_request->getPost('order_status');
        if(!empty($orderStatus)){
            $where['order_status'] = $orderStatus;
        }
        
        $orderList = Kissbaby_OrderModel::getList($where, '*', $limit, 'id desc');
        
        if($this->_request->isXmlHttpRequest()){
            lExit($orderList);
        }
        
        if($orderList){
            header('location: /shop/index/notfound?code=404&msg=异常...&title=订单数据丢失...');exit;
        }
        
        $this->_view->assign('title', '订单中心');
        $this->_view->assign('orderList', $orderList);
        return true;
    }
    
    /**
     * 订单详情
     */
    public function detailAction(){
        $orderCode = $this->_request->getQuery('order_code');
        if(empty($orderCode)){
            header('location: /shop/index/notfound?code=404&title=异常&msg=请求非法');exit;
        }
        
        if(!$order=Kissbaby_OrderModel::getRow(['order_code'=>$orderCode])){
            header('location: /shop/index/notfound?code=404&title=异常&msg=订单数据丢失...');exit;
        }
        
        $orderProduct = Kissbaby_OrderProductModel::getList(['order_id'=>$order['id']]);
        if($order['order_status']!=='INIT'){
            header('location: /shop/index/notfound?code=404&title=异常&msg=订单明细丢失...');exit;
        }
        
        $this->_view->assign('title', '订单中心');
        $this->_view->assign('order', $order);
        $this->_view->assign('orderProduct', $orderProduct);
        return true;
    }
    
    /**
     * 创建订单、订单新增商品
     */
    public function addAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }
        
        $orderCode = $this->_request->getPost('order_code');
        if(!empty($orderCode) && $order=Kissbaby_OrderModel::getRow(['order_code'=>$orderCode])){
            lExit(502, '订单不存在');
        }
        
        $userId = $this->_request->getPost('user_id');
        if(empty($userId) && empty($orderCode) && $_SESSION['user']['user_type']==='admin'){
            lExit(502, '请指定购买人');
        }else{
            $userId = $_SESSION['user']['id'];
        }
        
        $productId = $this->_request->getPost('product_id');
        if(empty($productId)){
            lExit(502, '商品不存在');
        }
        
        if(!$product=Kissbaby_ProductModel::getRow(['id'=>$productId])){
            lExit(502, '商品不存在');
        }
        
        $productNum = $this->_request->getPost('product_num');
        if(empty($productNum) || $productNum<=0){
            lExit(502, '购买数量错误');
        }
        
        $tmp = explode(',', $product['product_image']);
        $productImage = empty($tmp[0]) ? '' : $tmp[0];
        
        $db = Database::getInstance('kissbaby');
        $db->startTransaction();
        if(!$order){
            $insert = [
                'order_code'            =>  Kissbaby_OrderModel::getOrderCode(),
                'order_cost_money'      =>  bcmul($product['product_vip_price'], $productNum, 2),
                'order_discount_money'  =>  0,
                'order_image'           =>  $productImage,
                'order_origin_money'    =>  bcmul($product['product_sale_price'], $productNum, 2),
                'order_product_num'     =>  $productNum,
                'order_real_money'      =>  bcmul($product['product_sale_price'], $productNum, 2),
                'order_status'          =>  'INIT',
                'user_id'               =>  $userId,
                'create_time'           =>  time()
            ];
            if(!$orderId = Kissbaby_OrderModel::insert($insert)){
                $db->rollBack();
                lExit(500, '下单失败,请稍后再试...');
            }

            $insert = [
                'product_cost_money'    =>  $product['product_vip_price'],
                'product_image'         =>  $productImage,
                'product_name'          =>  $product['product_name'],
                'product_num'           =>  $productNum,
                'product_real_money'    =>  $product['product_sale_price'],
                'product_sale_money'    =>  $product['product_sale_price'],
                'order_id'              =>  $orderId,
                'product_id'            =>  $product['product_id'],
                'create_time'           =>  time()
            ];
            if(!$orderProductId = Kissbaby_OrderProductModel::insert($insert)){
                $db->rollBack();
                lExit(500, '下单失败,请稍后再试...');
            }
        }else{
            $insert = [
                'product_cost_money'    =>  bcmul($product['product_vip_price'], $productNum, 2),
                'product_image'         =>  $productImage,
                'product_name'          =>  $product['product_name'],
                'product_num'           =>  $productNum,
                'product_real_money'    =>  bcmul($product['product_sale_price'], $productNum, 2),
                'product_sale_money'    =>  bcmul($product['product_sale_price'], $productNum, 2),
                'order_id'              =>  $order['id'],
                'product_id'            =>  $product['product_id'],
                'create_time'           =>  time()
            ];
            if(!$orderProductId = Kissbaby_OrderProductModel::insert($insert)){
                $db->rollBack();
                lExit(500, '下单失败,请稍后再试...');
            }
            
            $update = [
                'order_cost_money'      =>  bcadd($order['order_cost_money'], bcmul($product['product_vip_price'], $productNum, 2), 2),
                'order_origin_money'    =>  bcadd($order['order_origin_money'], bcmul($product['product_sale_price'], $productNum, 2), 2),
                'order_product_num'     =>  $order['order_product_num'] + $productNum,
                'order_real_money'      =>  bcadd($order['order_real_money'], bcmul($product['product_sale_price'], $productNum, 2), 2)
            ];
            if(!Kissbaby_OrderModel::update($update, ['id'=>$order['id']])){
                $db->rollBack();
                lExit(500, '下单失败,请稍后再试...');
            }
        }
        
        $db->commit();
        lExit();
    }
    
    /**
     * 取消订单
     */
    public function cancelAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }
        
        $orderCode = $this->_request->getPost('order_code');
        if(!empty($orderCode) && $order=Kissbaby_OrderModel::getRow(['order_code'=>$orderCode], 'order_code')){
            lExit(502, '订单不存在');
        }

        if($_SESSION['user']['user_type']=='customer' && $order['order_status']!='INIT'){
            lExit(502, '订单【'.Kissbaby_OrderModel::getStatusHint($order['order_status']).'】, 不允许取消');
        }
        
        if(!Kissbaby_OrderModel::update(['order_status'=>'CANCEL'], ['order_code'=>$orderCode])){
            lExit(502, '取消订单失败');
        }
        
        lExit();
    }
    
    /**
     * 更新订单商品数量
     */
    public function updateProductNumAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }
        
        $orderCode = $this->_request->getPost('order_code');
        if(!empty($orderCode) || $order=Kissbaby_OrderModel::getRow(['order_code'=>$orderCode], 'order_code')){
            lExit(502, '订单不存在');
        }

        if($_SESSION['user']['user_type']=='customer' && $order['order_status']!='INIT'){
            lExit(502, '订单【'.Kissbaby_OrderModel::getStatusHint($order['order_status']).'】, 不允许编辑');
        }
        
        $productId = $this->_request->getPost('product_id');
        if(empty($productId)){
            lExit(502, '参数错误');
        }
        
        if(!$product=Kissbaby_OrderProductModel::getRow(['product_id'=>$productId, 'order_id'=>$order['id']])){
            lExit(500, '订单【'.$orderCode.'】里未找到要修改的商品');
        }
        
        $productNum = $this->_request->getPost('product_num');
        if(empty($productNum) || $productNum<=0){
            lExit(502, '购买数量错误');
        }
        
        $db = Database::getInstance('kissbaby');
        $db->startTransaction();
        
        $update = [
            'order_cost_money'      =>  bcmul($product['product_vip_price'], $productNum, 2),
            'order_origin_money'    =>  bcmul($product['product_sale_price'], $productNum, 2),
            'order_product_num'     =>  $productNum,
            'order_real_money'      =>  bcmul($product['product_sale_price'], $productNum, 2),
        ];
        if(!Kissbaby_OrderProductModel::update($update, ['product_id'=>$productId, 'order_id'=>$order['id']])){
            $db->rollBack();
            lExit(500, '更新订单【'.$orderCode.'】商品失败');
        }
        
        $productNum = $productNum - $product['product_num'];
        $update = [
            'order_cost_money'      =>  bcadd($order['order_cost_money'], bcmul($product['product_cost_money'], $productNum, 2), 2),
            'order_origin_money'    =>  bcadd($order['order_origin_money'], bcmul($product['product_sale_money'], $productNum, 2), 2),
            'order_product_num'     =>  $order['order_product_num'] +  $productNum,
            'order_real_money'      =>  bcadd($order['order_real_money'], bcmul($product['product_real_money'], $productNum, 2), 2)
        ];
        if(!Kissbaby_OrderModel::update($update, ['order_code'=>$orderCode])){
            lExit(502, '更新订单失败');
        }
        
        lExit();
    }
    
    /**
     * 更新订单价格
     */
    public function updateProductPriceAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }

        if($_SESSION['user']['user_type']=='customer'){
            lExit(502, '操作未授权');
        }
        
        $orderCode = $this->_request->getPost('order_code');
        if(!empty($orderCode) || $order=Kissbaby_OrderModel::getRow(['order_code'=>$orderCode], 'order_code')){
            lExit(502, '订单不存在');
        }
        
        $productId = $this->_request->getPost('product_id');
        if(empty($productId)){
            lExit(502, '参数错误');
        }
        
        if(!$product=Kissbaby_OrderProductModel::getRow(['product_id'=>$productId, 'order_id'=>$order['id']])){
            lExit(500, '订单【'.$orderCode.'】里未找到要修改的商品');
        }
        
        $db = Database::getInstance('kissbaby');
        $db->startTransaction();
        
        $productUpdate = [];
        
        if($tmp=abs(floatval($this->_request->getPost('product_cost_money')))){
            $productUpdate['product_cost_money'] = bcmul($tmp, $product['product_num'], 2);
        }
        
        if($tmp=abs(floatval($this->_request->getPost('product_sale_money')))){
            $productUpdate['product_sale_money'] = bcmul($tmp, $product['product_num'], 2);
        }
        
        if($tmp=abs(floatval($this->_request->getPost('product_real_money')))){
            $productUpdate['product_real_money'] = bcmul($tmp, $product['product_num'], 2);
        }
        
        if($productUpdate){
            if(!Kissbaby_OrderProductModel::update($productUpdate, ['product_id'=>$productId, 'order_id'=>$order['id']])){
                $db->rollBack();
                lExit(500, '更新订单【'.$orderCode.'】商品失败');
            }

            $orderUpdate = [];
            if(isset($productUpdate['product_cost_money'])){
                $singlePriceDiff = bcsub($productUpdate['product_cost_money'], $product['product_cost_money'], 2);
                $totalMoneyDiff = bcmul($singlePriceDiff, $product['product_num'], 2);
                $orderUpdate['order_cost_money'] = bcadd($order['order_cost_money'], $totalMoneyDiff, 2);
            }
            
            if(isset($productUpdate['product_sale_money'])){
                $singlePriceDiff = bcsub($productUpdate['product_sale_money'], $product['product_sale_money'], 2);
                $totalMoneyDiff = bcmul($singlePriceDiff, $product['product_num'], 2);
                $orderUpdate['order_origin_money'] = bcadd($order['order_origin_money'], $totalMoneyDiff, 2);
            }
            
            if(isset($productUpdate['product_sale_money'])){
                $singlePriceDiff = bcsub($productUpdate['product_real_money'], $product['product_real_money'], 2);
                $totalMoneyDiff = bcmul($singlePriceDiff, $product['product_num'], 2);
                $orderUpdate['order_real_money'] = bcadd($order['order_real_money'], $totalMoneyDiff, 2);
            }
            
            if($orderUpdate && !Kissbaby_OrderModel::update($update, ['order_code'=>$orderCode])){
                $db->rollBack();
                lExit(502, '更新订单失败');
            }
            
            $db->commit();
        }
        
        lExit();
    }
    
    /**
     * 更新订单优惠金额
     */
    public function updateDiscountMoneyAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }

        if($_SESSION['user']['user_type']=='customer'){
            lExit(502, '操作未授权');
        }
        
        $orderCode = $this->_request->getPost('order_code');
        if(!empty($orderCode) || $order=Kissbaby_OrderModel::getRow(['order_code'=>$orderCode], 'order_code')){
            lExit(502, '订单不存在');
        }
        
        lExit();
    }
    
    /**
     * 订单删除商品
     */
    public function delProductAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }
        
        $orderCode = $this->_request->getPost('order_code');
        if(!empty($orderCode) && $order=Kissbaby_OrderModel::getRow(['order_code'=>$orderCode])){
            lExit(502, '订单不存在');
        }
        
        if($_SESSION['user']['user_type']=='customer' && $order['order_status']!='INIT'){
            lExit(502, '订单【'.Kissbaby_OrderModel::getStatusHint($order['order_status']).'】, 不允许修改');
        }
        
        $productId = $this->_request->getPost('product_id');
        if(empty($productId)){
            lExit(502, '参数错误');
        }
        
        if(!$product=Kissbaby_OrderProductModel::getRow(['product_id'=>$productId, 'order_id'=>$order['id']])){
            lExit(500, '订单【'.$orderCode.'】里未找到要删除的商品');
        }
        
        $db = Database::getInstance('kissbaby');
        $db->startTransaction();
        
        if(!Kissbaby_OrderProductModel::delete(['product_id'=>$productId, 'order_id'=>$order['id']])){
            $db->rollBack();
            lExit(500, '删除订单【'.$orderCode.'】商品失败');
        }
        
        $update = [
            'order_cost_money'      =>  bcsub($order['order_cost_money'], bcmul($product['product_cost_money'], $product['product_num'], 2), 2),
            'order_origin_money'    =>  bcadd($order['order_origin_money'], bcmul($product['product_sale_money'], $product['product_num'], 2), 2),
            'order_product_num'     =>  $order['order_product_num'] - $product['product_num'],
            'order_real_money'      =>  bcadd($order['order_real_money'], bcmul($product['product_real_money'], $product['product_num'], 2), 2)
        ];
        if(!Kissbaby_OrderModel::update($update, ['order_id'=>$order['id']])){
            $db->rollBack();
            lExit(500, '更新订单【'.$orderCode.'】失败');
        }
        
        $db->commit();
        lExit();
    }
    
    /**
     * 购物车页面
     */
    public function cartAction(){
        $this->_view->assign('title', '我的购物车');
        return true;
    }
}