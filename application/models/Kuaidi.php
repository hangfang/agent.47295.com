<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class KuaidiModel extends BaseModel{
    
    public static function kdniao($com, $number){
        $queryData = array();
        $queryData['ShipperCode'] = $com;
        $queryData['LogisticCode'] = $number;
        
        $param = array();
        $param['RequestData'] = json_encode($queryData);
        $param['EBusinessID'] = KD_NIAO_APP_ID;
        $param['RequestType'] = 1002;
        $param['DataSign'] = base64_encode(md5($param['RequestData'].KD_NIAO_APP_KEY));
        $param['DataType'] = 2;
        
        
        $data = array();
        $data['data'] = $param;
        $data['url'] = KD_NIAO_API_URL;
        $data['method'] = 'POST';
        
        return http($data);
    }
}