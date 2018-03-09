<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class WechatController extends Yaf_Controller_Abstract {

    public function init(){
        $moduleName = strtolower($this->_request->module);
        $controllerName = strtolower($this->_request->controller);
        $actionName = strtolower($this->_request->action);
        
        if($controllerName==='msg'){
            return true;
        }
        
        header('content-type:text/html;charset=utf-8', true);
        $viewpath = BASE_PATH.'/template/'.$moduleName.'/';
        $this->setViewpath($viewpath);
        $this->_view->assign('viewPath', $viewpath);
        $this->_view->assign('moduleName', $moduleName);
        $this->_view->assign('controllerName', $controllerName);
        $this->_view->assign('actionName', $actionName);
        $this->_view->assign('staticDir', '/static/'.$moduleName .'/');

            
        if(empty($_SESSION['wechat']['access_token'])){
            if(!in_array($actionName, ['login', 'code', 'register'])){
                header('location: /wechat/auth/login?redirect_uri='. urlencode(BASE_URL.$this->_request->getRequestUri()));
                exit;
            }else{
                return true;
            }
        }else if(time()-$_SESSION['wechat']['access_token_time']>7200){//access_token过期
            if(time()-$_SESSION['wechat']['refresh_token_time']<30*86400){//access_token过期
                return $this->refreshToken();
            }

            header('location: /wechat/auth/login?redirect_uri='. urlencode(BASE_URL.$this->_request->getRequestUri()));
            exit;
        }
        
        if(!empty($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']==='customer'){
            header('location: /shop/index/index');exit;
        }elseif(!empty($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']==='admin'){
            if($moduleName!='wechat' && $controllerName!=='account' && $actionName!=='index'){
                header('location: /wechat/account/index');exit;
            }
        }else{
            if($moduleName!='wechat' && $controllerName!=='auth' && $actionName!=='index'){
                header('location: /wechat/auth/index');exit;
            }
        }
    }
    
    /**
     * 刷新access_token
     * @author fanghang@fujiacaifu.com
     */
    protected function refreshToken(){
        if(empty($_SESSION['wechat']['refresh_token'])){//此时去微信登录页面重新授权
            log_message('error', 'get refresh_token failed, wechat: '. print_r($_SESSION['wechat'], true));
            return false;
        }
        
        $args = ['url'=>sprintf(WECHAT_API_HOST.'/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s', WECHAT_APP_ID, $_SESSION['wechat']['refresh_token'])];
        $result = http($args);
        if(isset($result['errcode'])){
            log_message('error', 'get sns_user_info failed, wechat: '. print_r($result, true));
            return false;
        }

        $_SESSION['wechat']['access_token'] = $result['access_token'];
        $_SESSION['wechat']['access_token_time'] = time()-50;//防止刚好7200秒，导致token过期
        $_SESSION['wechat']['refresh_token'] = $result['refresh_token'];
        $_SESSION['wechat']['refresh_token_time'] = time()-50;//30天内有效，用来刷新access_token
        $_SESSION['wechat']['openid'] = $result['openid'];
        
        return true;
    }
    
    /**
     * 获取access_token
     * @param string code 微信返回的授权码
     * @param string state 微信带回的state
     */
    protected function getAccessToken($code, $state){
        $args = ['method'=>'get', 'url'=>sprintf(WECHAT_API_HOST.'/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code', WECHAT_APP_ID, WECHAT_APP_SECRET, $code)];
        $result = http($args);
        if(isset($result['errcode'])){
            log_message('error', '微信授权失败, result: '. print_r($result, true));
            
            $url = '/wechat/auth/index';
            if($tmp=$this->_request->getQuery('redirect_uri')){
                $url = $tmp;
            }
        
            header('refresh:3;url='.WECHAT_OPEN_HOST.sprintf('/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect', WECHAT_APP_ID, urlencode(BASE_URL.'/wechat/auth/code?redirect_uri='.$url)));
            exit(isset($result['errmsg']) ? $result['errmsg'] : '微信授权失败');
        }
        
        return $result;
    }
}