<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class KuaidiModel{
    /**
     * 查询物流
     * @param string $com 物流公司代码
     * @param string $number 快递单号
     * @return array 查询结果
     */
    public static function query($com, $number){
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
        $data['url'] = KD_NIAO_API_QUERY_URL;
        $data['method'] = 'POST';
        
        return http($data);
    }
    
    /**
     * 预约取件
     * @param string $com 物流公司代码
     * @param string $number 快递单号
     * @return array 查询结果
     */
    public static function order($com, $number){
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
        $data['url'] = KD_NIAO_API_QUERY_URL;
        $data['method'] = 'POST';
        
        return http($data);
    }
}