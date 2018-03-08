<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

/**
 * @var string 当前系统域名
 */
define('BASE_URL', 'http://'.SERVER_NAME);

/**
 * @var string 静态文件版本号
 */
define('STATIC_VERSION', '######');

//OSS配置
define('OSS_BUCKET', 'agent-47295');
define('OSS_ACCESS_ID', '3IYCIDFZcGbGpnUX');
define('OSS_ACCESS_KEY', 'AjWQTwY1fq6puNswqPoB0BnXVg1TZI');
define('OSS_ENDPOINT', 'oss-cn-shenzhen.aliyuncs.com');
define('OSS_URL', 'http://'.OSS_BUCKET.'.oss-cn-shenzhen.aliyuncs.com/');

/**
 * @var string 图片文件CDN域名
 */
define('IMG_CDN_URL', PHP_ENV==='product' ? 'http://cdn.47295.com/upload/kissbaby/' : BASE_URL.'/upload/kissbaby/');

/**
 * @var string 静态文件CDN域名
 */
define('STATIC_CDN_URL', PHP_ENV==='product'? 'http://static.47295.com/' : BASE_URL);

/**
 * @var string CDN域名占位符
 */
define('CDN_URL_PLACEHOLDER', '{CDN_URL}');

//短信验证码有效期,10分钟
define('SMS_TOKEN_LIFT_TIME', 600);

/**
 * @var string 简单判断手机号码正则
 */
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
    define('WECHAT_ENCPRYPT_TYPE', false);//消息加密方式
    define('WECHAT_APP_ID', 'wx3779049492b4a7ad');
    define('WECHAT_APP_SECRET', 'b500556d6ff2af79022c373b6384b988');
    define('WECHAT_TOKEN', 'AgentWangLinLing');
    define('WECHAT_ENCODING_AES_KEY', 'WskaczySJfTf5Jx5iteiSdpSNXpUETVxMtlXhwMuij9');
    
    define('WECHAT_OPENID', 'gh_a103c9f558fa');//公众号的openid
    define('WECHAT_ADMIN_OPENID', ['o1pON0QK-CT6zS0ikg_Ks6GGxpPo']);//管理员的openid
}else{
    define('WECHAT_WEB_JS_DEBUG', true);//web JavaScript调试模式
    define('WECHAT_ENCPRYPT_TYPE', false);//消息加密方式
    define('WECHAT_APP_ID', 'wxda5cc06be9c200f6');
    define('WECHAT_APP_SECRET', '8d3f59778c0c214088c886845d57d8ce');
    define('WECHAT_TOKEN', 'AgentWangLinLing');
    define('WECHAT_ENCODING_AES_KEY', false);
    
    define('WECHAT_OPENID', 'gh_d48b747c4a68');//公众号的openid
    define('WECHAT_ADMIN_OPENID', ['ovzFrwIgjd9soE7SzlM_jo_Otuw0']);//管理员的openid
}

/**
 * @var string 业务员微信号
 */
define('WECHAT_HK_ACCOUNT', 'WangLin-ling');

/**
 * @var string 业务员电话
 */
define('SERVICE_TEL', '+86 15914186940');

/*新浪ip查询*/
define('SINA_IP_LOOKUP_API_URL', 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=%s');

/*快递鸟*/
define('KD_NIAO_APP_ID', '1256662');
define('KD_NIAO_APP_KEY', '998e72f8-d8f2-4b56-9b55-4c3510d23275');
define('KD_NIAO_API_URL', 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx');

/*腾讯地图*/
define('TENCENT_MAP_APP_KEY', 'J7CBZ-YV43X-PVS4E-ZGYVP-KF2T3-A3BQZ');
define('TENCENT_MAP_APP_URL', 'http://apis.map.qq.com/ws');

//kissbaby
define('KISSBABY_KEYWORD', '港货 母婴 药品 奶粉 辅食 奶瓶 化妆品 美颜 保养 包包');
define('KISSBABY_DESCRIPTION', '归功化妆品、母婴等产品香港代购服务');