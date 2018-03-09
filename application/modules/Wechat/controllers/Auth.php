<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * @todo 微信公众号授权登录
 */
class AuthController extends WechatController {
    /**
     * @todo 获得微信openid后，来此识别身份
     * @author fanghang@fujiacaifu.com
     */
    public function indexAction(){
        $url = $this->_request->getQuery('redirect_uri');

        if(isset($_SESSION['user']) && $_SESSION['user']['user_type']=='customer'){
            $url = $url ? $url : '/shop/index/index';
            header('location: '.$url);exit;
        }else if(isset($_SESSION['user']) && $_SESSION['user']['user_type']=='admin'){
            $url = $url ? $url : '/shop/manage/index';
            header('location: '.$url);exit;
        }
        
        $url = $url ? $url : '/wechat/auth/register';
        header('location: '.$url);exit;
    }
    
    /**
     * @todo 微信登录入口
     * @author fanghang@fujiacaifu.com
     */
    public function loginAction(){
        $url = '/wechat/auth/index';
        if($tmp=$this->_request->getQuery('redirect_uri')){
            $url = $tmp;
        }
        
        if(!empty($_SESSION['wechat']['openid'])){//去到用户中心，此时通过openid查询到客户信息
            header('location: '.$url);exit;
        }

        //微信授权登录
        header('location: '.sprintf(WECHAT_OPEN_HOST.'/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect', WECHAT_APP_ID, urlencode(BASE_URL.'/wechat/auth/code?redirect_uri='.$url)));exit;
    }
    
    /**
     * @todo 公众号授权页面，回调到此，返回code
     * @author fanghang@fujiacaifu.com
     */
    public function codeAction(){
        $code = $this->_request->getQuery('code');
        $state = $this->_request->getQuery('state');
        
        $result = $this->getAccessToken($code, $state);
        $_SESSION['wechat']['access_token'] = $result['access_token'];
        $_SESSION['wechat']['access_token_time'] = time()-50;//防止刚好7200秒，导致token过期
        $_SESSION['wechat']['refresh_token'] = $result['refresh_token'];
        $_SESSION['wechat']['refresh_token_time'] = time()-50;//30天内有效，用来刷新access_token
        $_SESSION['wechat']['openid'] = $result['openid'];
        //$_SESSION['wechat']['unionid'] = $result['unionid'];

        $user = Agent_UserModel::getRow(['user_openid'=>$result['openid']], 'id,user_name,user_mobile,user_pwd,user_openid,user_status,create_time,ts');
        if($user){
            $url = '/wechat/auth/index';
            $_SESSION['user'] = $user;
            $_SESSION['user']['user_type'] = in_array($user['user_openid'], WECHAT_ADMIN_OPENID) ? 'admin' : 'customer';
        }else{
            $url = '/wechat/auth/register';
        }
        
        
        $tmp = $this->_request->getQuery('redirect_uri');
        if($tmp && strpos($tmp, $url)===false){
            $url .= '?redirect_uri='.urlencode($tmp);
        }
        
        header('location: '.$url);exit;
    }
    
    /**
     * @todo 绑定账号的页面
     * @author fanghang@fujiacaifu.com
     */
    public function registerAction(){
        $this->_view->assign('staticDir', '/static/shop/');
        $this->_view->assign('title', '绑定账号');
        //echo $this->render('/template/wechat/auth/register.php');
        return true;
    }
    
    /**
     * @todo 绑定账号
     * @param string user_name 真实姓名
     * @param string user_mobile 手机号码
     * @param string user_pwd 密码 (123456)
     * @author fanghang@fujiacaifu.com
     */
    public function doRegisterAction(){
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
                'user_openid'=>$_SESSION['wechat']['openid'],
                'create_time'=>time(),
                'ts'=>date('Y-m-d H:i:s')
            ];
        if(!$userId = Agent_UserModel::insert($insert)){
            lExit('账号注册失败');
        }
        
        $insert['id'] = $userId;
        $_SESSION['user'] = $insert;
        lExit(0, '账号注册成功');
    }
    
    /**
     * @todo 绑定账号
     * @param string user_mobile 手机号码
     * @param string user_pwd 密码 (123456)
     * @author fanghang@fujiacaifu.com
     */
    public function bindAction(){
        $userMobile = $this->_request->getPost('user_mobile');
        if(!preg_match(PHONE_REG, $userMobile)){
            lExit('手机号码错误');
        }
        
        $userPwd = $this->_request->getPost('user_pwd');
        if(empty($userPwd)){
            lExit('密码不能为空');
        }
        
        if(!$user=Agent_UserModel::getRow(['user_mobile'=>$userMobile])){
            lExit('手机号码未注册');
        }
        
        if($user['user_openid']){
            lExit('手机号已绑定其他微信号');
        }
        
        if(md5($userPwd)!=$user['user_pwd']){
            lExit('密码不匹配');
        }
        
        if(!Agent_UserModel::update(['user_openid'=>$_SESSION['wechat']['openid']], ['user_mobile'=>$userMobile])){
            lExit('绑定账号失败');
        }
        
        $_SESSION['user'] = $user;
        $_SESSION['user']['user_openid'] = $_SESSION['wechat']['openid'];
        lExit(0, '账号绑定成功');
    }
    
    /**
     * @todo 一次性订阅消息.暂时未用到
     */
    public function subscribeAuthAction(){
        //需要每家企业在诸葛到店登记模版id
        //微信授权
        header('location: '.sprintf('https://mp.weixin.qq.com/mp/subscribemsg?action=get_confirm&appid=%s&scene=%s&template_id=%s&redirect_url=%s&reserved=test#wechat_redirect', WECHAT_APP_ID, 1000, 'fDDGjNbCi-M6f1cPXxah93IUSW1kUsYgkdJ2dZ30Tms', urlencode(BASE_URL.'/wechat/auth/subscribemsg')));exit;
    }
    
    /**
     * @todo 发送订阅消息
     * @param string openid 接收者openid
     * @param string template_id 消息模板id
     * @param string action 用户点击动作，”confirm”代表用户确认授权，”cancel”代表用户取消授权
     * @param string scene 订阅场景值
     * @param string reserved 请求带入原样返回
     */
    public function subscribeMsgAction(){
        $params = [];
        $tmp = $this->_request->getQuery('openid');
        if(!$tmp){
            lExit(502, '用户openid非法');
        }
        $params['touser'] = $tmp;
        
        $tmp = $this->_request->getQuery('template_id');
        if(!$tmp){
            lExit(502, '消息模版id非法');
        }
        $params['template_id'] = $tmp;
        
        $tmp = $this->_request->getQuery('action');
        if($tmp!=='confirm'){
            lExit(502, '用户拒绝授权');
        }
        
        $tmp = $this->_request->getQuery('scene');
        if(!$tmp){
            lExit(502, '场景值非法');
        }
        $params['scene'] = $tmp;
        
        $tmp = $this->_request->getQuery('reserved');
        
        $params['url'] = 'http://www.zhugedaodian.com';//点击消息时跳转地址
        $params['title'] = '消息标题AAA';//消息标题
        $params['data'] = [
            'username'  =>  ['value'=>'测试用户', 'color'=>'#ff0000'],
            'time'      =>  ['value'=>'2017-11-28 18:43:50', 'color'=>'#00ff00'],
            'shop_name' =>  ['value'=>'富甲集团', 'color'=>'#0000ff'],
            'money'     =>  ['value'=>'99.95', 'color'=>'#f0f000'],
        ];
        
        $cache = Cache::getInstance('wechat:');
        $cache->lpush('template.msg', json_encode($params));
        lExit();
    }
}