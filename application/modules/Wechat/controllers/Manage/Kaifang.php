<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * @todo 开房记录
 */
class Manage_KaifangController extends WechatController {
    public function init(){
        parent::init();
    }
    
    public function indexAction(){
        $this->_view->assign('title', '开房记录');
        $this->_view->display(BASE_PATH.'/template/wechat/manage_kaifang/index.php');
        return false;
    }
    
    public function getListAction(){
        $where = [];
        $type = $this->_request->getQuery('type');
        if(!$type){
            lExit(502);
        }
        
        $search = $this->_request->getQuery('search');
        if(!$search){
            lExit(502);
        }
        
        switch($type){
            case 'Mobile':
                $where['Mobile'] = $search;
                break;
            case 'CtfType':
                $where['CtfId'] = $search;
                break;
            default:
                $where['Name'] = $search;
                break;
        }
        
        if(empty($where)){
            lExit(502);
        }
        lExit(Agent_KaifangModel::getList($where, '*', '', 'Version DESC'));
    }
}