<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * @todo 微信用户管理
 */
class Manage_UserController extends WechatController {
    public function init(){
        parent::init();
    }
    
    public function indexAction(){
        $openIdList = Wechat_ApiModel::getUsers($this->_request->getQuery('next_openid'));
        $userList = [];
        if($openIdList['count']>0){
            $tmp = [];
            foreach($openIdList['data']['openid'] as $_openId){
                $tmp[] = ['openid'=>$_openId, 'lang'=>'zh_CN'];
            }
            $tmp && $userList = Wechat_ApiModel::batchGetUserInfo($tmp)['user_info_list'];
        }
        
        $this->_view->assign('title', '粉丝管理');
        $this->_view->assign('userList', $userList);
        $this->_view->assign('nextOpenId', $openIdList['next_openid']);
        $this->_view->display(BASE_PATH.'/template/wechat/manage_user/index.php');
        return false;
    }
    
    /**
     * @todo 更新用户备注名
     * @param string openid 粉丝的openid
     * @param string remark 粉丝的备注名
     */
    public function updateRemarkAction(){
        $openId = $this->_request->getQuery('openid');
        if(empty($openId)){
            lExit(502, '粉丝的openid不能为空');
        }
        
        $remark = $this->_request->getPost('remark');
        if(strlen($remark)>30){
            lExit(502, '新备注名不能大于30字符');
        }
        
        lExit(Wechat_ApiModel::updateRemark($openId, $remark));
    }
    
    /**
     * @todo 查询用户信息
     * @param string openid 粉丝的openid
     * @param string lang 返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
     * @return {
     *      "subscribe": 1, 
     *      "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M", 
     *      "nickname": "Band", 
     *      "sex": 1, 
     *      "language": "zh_CN", 
     *      "city": "广州", 
     *      "province": "广东", 
     *      "country": "中国", 
     *      "headimgurl":  "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
     *      "subscribe_time": 1382694957,
     *      "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL"
     *      "remark": "",
     *      "groupid": 0,
     *      "tagid_list":[128,2]
     *    }
     */
    public function getUserInfoAction(){
        $openId = $this->_request->getQuery('openid');
        if(empty($openId)){
            lExit(502, '粉丝的openid不能为空');
        }
        
        $lang = $this->_request->getQuery('lang', 'zh_CN');//返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
        
        lExit(Wechat_ApiModel::getUserInfo($openId, $lang));
    }
    
    /**
     * @todo 批量查询用户信息
     * @param string user_list[0]['openid'] 粉丝的openid
     * @param string user_list[0]['lang'] 返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
     * @return {"user_info_list":[{
     *      "subscribe": 1, 
     *      "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M", 
     *      "nickname": "Band", 
     *      "sex": 1, 
     *      "language": "zh_CN", 
     *      "city": "广州", 
     *      "province": "广东", 
     *      "country": "中国", 
     *      "headimgurl":  "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
     *      "subscribe_time": 1382694957,
     *      "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL"
     *      "remark": "",
     *      "groupid": 0,
     *      "tagid_list":[128,2]
     *    },...]
     * }
     */
    public function batchGetUserInfoAction(){
        $userList = $this->_request->getQuery('user_list');
        if(empty($userList)){
            lExit(502, '粉丝的列表不能为空');
        }
        
        lExit(Wechat_ApiModel::batchGetUserInfo($userList));
    }
    
    /**
     * @todo 查询用户列表
     * @param string next_openid 第一个拉取的OPENID，不填默认从头开始拉取
     */
    public function getUsersAction(){
        $openIdList = Wechat_ApiModel::getUsers($this->_request->getQuery('next_openid'));
        $userList = [];
        if($openIdList['count']>0){
            $tmp = [];
            foreach($openIdList['data']['openid'] as $_openId){
                $tmp[] = ['openid'=>$_openId, 'lang'=>'zh_CN'];
            }
            $tmp && $userList = Wechat_ApiModel::batchGetUserInfo($tmp)['user_info_list'];
        }
        
        lExit($userList);
    }
    
    /**
     * @todo 获取黑名单列表
     * @param string begin_openid 第一个拉取的OPENID，不填默认从头开始拉取
     */
    public function getBlackListAction(){
        $beginOpenid = $this->_request->getQuery('begin_openid');
        lExit(Wechat_ApiModel::getBlackList($beginOpenid));
    }
    
    /**
     * @todo 批量拉黑用户
     * @param string openid_list 粉丝的openid列表
     */
    public function batchBlackListAction(){
        $openIdList = $this->_request->getQuery('openid_list');
        if(empty($openIdList)){
            lExit(502, '粉丝的列表不能为空');
        }
        !is_array($openIdList) && $openIdList = explode(',', $openIdList);
        lExit(Wechat_ApiModel::batchBlackList($openIdList));
    }
    
    /**
     * @todo 批量取消拉黑用户
     * @param string openid_list 粉丝的openid列表
     */
    public function batchUnblackListAction(){
        $openIdList = $this->_request->getQuery('openid_list');
        if(empty($openIdList)){
            lExit(502, '粉丝的列表不能为空');
        }
        !is_array($openIdList) && $openIdList = explode(',', $openIdList);
        lExit(Wechat_ApiModel::batchUnblackList($openIdList));
    }
}