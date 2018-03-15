<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class OrderController extends BasicController{
    /**
     * 订单列表
     */
    public function indexAction(){
        $where = [];
        if(!BaseModel::isAdmin()){
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
        
        $total = Kissbaby_OrderModel::count($where);
        $orderList = [];
        if($total){
            $limit = ['limit'=>12];
            $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
            $orderList = Kissbaby_OrderModel::getList($where, '*', $limit, 'id desc');
        }
        $result = ['list'=>$orderList, 'total'=>$total];
        
        if($this->_request->isXmlHttpRequest()){
            lExit($result);
        }
        
        if(!$result['list']){
            header('location: /shop/index/succ?title=异常&msg=没有找到您的订单记录...&btn=立即下单&detail=/shop/index/index');exit;
        }
        
        $this->_view->assign('title', '订单中心');
        $this->_view->assign('data', $result);
        return true;
    }
    
    /**
     * 订单详情
     */
    public function detailAction(){
        $orderCode = $this->_request->getQuery('order_code');
        if(empty($orderCode)){
            header('location: /shop/index/succ?title=错误&msg=请求非法&detail=/shop/order/index');exit;
        }
        
        if(!$order=Kissbaby_OrderModel::getRow(['order_code'=>$orderCode])){
            header('location: /shop/index/succ?title=错误&msg=订单数据丢失...&detail=/shop/order/index');exit;
        }
        
        $orderProduct = Kissbaby_OrderProductModel::getList(['order_id'=>$order['id']]);
        if($order['order_status']!=='INIT'){
            header('location: /shop/index/succ?title=错误&msg=订单明细丢失...&detail=/shop/order/index');exit;
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
        if(!empty($orderCode)){
            if(!$order=Kissbaby_OrderModel::getRow(['order_code'=>$orderCode])){
                lExit(502, '订单不存在');
            }
            
            if($order['order_state']!=='INIT' && !BaseModel::isAdmin()){
                lExit(502, '订单以确认，如需修改订单，请联系管理员');
            }
        }
        
        $userId = $this->_request->getPost('user_id');
        if(empty($orderCode) && $_SESSION['user']['user_type']==='admin'){
            $userId = $userId ? $userId : $_SESSION['user']['id'];
        }else{
            $userId = $_SESSION['user']['id'];
        }
        
        $productList = $this->_request->getPost('product_list');
        if(empty($productList) || !is_array($productList)){
            lExit(502, '请至少选择一件商品');
        }
        
        $id2product = Kissbaby_ProductModel::getIndexedList(['product_id'=>array_column($productList, 'product_id')], 'product_id');
        if(count($productList)!==count($id2product)){
            lExit(502, '部分商品不存在');
        }
        
        $db = Database::getInstance('kissbaby');
        $db->startTransaction();
        
        if(!$order){
            $order = [
                'order_code'            =>  $orderCode=Kissbaby_OrderModel::getOrderCode(),
                'order_cost_money'      =>  0,
                'order_discount_money'  =>  0,
                'order_image'           =>  '',
                'order_origin_money'    =>  0,
                'order_product_num'     =>  0,
                'order_real_money'      =>  0,
                'order_status'          =>  'INIT',
                'user_id'               =>  $userId,
                'create_time'           =>  time(),
                'ts'                    =>  date('Y-m-d H:i:s')
            ];
            
            if(!$orderId = Kissbaby_OrderModel::insert($order)){
                $db->rollBack();
                lExit(500, '下单失败,请稍后再试...');
            }
            
            $order['id'] = $orderId;
        }
        
        $orderUpdate = [
            'order_cost_money'      =>  0,
            'order_origin_money'    =>  0,
            'order_product_num'     =>  0,
            'order_real_money'      =>  0
        ];
        
        foreach($productList as $_prd){
            $_product = $id2product[$_prd['product_id']];
            $tmp = explode(',', $product['product_image']);
            $_productImage = empty($tmp[0]) ? '' : $tmp[0];
            
            if($_orderProduct=Kissbaby_OrderProductModel::getRow(['order_id'=>$orderId, 'product_id'=>$_prd['product_id']])){//订单里已存在的商品
                if(bccomp($_orderProduct['product_vip_price'], $_product['product_vip_price'], 2)!=0 || bccomp($_orderProduct['product_sale_price'], $_product['product_sale_price'], 2)!=0 || bccomp($_orderProduct['product_num'], $_prd['product_num'], 2)!=0){//价格、数量有变更
                    $_update = [
                        'product_cost_money'    =>  bcmul($_product['product_vip_price'], $_prd['product_num'], 2),
                        'product_image'         =>  $_productImage,
                        'product_name'          =>  $_product['product_name'],
                        'product_num'           =>  $_prd['product_num'],
                        'product_real_money'    =>  bcmul($_product['product_sale_price'], $_prd['product_num'], 2),
                        'product_sale_money'    =>  bcmul($_product['product_sale_price'], $_prd['product_num'], 2),
                    ];
                    
                    if(!$orderProductId = Kissbaby_OrderProductModel::update($_update, $_where=['order_id'=>$orderId])){
                        $db->rollBack();
                        lExit(500, '更新订单商品失败,请稍后再试...');
                    }
                }
            }else{//新增的商品
                $_insert = [
                    'product_cost_money'    =>  bcmul($_product['product_vip_price'], $_prd['product_num'], 2),
                    'product_image'         =>  $_productImage,
                    'product_name'          =>  $_product['product_name'],
                    'product_num'           =>  $_prd['product_num'],
                    'product_real_money'    =>  bcmul($_product['product_sale_price'], $_prd['product_num'], 2),
                    'product_sale_money'    =>  bcmul($_product['product_sale_price'], $_prd['product_num'], 2),
                    'order_id'              =>  $orderId,
                    'product_id'            =>  $_product['product_id'],
                    'create_time'           =>  time()
                ];
                if(!$orderProductId = Kissbaby_OrderProductModel::insert($insert)){
                    $db->rollBack();
                    lExit(500, '新增订单商品失败,请稍后再试...');
                }
            }
            
            $orderUpdate['order_cost_money'] = bcadd($orderUpdate['order_cost_money'], bcmul($_product['product_vip_price'], $_prd['product_num'], 2), 2);
            $orderUpdate['order_origin_money'] = bcadd($orderUpdate['order_origin_money'], bcmul($_product['product_sale_price'], $_prd['product_num'], 2), 2);
            $orderUpdate['order_product_num'] = bcadd($orderUpdate['order_product_num'], $_prd['product_num']);
            $orderUpdate['order_real_money'] = bcadd($orderUpdate['order_real_money'], bcmul($_product['product_sale_price'], $_prd['product_num'], 2), 2);
        }

        if(!Kissbaby_OrderModel::update($orderUpdate, ['id'=>$orderId])){
            $db->rollBack();
            lExit(500, '更新订单失败,请稍后再试...');
        }
        
        $db->commit();
        lExit(['order_code'=>$orderCode]);
    }
    
    /**
     * 取消订单
     */
    public function cancelAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }
        
        $orderCode = $this->_request->getPost('order_code');
        if(!empty($orderCode)){
            lExit(502, '订单号不能为空');
        }
        
        $where = ['order_code'=>$orderCode];
        if(!BaseModel::isAdmin()){
            $where['user_id'] = $_SESSION['user']['id'];
        }

        if(!$order=Kissbaby_OrderModel::getRow($where, 'order_code')){
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

        if(!BaseModel::isAdmin() && $order['order_status']!='INIT'){
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
        if(empty($productNum) || $productNum<0){
            lExit(502, '购买数量错误');
        }
        
        $db = Database::getInstance('kissbaby');
        $db->startTransaction();
        
        if($productNum>0){
            if(!Kissbaby_OrderProductModel::update(['product_num'=>$productNum], ['product_id'=>$productId, 'order_id'=>$order['id']])){
                $db->rollBack();
                lExit(500, '更新订单【'.$orderCode.'】商品失败');
            }
        }else{
            if(!Kissbaby_OrderProductModel::delete(['product_id'=>$productId, 'order_id'=>$order['id']])){
                $db->rollBack();
                lExit(500, '删除订单【'.$orderCode.'】商品失败');
            }
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

        if(!BaseModel::isAdmin()){
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
            $productUpdate['product_cost_money'] = $tmp;
        }
        
        if($tmp=abs(floatval($this->_request->getPost('product_sale_money')))){
            $productUpdate['product_sale_money'] = $tmp;
        }
        
        if($tmp=abs(floatval($this->_request->getPost('product_real_money')))){
            $productUpdate['product_real_money'] = $tmp;
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
                lExit(502, '更新订单金额失败');
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

        if(!BaseModel::isAdmin()){
            lExit(502, '操作未授权');
        }
        
        $orderDiscountMoney = intval($this->_request->getPost('order_discount_money'));
        if(empty($orderDiscountMoney)){
            lExit(502, '折扣金额不能为空');
        }
        
        $orderCode = $this->_request->getPost('order_code');
        if(!empty($orderCode) || $order=Kissbaby_OrderModel::getRow(['order_code'=>$orderCode], 'order_code')){
            lExit(502, '订单不存在');
        }
        
        $orderSaleMoney = bcsub($order['order_sale_money'], $orderDiscountMoney, 2);
        if(bccomp($orderSaleMoney, 0, 2)<0){
            lExit(502, '折扣金额不能大于销售价');
        }
        
        $update = [
            'order_discount_money'  =>  $orderDiscountMoney,
            'order_sale_money'      =>  $orderSaleMoney,
        ];
        if(!Kissbaby_OrderModel::update($update, ['order_id'=>$order['id']])){
            $db->rollBack();
            lExit(500, '更新订单【'.$orderCode.'】折扣金额失败');
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
        if(empty($orderCode)){
            lExit(502, '订单号不能为空');
        }
        
        $where = ['order_code'=>$orderCode];
        if(!BaseModel::isAdmin()){
            $where['user_id'] = $_SESSION['user']['id'];
        }

        if(!$order=Kissbaby_OrderModel::getRow($where)){
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