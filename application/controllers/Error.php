<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 * @see http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 * @author fanghang@me.com
 */
class ErrorController extends Yaf_Controller_Abstract {
     
    public function errorAction($exception){
        $request = new Yaf_Request_Http();
        switch($exception->getCode()) {
            case YAF_ERR_NOTFOUND_CONTROLLER:
                log_message('error', 'YAF_ERR_NOTFOUND_CONTROLLER: '. $request->getRequestUri());
                header('location: /shop/index/succ?title=异常&msg=请求页面不存在&btn=确定&detail=/shop/index/index');exit;
            case YAF_ERR_NOTFOUND_ACTION:
                log_message('error', 'YAF_ERR_NOTFOUND_ACTION: '. $request->getRequestUri());
                header('location: /shop/index/succ?title=异常&msg=请求页面不存在&btn=确定&detail=/shop/index/index');exit;
            case YAF_ERR_NOTFOUND_MODULE:
                log_message('error', 'YAF_ERR_NOTFOUND_MODULE: '. $request->getRequestUri());
                header('location: /shop/index/succ?title=异常&msg=请求页面不存在&btn=确定&detail=/shop/index/index');exit;
            case YAF_ERR_NOTFOUND_VIEW:
                log_message('error', 'YAF_ERR_NOTFOUND_VIEW: '. $request->getRequestUri());
                header('location: /shop/index/succ?title=异常&msg=请求页面不存在&btn=确定&detail=/shop/index/index');exit;
        }
        
        log_message('error', 'SERVER_INTERNAL_ERROR: '. $msg=$exception->getMessage());
        header('location: /shop/index/succ?title=异常&msg='. $msg .'&btn=确定&detail=/shop/index/index');exit;
    }
}
