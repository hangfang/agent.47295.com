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
    public static function order($com, $billCode, $address){
        $queryData = array();
        $queryData['ShipperCode'] = $com;//快递公司编码
        $queryData['CallBack'] = '琳玲港货';//商户标识
        $queryData['OrderCode'] = $billCode;//订单编号
        $queryData['PayType'] = 1;//邮费支付方式:1-现付，2-到付，3-月结，4-第三方支付
        $queryData['ExpType'] = 1;//快递类型：1-标准快件
        $queryData['Receiver'] = [
            'Name'  =>  mb_convert_encoding($address['address_name'], 'UTF-8', mb_detect_encoding($address['address_name'])),//收件人
            'Mobile'  =>  $address['address_tel'],
            'ProvinceName'  =>  trim(mb_convert_encoding($address['address_province'], 'UTF-8', mb_detect_encoding($address['address_province'])), '省').'省',//收件省（如广东省，不要缺少“省”）
            'CityName'  =>  trim(mb_convert_encoding($address['address_city'], 'UTF-8', mb_detect_encoding($address['address_city'])), '市').'市',//收件市（如深圳市，不要缺少“市”）
            'Address'  =>  mb_convert_encoding($address['address_detail'], 'UTF-8', mb_detect_encoding($address['address_detail'])),//收件人详细地址
        ];
        
        $queryData['Sender'] = EXPRESS_SENDER;
        
        $queryData['Commodity'] = [
            ['GoodsName' =>  '生活用品']
        ];

        $tmp = json_encode($queryData, JSON_UNESCAPED_UNICODE);
        if(json_last_error()){
            log_message('error', print_r([$queryData, json_last_error_msg()], true));
        }
        
        $param = array();
        $param['RequestData'] = $tmp;
        $param['EBusinessID'] = KD_NIAO_APP_ID;
        $param['RequestType'] = 1001;
        $param['DataSign'] = base64_encode(md5($param['RequestData'].KD_NIAO_APP_KEY));
        $param['DataType'] = 2;
        
        
        $data = array();
        $data['data'] = $param;
        $data['header'] = PHP_ENV==='product' ? ['Content-Type: application/json;charset=UTF-8'] : [];
        $data['url'] = KD_NIAO_API_ORDER_URL;
        $data['method'] = 'POST';
        
        return http($data);
    }
    
    /**
     * 单号识别
     * @param string $number 快递单号
     * @return array 查询结果
     */
    public static function recognize($number){
        $queryData = array();
        $queryData['LogisticCode'] = $number;
        
        $param = array();
        $param['RequestData'] = json_encode($queryData);
        $param['EBusinessID'] = KD_NIAO_APP_ID;
        $param['RequestType'] = 2002;
        $param['DataSign'] = base64_encode(md5($param['RequestData'].KD_NIAO_APP_KEY));
        $param['DataType'] = 2;
        
        
        $data = array();
        $data['data'] = $param;
        $data['url'] = KD_NIAO_API_RECOGNIZE_URL;
        $data['method'] = 'POST';
        
        return http($data);
    }
}