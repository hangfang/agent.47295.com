<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class AccountController extends BasicController{
    public function indexAction(){
        $this->_view->assign('title', '账户中心');
        return true;
    }
    
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
    
    public function userAddAction(){
        if(!BaseModel::isAdmin()){
            header('location: /shop/index/succ?title=异常&msg=操作未授权&btn=确定&detail=/shop/index/index');exit;
        }
        
        $this->_view->assign('title', '新增用户');
        return true;
    }
    
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
        return true;
    }
}