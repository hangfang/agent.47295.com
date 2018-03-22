<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * 更新物流信息
 */
class ExpressController extends BasicController{
    public function updateAction(){
        $billList = Kissbaby_BillModel::getList(['express_com!='=>'', 'express_num!='=>'', 'express_status!='=>3], 'id,express_com,express_num,express_detail,express_status');
        foreach($billList as $_bill){
            $_detail = KuaidiModel::query($_bill['express_com'], $_bill['express_num']);
            if(!$_detail){
                echo '查询订单['. $_bill['id'] .']物流信息, FAILED'."\n";
                continue;
            }
            
            $_update=['express_status'=>$_detail['State']];
            if(!empty($_detail['Traces']) && is_array($_detail['Traces'])){
                $_update['express_detail'] = json_encode($_detail['Traces'], JSON_UNESCAPED_UNICODE);
            }
            if(false===Kissbaby_BillModel::update($_update, $_where=['id'=>$_bill['id']])){
                log_message('error', __FUNCTION__.',更新物流信息失败, update:'.print_r($_update, true).', where:'.print_r($_where, true));
                echo '更新订单['. $_bill['id'] .']物流信息, FAILED'."\n";
                continue;
            }
            
            echo '更新订单['. $_bill['id'] .']物流信息, SUCC'."\n";
        }
        
        exit(date('Y-m-d H:i:s').' '.'更新物流信息成功'."\n");
    }
}
