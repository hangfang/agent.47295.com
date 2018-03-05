<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * @todo 微信公众号管理
 */
class AccountController extends WechatController {
    public function indexAction(){
        header('location: /shop/index/notfound?code=404&msg=敬请期待...&title=公众号管理');exit;
    }
}