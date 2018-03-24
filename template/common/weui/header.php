<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript">
        var _speedMark = new Date();
    </script>
    <meta charset="utf-8">
    <title><?php echo $title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="<?php echo STATIC_CDN_URL;?>static/bootstrap/css/bootstrap.min.css">
<!--    <link rel="stylesheet" href="<?php echo STATIC_CDN_URL;?>static/bootstrap/css/bootstrap-theme.min.css">-->
    <script src="<?php echo STATIC_CDN_URL;?>static/public/js/jquery.min.js"></script>
    <script src="<?php echo STATIC_CDN_URL;?>static/public/js/lightbox.js"></script>
    <script src="<?php echo STATIC_CDN_URL;?>static/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo STATIC_CDN_URL;?>static/shop/js/jquery.lazyload.js?v=<?php echo STATIC_VERSION;?>"></script>
    <link rel="stylesheet" href="<?php echo STATIC_CDN_URL;?>static/weui/css/weui.css?v=<?php echo STATIC_VERSION;?>"/>
    <link rel="stylesheet" href="<?php echo STATIC_CDN_URL;?>static/weui/css/common.css?v=<?php echo STATIC_VERSION;?>"/>
    <style type="text/css">
        body,button, input, select, textarea,h1 ,h2, h3, h4, h5, h6 { font-family: Microsoft YaHei,'宋体' , Tahoma, Helvetica, Arial, "\5b8b\4f53", sans-serif;}
        p.weui_tabbar_label {padding: 0; margin:0;}
        .weui_tabbar_icon + .weui_tabbar_label {margin: 0;}
        .weui_panel_bd p {margin:0;}
        .cart_plus {
            position: absolute;
            cursor: pointer;
            bottom: -3px;
            right: 0px;
            width: 40px;
            height: 40px;
            background: url(/static/shop/images/plus.png) no-repeat 8px 8px #F54D5B;
            color: #fff;
            text-align: center;
            line-height: 33px;
            border: 4px solid #fff;
            font-size: 11px;
            border-top-left-radius: 50%;
            border-top-right-radius: 50%;
            border-top-right-radius: 50%;
            /* border-bottom-right-radius: 50%; */
            border-bottom-left-radius: 50%;
            border-bottom-right-radius: 50%;
            border-top-left-radius: 50%;
            border-bottom-left-radius: 50%;
        }
        .cart_minus {
            position: absolute;
            cursor: pointer;
            bottom: -3px;
            right: 7rem;
            width: 40px;
            height: 40px;
            background: url(/static/shop/images/minus.png) no-repeat 8px 8px #F54D5B;
            color: #fff;
            text-align: center;
            line-height: 33px;
            border: 4px solid #fff;
            font-size: 11px;
            border-top-left-radius: 50%;
            border-top-right-radius: 50%;
            border-top-right-radius: 50%;
            /* border-bottom-right-radius: 50%; */
            border-bottom-left-radius: 50%;
            border-bottom-right-radius: 50%;
            border-top-left-radius: 50%;
            border-bottom-left-radius: 50%;
        }
        .cart_number {
            position: absolute;
            bottom: -3px;
            right: 3.1rem;
            width: 50px;
            height: 40px;
            overflow:hidden;
            color: red;
            text-align: center;
            line-height: 38px;
            font-size: 11px;
        }
    </style>
    <?php if(ini_get('environ') === 'product'){ ?>
    <script>
        window.onerror = function(){return true;};
    </script>
    <?php }?>
    <script src="<?php echo STATIC_CDN_URL;?>static/weui/js/jweixin-1.2.0.js?v=2016-04-07"></script>
    <script>
        var openInWechat = navigator.userAgent.toLowerCase().match(/MicroMessenger/i)=="micromessenger" ? true : false;
        if(openInWechat){
            wx.config({
                debug: <?php echo $jsapi['debug']; ?>, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: '<?php echo $jsapi['appId']; ?>', // 必填，公众号的唯一标识
                timestamp: <?php echo $jsapi['timestamp']; ?>, // 必填，生成签名的时间戳
                nonceStr: '<?php echo $jsapi['nonceStr']; ?>', // 必填，生成签名的随机串
                signature: '<?php echo $jsapi['signature']; ?>', // 必填，签名，见附录1
                jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','onMenuShareQZone','startRecord','stopRecord','onVoiceRecordEnd','playVoice','pauseVoice','stopVoice','onVoicePlayEnd','uploadVoice','downloadVoice','chooseImage','previewImage','uploadImage','downloadImage','translateVoice','getNetworkType','openLocation','getLocation','hideOptionMenu','showOptionMenu','hideMenuItems','showMenuItems','hideAllNonBaseMenuItem','showAllNonBaseMenuItem','closeWindow','scanQRCode','chooseWXPay','openProductSpecificView','addCard','chooseCard','openCard'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
            });
        }
    </script>
</head>
<body>
    <div id="container" class=''>