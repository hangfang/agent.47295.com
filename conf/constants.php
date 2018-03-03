<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
define('BASE_URL', 'http://agent.47295.com');//当前系统地址

//OSS配置
define('OSS_BUCKET', 'saas-static');
define('OSS_ACCESS_ID', 'LTAIGq7u3xTsJFCZ');
define('OSS_ACCESS_KEY', 'lrGzGMYPb4D0fNBBUXAVJh9Ex8DUrF');
define('OSS_ENDPOINT', 'oss-cn-shenzhen.aliyuncs.com');
define('OSS_URL', 'http://saas-static.oss-cn-shenzhen.aliyuncs.com/');

//商品重量精度
define('WEIGHT_PRECISION', 3);

//商品零售价精度
define('PRICE_PRECISION', 2);

// 百分比精度
define('PERCENT_PRECISION', 4);

define('SERVICE_TEL', '15914186940');

//短信验证码有效期,10分钟
define('SMS_TOKEN_LIFT_TIME', 600);

//简单判断手机号码正则
define('PHONE_REG', '/^1[\d]{10}$/');

//邮箱配置
define('MAIL_CHANNEL', 'exmail');
define('ALIYUN_MAIL_ACCESS_KEY_ID', 'LTAIJtec1o9LM09B');
define('ALIYUN_MAIL_ACCESS_KEY', '89DFImZx8v4p46dhexaknzssL6Akrz');
define('MAIL_STAT', ['to'=>['295296780@qq.com'=>'王琳玲'], 'bc'=>['470739703@qq.com'=>'方航']]);


define('WECHAT_OPEN_HOST', 'https://open.weixin.qq.com');
define('WECHAT_API_HOST', 'https://api.weixin.qq.com');

if(PHP_ENV==='product'){
    define('WECHAT_WEB_JS_DEBUG', false);//web JavaScript调试模式
    define('WECHAT_APP_ID', 'wxc170b80185652191');
    define('WECHAT_APP_SECRET', '588a521441c177d77f7f5b40750e0a8f');
    define('WECHAT_TOKEN', 'AgentWangLinLing');
    define('WECHAT_ENCODING_AES_KEY', 'WskaczySJfTf5Jx5iteiSdpSNXpUETVxMtlXhwMuij9');
}else{
    define('WECHAT_WEB_JS_DEBUG', true);//web JavaScript调试模式
    define('WECHAT_APP_ID', 'wxda5cc06be9c200f6');
    define('WECHAT_APP_SECRET', '8d3f59778c0c214088c886845d57d8ce');
    define('WECHAT_TOKEN', 'zhugedaodian');
    define('WECHAT_ENCODING_AES_KEY', false);
}

define('WECHAT_ADMIN_OPENID', 'ohwjvw1QPmm0YLy3yKhjGYg4qS_g');
define('WECHAT_HK_ACCOUNT', 'WangLin-ling');

/*新浪ip查询*/
define('SINA_IP_LOOKUP_API_URL', 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=%s');

/*快递鸟*/
define('KD_NIAO_APP_ID', '1256662');
define('KD_NIAO_APP_KEY', '998e72f8-d8f2-4b56-9b55-4c3510d23275');
define('KD_NIAO_API_URL', 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx');

/*腾讯地图*/
define('TENCENT_MAP_APP_KEY', 'J7CBZ-YV43X-PVS4E-ZGYVP-KF2T3-A3BQZ');
define('TENCENT_MAP_APP_URL', 'http://apis.map.qq.com/ws');