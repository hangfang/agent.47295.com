<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * @todo 微信账户管理
 */
class Manage_AccountController extends WechatController {
    public function init(){
        parent::init();
    }
    
    public function indexAction(){
        $this->_view->assign('title', '公众号管理');
        $this->_view->display(BASE_PATH.'/template/wechat/manage_account/index.php');
        return false;
    }
    
    /**
     * @todo 生成临时二维码
     * @param string scene_str 场景id.1~64位
     * @param int expire_seconds 有效时间.秒,最大30天
     */
    public function getTempQrcodeAction(){
        $sceneStr = $this->_request->getPost('scene_str');
        if(strlen($sceneStr)<1 || strlen($sceneStr)>64){
            lExit(502, '场景id必须为1~64个字符');
        }
        
        $expireSeconds = intval($this->_request->getPost('expire_seconds', 30*86400));
        if(empty($expireSeconds) || $expireSeconds>30*86400 || $expireSeconds<=0){
            lExit(502, '二维码过期时间无效，最大不超过2592000（即30天）');
        }
        $params = ['action_name'=>'QR_STR_SCENE', 'action_info'=>['scene'=>['scene_str'=>$sceneStr]], 'expire_seconds'=>$expireSeconds];
        $rt = Wechat_ApiModel::getQrcode($params);
        if(!isset($rt['url'])){
            lExit($rt);
        }
        
        echo QRcode::png($rt['url']);exit;
    }
    
    /**
     * @todo 生成永久二维码
     * @param string scene_str 场景id.1~64位
     */
    public function getQrcodeAction(){
        $sceneStr = $this->_request->getPost('scene_str');
        if(strlen($sceneStr)<1 || strlen($sceneStr)>64){
            lExit(502, '场景id必须为1~64个字符');
        }
        
        $params = ['action_name'=>'QR_LIMIT_STR_SCENE', 'action_info'=>['scene'=>['scene_str'=>$sceneStr]]];
        $rt = Wechat_ApiModel::getQrcode($params);
        if(!isset($rt['url'])){
            lExit($rt);
        }
        
        echo QRcode::png($rt['url']);exit;
    }
    
    /**
     * @todo 获取短连接
     * @param string url 原始url
     */
    public function long2ShortAction(){
        $url = $this->_request->getPost('url', 'https://www.zhugedaodian.com');
        if(!preg_match('#^[http://|https://|weixin://wxpay][^\s]+$#', $url)){
            lExit(502, '原始url格式错误');
        }
        
        lExit(Wechat_ApiModel::long2Short($url));
    }
}