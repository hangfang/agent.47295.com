<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class BillController extends BasicController{
    /**
     * 订单列表
     */
    public function indexAction(){
        $where = [];
        $userId = $this->_request->getPost('user_id');
        $userList = [];
        if(BaseModel::isAdmin()){
            $userList = Agent_UserModel::getList([], 'id,user_name');
            $userId && $where['user_id'] = $userId;
        }else{
            $where['user_id'] = $_SESSION['user']['id'];
        }
        
        $billStime = $this->_request->getPost('bill_stime');
        if(!empty($billStime)){
            $where['create_time>='] = strtotime($billStime);
        }
        
        $billEtime = $this->_request->getPost('bill_etime');
        if(!empty($billEtime)){
            $where['create_time<='] = strtotime($billEtime.' 23:59:59');
        }
        
        $billStatus = $this->_request->getPost('bill_status');
        $billStatus = $billStatus ? explode(',', $billStatus) : [];
        if($billStatus){
            $where['bill_status'] = $billStatus;
        }
        
        $total = Kissbaby_BillModel::count($where);
        $billList = [];
        if($total){
            $limit = ['limit'=>10];
            $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
            $billList = Kissbaby_BillModel::getList($where, '*', $limit, 'id desc');
        }
        $result = ['list'=>$billList, 'total'=>$total];
        
        if($this->_request->isXmlHttpRequest()){
            lExit($result);
        }
        
        if(!$result['list']){
            //header('location: /shop/index/succ?title=异常&msg=没有找到您的订单记录...&btn=立即下单&detail=/shop/index/index');exit;
        }
        
        $this->_view->assign('title', '订单中心');
        $this->_view->assign('userId', $userId);
        $this->_view->assign('billStatus', $billStatus);
        $this->_view->assign('userList', $userList);
        $this->_view->assign('data', $result);
        return true;
    }
    
    /**
     * 订单详情
     */
    public function detailAction(){
        $billCode = $this->_request->getQuery('bill_code');
        if(empty($billCode)){
            header('location: /shop/index/succ?title=错误&msg=请求非法&detail=/shop/bill/index');exit;
        }
        
        if(!$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode])){
            header('location: /shop/index/succ?title=错误&msg=订单数据丢失...&detail=/shop/bill/index');exit;
        }
        
        $where = ['bill_id'=>$bill['id']];
        if(!BaseModel::isAdmin()){
            $where['user_id'] = $_SESSION['user']['id'];
        }
        $billProduct = Kissbaby_BillProductModel::getList($where);
        
        $this->_view->assign('title', '订单详情');
        $this->_view->assign('bill', $bill);
        $this->_view->assign('billProduct', $billProduct);
        $this->_view->assign('addressList', Agent_AddressModel::getList(['user_id'=>$bill['user_id'], 'address_status'=>0]));
        return true;
    }
    
    /**
     * 创建订单、订单新增商品
     */
    public function addAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }
        
        $productList = $this->_request->getPost('product_list');
        if(empty($productList) || !is_array($productList) || !$productId=array_column($productList, 'product_id')){
            lExit(502, '请至少选择一件商品');
        }
        
        $billCode = $this->_request->getPost('bill_code');
        if(!empty($billCode)){
            if(!$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode])){
                lExit(502, '订单不存在');
            }
            
            if($bill['bill_status']!=='INIT' && !BaseModel::isAdmin()){
                lExit(502, '订单以确认，如需修改订单，请联系管理员');
            }
        
            if(Kissbaby_BillProductModel::count(['bill_id'=>$bill['id'], 'product_id'=>$productId])){//订单里已存在的商品
                $db->rollBack();
                lExit(500, '商品已被加入订单,请勿重复购买');
            }
        }
        
        $userId = $this->_request->getPost('user_id');
        if(empty($billCode) && $_SESSION['user']['user_type']==='admin'){
            $userId = $userId ? $userId : $_SESSION['user']['id'];
        }else{
            $userId = $_SESSION['user']['id'];
        }
        
        $id2product = Kissbaby_ProductModel::getIndexedList(['product_id'=>array_column($productList, 'product_id')], 'product_id');
        if(count($productList)!==count($id2product)){
            lExit(502, '部分商品不存在');
        }
        
        foreach($productList as $_key=>$_prd){
            if($_prd['product_num']<0){
                lExit(502, '商品【'.$id2product[$_prd['product_id']]['product_name'].'】购买数量错误');
            }
        }
        
        $address = Agent_AddressModel::getRow(['user_id'=>$userId, 'address_status'=>0, 'address_default'=>1], 'id');
        $db = Database::getInstance('kissbaby');
        $db->startTransaction();
        
        if(empty($bill)){
            $bill = [
                'express_com'           =>  'ZTO',
                'bill_code'             =>  $billCode=Kissbaby_BillModel::getBillCode(),
                'bill_cost_money'       =>  '0.00',
                'bill_discount_money'   =>  '0.00',
                'bill_image'            =>  '',
                'bill_origin_money'     =>  '0.00',
                'bill_product_num'      =>  0,
                'bill_sale_money'       =>  '0.00',
                'bill_status'           =>  'INIT',
                'address_id'            =>  empty($address['id']) ? '' : $address['id'],
                'user_id'               =>  $userId,
                'create_time'           =>  time(),
                'ts'                    =>  date('Y-m-d H:i:s')
            ];
            
            if(!$billId = Kissbaby_BillModel::insert($bill)){
                log_message('error', __FUNCTION__.', 插入订单数据失败, insert:'.print_r($bill, true));
                $db->rollBack();
                lExit(500, '下单失败,请稍后再试...');
            }
            
            $bill['id'] = $billId;
        }
        
        $billUpdate = [
            'bill_cost_money'      =>  $bill['bill_cost_money'],
            'bill_origin_money'    =>  $bill['bill_origin_money'],
            'bill_product_num'     =>  $bill['bill_product_num'],
            'bill_sale_money'      =>  $bill['bill_sale_money'],
        ];
        
        foreach($productList as $_prd){
            $_product = $id2product[$_prd['product_id']][0];
            $tmp = explode(',', $_product['product_image']);
            $_productImage = empty($tmp[0]) ? '' : $tmp[0];
            
            $_insert = [
                'product_cost_money'    =>  bcmul($_product['product_vip_price'], $_prd['product_num'], 2),
                'product_image'         =>  $_productImage,
                'product_name'          =>  $_product['product_name'],
                'product_num'           =>  $_prd['product_num'],
                'product_real_money'    =>  bcmul($_product['product_sale_price'], $_prd['product_num'], 2),
                'product_sale_money'    =>  bcmul($_product['product_sale_price'], $_prd['product_num'], 2),
                'bill_id'              =>  $bill['id'],
                'product_id'            =>  $_product['product_id'],
                'create_time'           =>  time()
            ];
            if(!$billProductId = Kissbaby_BillProductModel::insert($_insert)){
                log_message('error', __FUNCTION__.', 插入订单商品失败, insert:'.print_r($_insert, true));
                $db->rollBack();
                lExit(500, '新增订单商品失败,请稍后再试...');
            }
            
            $billUpdate['bill_cost_money'] = bcadd($billUpdate['bill_cost_money'], bcmul($_product['product_vip_price'], $_prd['product_num'], 2), 2);
            $billUpdate['bill_origin_money'] = bcadd($billUpdate['bill_origin_money'], bcmul($_product['product_sale_price'], $_prd['product_num'], 2), 2);
            $billUpdate['bill_product_num'] = bcadd($billUpdate['bill_product_num'], $_prd['product_num']);
            $billUpdate['bill_sale_money'] = bcadd($billUpdate['bill_sale_money'], bcmul($_product['product_sale_price'], $_prd['product_num'], 2), 2);
            $billUpdate['bill_image'] = $_productImage;
        }

        if(false===Kissbaby_BillModel::update($billUpdate, ['id'=>$bill['id']])){
            $db->rollBack();
            lExit(500, '更新订单失败,请稍后再试...');
        }
        
        $db->commit();
        lExit(['bill_code'=>$billCode]);
    }
    
    /**
     * 取消订单
     */
    public function cancelAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode)){
            lExit(502, '订单号不能为空');
        }
        
        $where = ['bill_code'=>$billCode];
        if(!BaseModel::isAdmin()){
            $where['user_id'] = $_SESSION['user']['id'];
        }

        if(!$bill=Kissbaby_BillModel::getRow($where)){
            lExit(502, '订单不存在');
        }

        if($_SESSION['user']['user_type']=='customer' && $bill['bill_status']!='INIT'){
            lExit(502, '订单【'.Kissbaby_BillModel::getStatusHint($bill['bill_status']).'】, 不允许取消');
        }
        
        if(false===Kissbaby_BillModel::update(['bill_status'=>'CANCEL'], ['bill_code'=>$billCode])){
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
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode) || !$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode])){
            lExit(502, '订单不存在');
        }

        if(!BaseModel::isAdmin() && $bill['bill_status']!='INIT'){
            lExit(502, '订单【'.Kissbaby_BillModel::getStatusHint($bill['bill_status']).'】, 不允许编辑');
        }
        
        $productId = $this->_request->getPost('product_id');
        if(!is_numeric($productId)){
            lExit(502, '参数错误');
        }
        
        if(!$product=Kissbaby_BillProductModel::getRow(['product_id'=>$productId, 'bill_id'=>$bill['id']])){
            lExit(500, '订单【'.$billCode.'】里未找到要修改的商品');
        }
        
        $productNum = $this->_request->getPost('product_num');
        if(!is_numeric($productNum) || $productNum<0){
            lExit(502, '购买数量错误');
        }
        
        $db = Database::getInstance('kissbaby');
        $db->startTransaction();
        
        if($productNum>0){
            if(false===Kissbaby_BillProductModel::update(['product_num'=>$productNum], ['product_id'=>$productId, 'bill_id'=>$bill['id']])){
                $db->rollBack();
                lExit(500, '更新订单【'.$billCode.'】商品失败');
        }
        }else{
            if(!Kissbaby_BillProductModel::delete(['product_id'=>$productId, 'bill_id'=>$bill['id']])){
                $db->rollBack();
                lExit(500, '删除订单【'.$billCode.'】商品失败');
            }
        }
        
        $productNum = $productNum - $product['product_num'];
        $update = [
            'bill_cost_money'      =>  bcadd($bill['bill_cost_money'], bcmul($product['product_cost_money'], $productNum, 2), 2),
            'bill_origin_money'    =>  bcadd($bill['bill_origin_money'], bcmul($product['product_sale_money'], $productNum, 2), 2),
            'bill_product_num'     =>  $bill['bill_product_num'] +  $productNum,
            'bill_sale_money'      =>  bcadd($bill['bill_sale_money'], bcmul($product['product_real_money'], $productNum, 2), 2)
        ];
        if(false===Kissbaby_BillModel::update($update, ['bill_code'=>$billCode])){
            $db->rollBack();
            lExit(502, '更新订单失败');
        }
        
        $db->commit();
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
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode) || !$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode])){
            lExit(502, '订单不存在');
        }
        
        $productId = $this->_request->getPost('product_id');
        if(!is_numeric($productId)){
            lExit(502, '参数错误');
        }
        
        if(!$product=Kissbaby_BillProductModel::getRow(['product_id'=>$productId, 'bill_id'=>$bill['id']])){
            lExit(500, '订单【'.$billCode.'】里未找到要修改的商品');
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
            if(false===Kissbaby_BillProductModel::update($productUpdate, ['product_id'=>$productId, 'bill_id'=>$bill['id']])){
                $db->rollBack();
                lExit(500, '更新订单【'.$billCode.'】商品失败');
            }

            $billUpdate = [];
            if(isset($productUpdate['product_cost_money'])){
                $singlePriceDiff = bcsub($productUpdate['product_cost_money'], $product['product_cost_money'], 2);
                $totalMoneyDiff = bcmul($singlePriceDiff, $product['product_num'], 2);
                $billUpdate['bill_cost_money'] = bcadd($bill['bill_cost_money'], $totalMoneyDiff, 2);
            }
            
            if(isset($productUpdate['product_sale_money'])){
                $singlePriceDiff = bcsub($productUpdate['product_sale_money'], $product['product_sale_money'], 2);
                $totalMoneyDiff = bcmul($singlePriceDiff, $product['product_num'], 2);
                $billUpdate['bill_origin_money'] = bcadd($bill['bill_origin_money'], $totalMoneyDiff, 2);
            }
            
            if(isset($productUpdate['product_real_money'])){
                $singlePriceDiff = bcsub($productUpdate['product_real_money'], $product['product_real_money'], 2);
                $totalMoneyDiff = bcmul($singlePriceDiff, $product['product_num'], 2);
                $billUpdate['bill_sale_money'] = bcadd($bill['bill_sale_money'], $totalMoneyDiff, 2);
            }
            
            if($billUpdate && false===Kissbaby_BillModel::update($billUpdate, ['bill_code'=>$billCode])){
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
        
        $billDiscountMoney = floatval($this->_request->getPost('bill_discount_money'));
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode) || !$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode])){
            lExit(502, '订单不存在');
        }
        
        $billSaleMoney = bcsub($bill['bill_sale_money'], $billDiscountMoney, 2);
        if(bccomp($billSaleMoney, 0, 2)<0){
            lExit(502, '折扣金额不能大于销售价');
        }
        
        $update = [
            'bill_discount_money'  =>  $billDiscountMoney,
            'bill_sale_money'      =>  $billSaleMoney,
        ];
        if(false===Kissbaby_BillModel::update($update, ['bill_code'=>$billCode])){
            lExit(500, '更新订单【'.$billCode.'】折扣金额失败');
        }
        
        lExit();
    }
    
    /**
     * 更新订单状态
     */
    public function updateStatusAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }

        if(!BaseModel::isAdmin()){
            lExit(502, '操作未授权');
        }
        
        $billStatus = $this->_request->getPost('bill_status');
        if(!in_array($billStatus, array_keys(BILL_STATUS_HINT))){
            lExit(502, '订单状态错误');
        }
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode) || !$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode])){
            lExit(502, '订单不存在');
        }
        
        $update = [
            'bill_status'  =>  $billStatus
        ];
        if(false===Kissbaby_BillModel::update($update, ['bill_code'=>$billCode])){
            lExit(500, '更新订单【'.$billCode.'】状态失败');
        }
        
        lExit();
    }
    
    /**
     * 更新快递单号状态
     */
    public function updateExpressAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }

        if(!BaseModel::isAdmin()){
            lExit(502, '操作未授权');
        }
        
        $update = [];
        $expressNum = $this->_request->getPost('express_num');
        $update['express_num'] = $expressNum ? preg_replace('/\s+/', '', htmlentities($expressNum)) : '';
        if(empty($update['express_num'])){
            lExit(502, '快递单号不能为空');
        }
        
        $expressConf = get_var_from_conf('kdniao');
        $expressCom = $this->_request->getPost('express_com');
        if($expressCom){
            !in_array($expressCom, array_values($expressConf)) && lExit(502, '快递公司不存在');
            $update['express_com'] = htmlentities($expressCom);
        }else{
            $res = KuaidiModel::recognize($update['express_num']);
            if(empty($res['Shippers'])){
                lExit(502, '快递单号识别失败');
            }
            
            foreach($res['Shippers'] as $_shipper){
                if(in_array($_shipper['ShipperCode'], array_values($expressConf))){
                    $update['express_com'] = $_shipper['ShipperCode'];
                }
            }
        }
        
        
        if(!$update){
            lExit(502, '数据无更新');
        }
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode) || !$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode])){
            lExit(502, '订单不存在');
        }
        
        if(false===Kissbaby_BillModel::update($update, ['bill_code'=>$billCode])){
            lExit(500, '更新订单【'.$billCode.'】物流信息失败');
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
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode)){
            lExit(502, '订单号不能为空');
        }
        
        $where = ['bill_code'=>$billCode];
        if(!BaseModel::isAdmin()){
            $where['user_id'] = $_SESSION['user']['id'];
        }

        if(!$bill=Kissbaby_BillModel::getRow($where)){
            lExit(502, '订单不存在');
        }
        
        if($_SESSION['user']['user_type']=='customer' && $bill['bill_status']!='INIT'){
            lExit(502, '订单【'.Kissbaby_BillModel::getStatusHint($bill['bill_status']).'】, 不允许修改');
        }
        
        $productId = $this->_request->getPost('product_id');
        if(!is_numeric($productId)){
            lExit(502, '参数错误');
        }
        
        if(!$product=Kissbaby_BillProductModel::getRow(['product_id'=>$productId, 'bill_id'=>$bill['id']])){
            lExit(500, '订单【'.$billCode.'】里未找到要删除的商品');
        }
        
        $db = Database::getInstance('kissbaby');
        $db->startTransaction();
        
        if(!Kissbaby_BillProductModel::delete(['product_id'=>$productId, 'bill_id'=>$bill['id']])){
            $db->rollBack();
            lExit(500, '删除订单【'.$billCode.'】商品失败');
        }
        
        $update = [
            'bill_cost_money'      =>  bcsub($bill['bill_cost_money'], bcmul($product['product_cost_money'], $product['product_num'], 2), 2),
            'bill_origin_money'    =>  bcadd($bill['bill_origin_money'], bcmul($product['product_sale_money'], $product['product_num'], 2), 2),
            'bill_product_num'     =>  $bill['bill_product_num'] - $product['product_num'],
            'bill_sale_money'      =>  bcadd($bill['bill_sale_money'], bcmul($product['product_real_money'], $product['product_num'], 2), 2)
        ];
        if(false===Kissbaby_BillModel::update($update, ['bill_id'=>$bill['id']])){
            $db->rollBack();
            lExit(500, '更新订单【'.$billCode.'】失败');
        }
        
        $db->commit();
        lExit();
    }
    
    /**
     * 购物车页面
     */
    public function cartAction(){
        $this->_view->assign('title', '我的购物车');
        $this->_view->assign('userList', BaseModel::isAdmin() ? Agent_UserModel::getList() : Agent_UserModel::getList(['id'=>$_SESSION['user']['id']]));
        return true;
    }
    
    /**
     * 更新订单备注
     */
    public function updateMemoAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }

        if(!BaseModel::isAdmin()){
            lExit(502, '操作未授权');
        }
        
        $billMemo = $this->_request->getPost('bill_memo');
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode) || !$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode])){
            lExit(502, '订单不存在');
        }
        
        $update = [
            'bill_memo'  => htmlentities($billMemo)
        ];
        if(false===Kissbaby_BillModel::update($update, ['bill_code'=>$billCode])){
            lExit(500, '更新订单【'.$billCode.'】备注失败');
        }
        
        lExit();
    }
    
    /**
     * 更新订单商品名称
     */
    public function updateProductNameAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }

        if(!BaseModel::isAdmin()){
            lExit(502, '操作未授权');
        }
        
        $productId = $this->_request->getPost('product_id');
        if(!is_numeric($productId)){
            lExit(502, '参数错误');
        }
        
        if($productId!=0){
            lExit(502, '只能更新完成替身商品的名称');
        }
        
        $productName = $this->_request->getPost('product_name');
        if(empty($productName)){
            lExit(502, '商品名称不能修改为空');
        }
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode) || !$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode])){
            lExit(502, '订单不存在');
        }
        
        if(!$product=Kissbaby_BillProductModel::getRow(['product_id'=>$productId, 'bill_id'=>$bill['id']])){
            lExit(500, '订单【'.$billCode.'】里未找到要修改的商品');
        }
        
        if(false===Kissbaby_BillProductModel::update(['product_name'=>$productName], ['product_id'=>$productId, 'bill_id'=>$bill['id']])){
            lExit(500, '更新订单【'.$billCode.'】商品名称失败');
        }
        
        lExit();
    }
    
    /**
     * 添加替补商品
     */
    public function addBakAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }

        if(!BaseModel::isAdmin()){
            lExit(502, '操作未授权');
        }
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode) || !$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode], 'id')){
            lExit(502, '订单不存在');
        }
        
        if(Kissbaby_BillProductModel::count(['bill_id'=>$bill['id'], 'product_id'=>0])){//订单里已存在的商品
            lExit(500, '商品已被加入订单,请勿重复购买');
        }
        
        $product = Kissbaby_ProductModel::getRow(['product_id'=>0]);
        if(!$product){
            lExit(502, '替补商品不存在');
        }
        
        $_insert = [
            'product_cost_money'    =>  0,
            'product_image'         =>  '',
            'product_name'          =>  $product['product_name'],
            'product_num'           =>  1,
            'product_real_money'    =>  0,
            'product_sale_money'    =>  0,
            'bill_id'               =>  $bill['id'],
            'product_id'            =>  0,
            'create_time'           =>  time()
        ];
        if(!Kissbaby_BillProductModel::insert($_insert)){
            log_message('error', __FUNCTION__.', 插入订单商品失败, insert:'.print_r($_insert, true));
            lExit(500, '新增订单商品失败,请稍后再试...');
        }
        
        lExit(['bill_code'=>$billCode]);
    }
    
    public function expressAction(){
        $billCode = $this->_request->getQuery('bill_code');
        if(empty($billCode) || !$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode], 'express_com,express_num,express_detail,express_status')){
            header('location: /shop/index/succ?title=错误&msg=订单不存在&detail=/shop/bill/index');exit;
        }
        
        $kdniao = array_flip(get_var_from_conf('kdniao'));
        $this->_view->assign('title', '物流信息');
        $this->_view->assign('expressCom', $kdniao[$bill['express_com']]);
        $this->_view->assign('expressNum', $bill['express_num']);
        $this->_view->assign('expressStatus', [0=>'无数据', 1=>'已揽收', 2=>'在途中', 201=>'到达派件城市', 202=>'派件中', 211=>'已放入快递柜或驿站', 3=>'已签收', 311=>'已取出快递柜或驿站', 4=>'问题件', 401=>'发货无信息', 402=>'超时未签收', 403=>'超时未更新', 404=>'拒收（退件）', 412=>'快递柜或驿站超时未取']);
        $this->_view->assign('expressDetail', $bill['express_detail'] ? json_decode($bill['express_detail'], true) : []);
        return true;
    }
    
    /**
     * 快递下单
     */
    public function expressOrderAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }

        if(!BaseModel::isAdmin()){
            lExit(502, '操作未授权');
        }
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode) || !$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode], 'id,express_com,address_id,user_id')){
            lExit(502, '订单不存在');
        }
        
        if(!$bill['express_com']){
            lExit(502, '请先选择物流公司');
        }
        
        if(!$bill['address_id']){
            lExit(502, '请先选择收货地址');
        }
        
        $address = Agent_AddressModel::getRow(['id'=>$bill['address_id'], 'user_id'=>$bill['user_id']]);
        if(!$address){
            lExit(502, '收货地址不存在');
        }
        
        $result = KuaidiModel::order($bill['express_com'], $billCode, $address);
        if(empty($result['Success'])){
            lExit(502, empty($result['Reason']) ? '快递下单失败' : $result['Reason']);
        }
        
        if(empty($result['Order']['LogisticCode'])){
            lExit(502, '未获取到快递单号');
        }
        
        $update = ['express_num'=>$result['Order']['LogisticCode']];
        if(false===Kissbaby_BillModel::update($update, ['bill_code'=>$billCode])){
            lExit(502, '更新快递单号失败');
        }
        
        lExit();
    }
    
    /**
     * 更新订单收货地址
     */
    public function updateAddressAction(){
        if(!$this->_request->isXmlHttpRequest()){
            lExit(502, '请求非法');
        }
        
        $billCode = $this->_request->getPost('bill_code');
        if(empty($billCode) || !$bill=Kissbaby_BillModel::getRow(['bill_code'=>$billCode])){
            lExit(502, '订单不存在');
        }

        if(!BaseModel::isAdmin() && $bill['bill_status']!=='INIT'){
            lExit(502, '操作未授权');
        }
        
        $addressId = intval($this->_request->getPost('address_id'));
        if(!$addressId || !$address=Agent_AddressModel::getRow(['id'=>$addressId, 'user_id'=>$bill['user_id']])){
            lExit(502, '收货地址不存在');
        }
        
        $update = [
            'address_id'  => $addressId
        ];
        if(false===Kissbaby_BillModel::update($update, ['bill_code'=>$billCode])){
            lExit(500, '更新订单【'.$billCode.'】收货地址失败');
        }
        
        lExit();
    }
}