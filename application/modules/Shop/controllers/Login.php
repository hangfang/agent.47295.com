<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
class LoginController extends BasicController{
    /**
     * @todo 登录页面
     * @author fanghang@fujiacaifu.com
     */
    public function indexAction(){
        $this->_view->assign('title', '登录');
        //echo $this->render('/template/wechat/auth/register.php');
        return true;
    }
    
    /**
     * @todo 注册账号
     * @param string user_name 真实姓名
     * @param string user_mobile 手机号码
     * @param string user_pwd 密码 (123456)
     * @author fanghang@fujiacaifu.com
     */
    public function registerAction(){
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
        $_SESSION['user'] = $insert;
        $_SESSION['user']['user_type'] = in_array($userMobile, ADMIN_MOBILE_LIST) ? 'admin' : 'customer';
        lExit(0, '账号注册成功');
    }
    
    /**
     * @todo 登录账号
     * @param string user_mobile 手机号码
     * @param string user_pwd 密码 (123456)
     * @author fanghang@fujiacaifu.com
     */
    public function doLoginAction(){
        $userMobile = $this->_request->getPost('user_mobile');
        if(!preg_match(PHONE_REG, $userMobile)){
            lExit('手机号码错误');
        }
        
        $userPwd = $this->_request->getPost('user_pwd');
        if(empty($userPwd)){
            lExit('密码不能为空');
        }
        
        if(!$user = Agent_UserModel::getRow(['user_mobile'=>$userMobile])){
            lExit('手机号码未注册');
        }
        
        if(md5($userPwd)!=$user['user_pwd']){
            lExit('密码不匹配');
        }
        
        if(!Agent_UserModel::update(['ts'=>date('Y-m-d H:i:s')], ['user_mobile'=>$userMobile])){
            lExit('登录失败');
        }
        
        $_SESSION['user'] = $user;
        $_SESSION['user']['user_type'] = in_array($user['user_mobile'], ADMIN_MOBILE_LIST) ? 'admin' : 'customer';
        lExit(0, '登录成功');
    }
    
    public function logoutAction(){
        session_destroy();
        header('location: /');exit;;
    }
}