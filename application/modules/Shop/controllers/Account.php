<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class AccountController extends BasicController{
    public function indexAction(){
        $this->_view->assign('title', '账户中心');
        return true;
    }
    
    /**
     * @todo 用户列表
     * @param int limit 分页.每页记录数
     * @param int offset 分页.偏移量
     * @return boolean
     */
    public function userAction(){
        if(!BaseModel::isAdmin()){
            if($this->_request->isXmlHttpRequest()){
                lExit(502, '操作未授权');
            }else{
                header('location: /shop/index/succ?title=异常&msg=操作未授权&btn=确定&detail=/shop/index/index');exit;
            }
        }
        
        $total = Agent_UserModel::count();
        $userList = [];
        if($total){
            $limit = ['limit'=>10];
            $limit['offset'] = is_numeric($tmp=$this->_request->getQuery('offset')) ? intval($tmp) : 0;
            $userList = Agent_UserModel::getList([], '*', $limit, 'id desc');
            
            if($userList){
                $id2bill = Kissbaby_BillModel::getIndexedList(['user_id'=>array_column($userList, 'id')], 'user_id', 'count(id) as bill_num', '', '', 'user_id');
                foreach($userList as &$_user){
                    $_user['bill_num'] = empty($id2bill[$_user['id']][0]['bill_num']) ? 0 : $id2bill[$_user['id']][0]['bill_num'];
                }
            }
        }
        
        $result = ['list'=>$userList, 'total'=>$total];
        if($this->_request->isXmlHttpRequest()){
            lExit($result);
        }
        
        $this->_view->assign('title', '账号管理');
        $this->_view->assign('data', $result);
        return true;
    }
    
    /**
     * @todo 注册账号
     * @param string user_name 真实姓名
     * @param string user_mobile 手机号码
     * @param string user_pwd 密码 (123456)
     * @author fanghang@fujiacaifu.com
     */
    public function addUserAction(){
        if(!BaseModel::isAdmin()){
            lExit(502, '操作未授权');
        }
        
        $userName = $this->_request->getPost('user_name');
        if(!$userName || strlen($userName)<2){
            lExit('真实性不能少于2个字符');
        }
        
        $userMobile = $this->_request->getPost('user_mobile');
        if(!preg_match(PHONE_REG, $userMobile)){
            lExit('手机号码错误');
        }
        
        $userPwd = $this->_request->getPost('user_pwd');
        if(!$userPwd || strlen($userPwd) <6 ){
            lExit('密码少于6个字符');
        }
        
        if(Agent_UserModel::getRow(['user_mobile'=>$userMobile], 'id')){
            lExit('手机号码已被注册');
        }
        
        $insert = [
                'user_name'=>$userName,
                'user_mobile'=>$userMobile,
                'user_pwd'=>md5($userPwd),
                'user_status'=>'NORMAL',
                'create_time'=>time(),
                'ts'=>date('Y-m-d H:i:s')
            ];
        if(!$userId = Agent_UserModel::insert($insert)){
            lExit('账号注册失败');
        }
        
        $insert['id'] = $userId;
        lExit($insert);
    }
    
    /**
     * @todo 新增用户
     * @return boolean
     */
    public function userAddAction(){
        if(!BaseModel::isAdmin()){
            header('location: /shop/index/succ?title=异常&msg=操作未授权&btn=确定&detail=/shop/index/index');exit;
        }
        
        $this->_view->assign('title', '新增用户');
        return true;
    }
    
    /**
     * @todo 用户详情
     * @param int id 用户id
     * @return boolean
     */
    public function userDetailAction(){
        if(!BaseModel::isAdmin()){
            header('location: /shop/index/succ?title=异常&msg=操作未授权&btn=确定&detail=/shop/index/index');exit;
        }
        
        $userId = $this->_request->getQuery('id');
        if(!$userId){
            header('location: /shop/index/succ?title=异常&msg=用户id非法&btn=确定&detail=/shop/account/user');exit;
        }
        
        $user = Agent_UserModel::getRow(['id'=>$userId]);
        if(!$user){
            header('location: /shop/index/succ?title=异常&msg=未查询到用户&btn=确定&detail=/shop/account/user');exit;
        }
        
        $this->_view->assign('title', '用户资料-'.$user['user_name']);
        $this->_view->assign('user', $user);
        $this->_view->assign('billList', Kissbaby_BillModel::getList(['user_id'=>$userId], '*', '', 'id desc'));
        $this->_view->assign('address', Agent_AddressModel::getRow(['user_id'=>$userId, 'address_status'=>0], '*', '', 'address_default desc'));
        return true;
    }
    
    /**
     * @todo 收货地址列表
     * @return boolean
     */
    public function addressAction(){
        $where = ['address_status'=>0];
        if(!BaseModel::isAdmin()){
            $where['user_id'] = $_SESSION['user']['id'];
        }
        
        $this->_view->assign('title', '收货地址管理');
        $this->_view->assign('addressList', Agent_AddressModel::getList($where));
        return true;
    }
    
    /**
     * @todo 新增或编辑收货地址
     * @param int address_id 收货地址id
     * @param int user_id 用户id
     * @return boolean
     */
    public function addressUpdateAction(){
        $address = [];
        $id = $this->_request->getQuery('address_id');
        $userId = $this->_request->getQuery('user_id');
        if($id){
            $where = ['id'=>$id];
            if(!BaseModel::isAdmin()){
                $where['user_id'] = $_SESSION['user']['id'];
            }
            
            if(!$address = Agent_AddressModel::getRow($where)){
                header('location: /shop/index/succ?title=异常&msg=操作未授权&btn=确定&detail=/shop/account/index');exit;
            }
        }
        
        $this->_view->assign('userId', empty($userId) ? '' : intval($userId));
        $this->_view->assign('address', $address);
        $this->_view->assign('title', $id ? '更新收货地址' : '新增收货地址');
        return true;
    }
    
    /**
     * @todo 新增或编辑收货地址
     * @param string address_name 真实姓名
     * @param string address_tel 手机号码
     * @param string address_province 省份 (广东)
     * @param string address_city 城市
     * @param string address_status 是否启用
     * @param string address_default 是否默认地址
     * @param int id 地址id
     * @param int user_id 用户id
     * @author fanghang@fujiacaifu.com
     */
    public function updateAddressAction(){
        $addressName = $this->_request->getPost('address_name');
        if(!$addressName || strlen($addressName)<2){
            lExit('真实性不能少于2个字符');
        }
        
        $addressTel = $this->_request->getPost('address_tel');
        if(!preg_match(PHONE_REG, $addressTel)){
            lExit('手机号码错误');
        }
        
        $addressProvince = $this->_request->getPost('address_province');
        if(!strlen($addressProvince)){
            lExit('省份不能为空');
        }
        
        $addressCity = $this->_request->getPost('address_city');
        if(!strlen($addressCity)){
            lExit('城市不能为空');
        }
        
        $addressDetail = $this->_request->getPost('address_detail');
        if(strlen($addressDetail)<5){
            lExit('详细地址不能少于5个字符');
        }
        
        $addressStatus = $this->_request->getPost('address_status');
        $addressDefault = $this->_request->getPost('address_default');
        
        
        $userId = $this->_request->getPost('user_id');
        if(BaseModel::isAdmin()){
            if($userId && !$user = Agent_UserModel::getRow(['id'=>$userId])){
                lExit('用户不存在');
            }
            $userId = $userId ? $userId : $_SESSION['user']['id'];
        }else{
            $userId = $_SESSION['user']['id'];
        }
        
        $address = [];
        $id = $this->_request->getPost('address_id');
        if($id && !$address = Agent_AddressModel::getRow(['id'=>$id, 'user_id'=>$userId])){
            lExit('收货地址不存在');
        }
        
        $db = Database::getInstance();
        $db->startTransaction();
        if($addressDefault){
            if(false===Agent_AddressModel::update(['address_default'=>0], ['address_default'=>1, 'user_id'=>$userId])){
                $db->rollBack();
                lExit('取消默认收货地址失败');
            }
        }
        
        if($address){
            $update = [
                'address_name'=>$addressName,
                'address_tel'=>$addressTel,
                'address_province'=>$addressProvince,
                'address_city'=>$addressCity,
                'address_detail'=>$addressDetail,
                'address_status'=>$addressStatus,
                'address_default'=>$addressDefault
            ];
            if(!$addressId = Agent_AddressModel::update($update, ['id'=>$id, 'user_id'=>$userId])){
                $db->rollBack();
                lExit('更新收货地址失败');
            }
        }else{
            $insert = [
                'address_name'=>$addressName,
                'address_tel'=>$addressTel,
                'address_province'=>$addressProvince,
                'address_city'=>$addressCity,
                'address_detail'=>$addressDetail,
                'address_status'=>$addressStatus,
                'address_default'=>$addressDefault,
                'user_id'=>$userId,
                'create_time'=>time(),
                'ts'=>date('Y-m-d H:i:s')
            ];
            if(!$addressId = Agent_AddressModel::insert($insert)){
                $db->rollBack();
                lExit('新增收货地址失败');
            }
        }
        
        $db->commit();
        lExit();
    }
    
    /**
     * @todo 设置默认收货地址
     * @param int address_id 收货地址id
     * @param int user_id 用户id
     * @return boolean
     */
    public function defaultAddressAction(){
        $id = $this->_request->getPost('address_id');
        if(!$id){
            lExit(502, '收货地址id非法');
        }
        
        $userId = $this->_request->getPost('user_id');
        if(BaseModel::isAdmin()){
            if($userId && !$user = Agent_UserModel::getRow(['id'=>$userId])){
                lExit('用户不存在');
            }
            $userId = $userId ? $userId : $_SESSION['user']['id'];
        }else{
            $userId = $_SESSION['user']['id'];
        }
        
        if(!$address = Agent_AddressModel::getRow(['id'=>$id, 'user_id'=>$userId])){
            lExit('收货地址不存在');
        }
        
        $db = Database::getInstance();
        $db->startTransaction();
        if(false===Agent_AddressModel::update(['address_default'=>0], ['address_default'=>1, 'user_id'=>$userId])){
            $db->rollBack();
            lExit('取消默认收货地址失败');
        }
        
        if(false===Agent_AddressModel::update(['address_default'=>1], ['id'=>$id, 'user_id'=>$userId])){
            $db->rollBack();
            lExit('设置默认收货地址失败');
        }
        
        $db->commit();
        lExit();
    }
    
    /**
     * @todo 删除收货地址
     * @param int address_id 收货地址id
     * @param int user_id 用户id
     * @return boolean
     */
    public function delAddressAction(){
        $id = $this->_request->getPost('address_id');
        if(!$id){
            lExit(502, '收货地址id非法');
        }
        
        $userId = $this->_request->getPost('user_id');
        if(BaseModel::isAdmin()){
            if($userId && !$user = Agent_UserModel::getRow(['id'=>$userId])){
                lExit('用户不存在');
            }
            $userId = $userId ? $userId : $_SESSION['user']['id'];
        }else{
            $userId = $_SESSION['user']['id'];
        }
        
        if(!$address = Agent_AddressModel::getRow(['id'=>$id, 'user_id'=>$userId])){
            lExit('收货地址不存在');
        }
        
        if(false===Agent_AddressModel::update(['address_status'=>'-1'], ['id'=>$id, 'user_id'=>$userId])){
            lExit('删除收货地址失败');
        }
        
        lExit();
    }
}