<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * @name LogPlugin
 * @author root
 * @desc 插件
 * @see http://php.net/manual/en/class.yaf-plugin-abstract.php
 */
class LogPlugin extends Yaf_Plugin_Abstract {

	public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        $post = $request->getPost();
        $post = $post ? $post : file_get_contents('php://input');
        $get = $request->getQuery();
        $cookie = $_COOKIE;
        
        $requestStr = 'ip:'.ip_address()."\t".'get:'.json_encode($get, JSON_UNESCAPED_UNICODE)."\t".'post:'.json_encode($post, JSON_UNESCAPED_UNICODE)."\t".'cookie:'.json_encode($cookie, JSON_UNESCAPED_UNICODE);
        
        Yaf_Registry::set('log_message_all', $requestStr);
	}

    public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        //echo '<p>routerShutdown</p>';
    }

    public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        //echo '<p>dispatchLoopStartup</p>';
    }

    public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        //echo '<p>preDispatch</p>';
    }

    public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        //echo '<p>postDispatch</p>';
    }

    public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        //echo '<p>dispatchLoopShutdown</p>';
        log_message('all', Yaf_Registry::get('log_message_all'));
    }
}
