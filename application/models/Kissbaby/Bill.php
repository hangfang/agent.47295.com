<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Kissbaby_BillModel extends BaseModel {
    public static $_table = 'bill';
    public static $_database = 'kissbaby';
    
    /**
     * 获取订单状态的中文描述
     * @param string $status 订单状态
     * @return string
     */
    public static function getStatusHint($status){
        switch($status){
            case 'INIT':
                return '订单待确认';
            case 'CHECKED':
                return '订单待采购';
            case 'PAID':
                return '订单已付款';
            case 'POST':
                return '订单已发货';
            case 'DELETE':
                return '订单已删除';
            case 'CANCEL':
                return '订单已取消';
        }
        return '订单状态未知';
    }
    
    /**
     * 生成订单号
     * @return string
     */
    public static function getBillCode(){
        return date('YmdHis').rand(100000, 999999);
    }
}