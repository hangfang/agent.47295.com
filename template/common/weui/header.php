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
</head>
<body>
    <div id="container" class=''>