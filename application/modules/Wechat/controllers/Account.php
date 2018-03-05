<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * @todo 微信公众号管理
 */
class AccountController extends WechatController {
    public function indexAction(){
        $this->_view->assign('code', '404');
        $this->_view->assign('msg', '敬请期待...');
        $this->_view->assign('title', '公众号管理');
        return true;
    }
}