<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .weui_media_desc .add_to_cart {float:right;}
</style>
<div class="weui_cells">
    <form method="post" action="/shop/bill/index">
        <div class="weui_cell weui_cell_select weui_select_after" style="float:left;">
            <div class="weui_cell_hd">
            </div>
            <div class="weui_cell_bd weui_cell_primary" style="-webkit-box-flex: 2;-webkit-flex: 2;-ms-flex: 2;flex: 2;">
                <select class="weui_select" name="user_id" id='bill_user'>
                    <option <?php $userId ? '' : 'selected'?> value="">用户</option>
                    <?php
                        foreach($userList as $_user){
                            echo '<option '. ($userId==$_user['id'] ? 'selected' : '') .' value='. $_user['id'] .'>'. $_user['user_name'] .'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="weui_cell weui_cell_select weui_select_after">
            <div class="weui_cell_hd">
            </div>
            <div class="weui_cell_bd weui_cell_primary" style="-webkit-box-flex: 2;-webkit-flex: 2;-ms-flex: 2;flex: 2;">
                <select class="weui_select" name="bill_status" id='bill_status' style="padding-left:0px">
                    <option <?php empty($billStatus[0]) ? 'selected' : ''?> value="">订单状态</option>
                    <?php
                        foreach(BILL_STATUS_HINT as $_status=>$_hint){
                            echo '<option '. (!empty($billStatus[0]) && $billStatus[0]==$_status ? 'selected' : '') .' value='. $_status .'>'. $_hint .'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
    </form>
</div>
<div class="weui_panel weui_panel_access">
    <div class="weui_panel_hd" style='display:none;'><?php echo $title;?></div>
    <div class="weui_panel_bd">
        <?php 
            $STATIC_CDN_URL = STATIC_CDN_URL;
            for($i=0,$len=count($data['list']); $i<$len; $i++){
                $_bill = $data['list'][$i];

                $_imgSrc = empty($_bill['bill_image']) ? '' : str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_bill['bill_image']);

                $_extra = '<span class="weui_desc_extra">数量:'. $_bill['bill_product_num'] .'</span>';
                if(BaseModel::isAdmin() || in_array($_bill['bill_status'], ['CHECKED', 'PAID', 'POST'])){
                    $_extra = '<span class="weui_desc_extra">售价:'. $_bill['bill_sale_money'] .'</span>'.$_extra;
                }
                
                if(BaseModel::isAdmin()){
                    $_extra .= '<span class="weui_btn weui_btn_mini weui_btn_primary express_num" style="float:right;" bill_code="'.$_bill['bill_code'].'">扫码</span>';
                }
                
                $_time = date('Y-m-d', $_bill['create_time']);
                $_bill['bill_status'] = BILL_STATUS_HINT[$_bill['bill_status']];
                echo <<<EOF
<a href="/shop/bill/detail?bill_code={$_bill['bill_code']}" class="weui_media_box weui_media_appmsg">
    <div class="weui_media_hd">
        <img class="lazy weui_media_appmsg_thumb" data-original="{$_imgSrc}" src="{$STATIC_CDN_URL}{$staticDir}images/qrcode_for_gh_a103c9f558fa_258.jpg" >
    </div>
    <div class="weui_media_bd">
        <h4 class="weui_media_title" style="display:inline;">{$_SESSION['user']['user_name']}:{$_time}</h4><span style="float: right;color:#E64340">{$_bill['bill_status']}</span>
        <p class="weui_media_desc">{$_extra}</p>
    </div>
</a>
EOF;
            }
        ?>
    </div>
    <?php if($data['total']>10){ echo '<a class="weui_panel_ft" href="javascript:void(0);">查看更多</a>';}?>
</div>
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
            jsApiList: ['scanQRCode'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });

         $('#container').on('click', '.express_num', function(e){
            var _this = this;
            wx.scanQRCode({
                needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function (res) {
                    var expressNum = res.resultStr.replace(/\s+/g, ''); // 当needResult 为 1 时，扫码返回的结果
                    if(expressNum && expressNum.length<5){
                        layer.error('物流单号长度错误');
                        return false;
                    }
                    var tmp = expressNum.split(',');
                    if(tmp.length==2){
                        expressNum = tmp[1].replace(/\s+/g, '');
                    }    

                    var param = {"express_num":expressNum};
                    var tmp = $(_this).attr('bill_code');
                    if(!tmp){
                        layer.error('订单号非法');
                        return false;
                    }
                    param.bill_code = tmp;

                    layer.loading(true);

                    $.ajax({
                        url:'/shop/bill/updateexpress',
                        dataType:'json',
                        data:param,
                        type:'post',
                        success:function(data, xhr){
                            layer.loading(false);
                            if(!data){
                                layer.error('请求失败,请稍后再试...');
                                return false;
                            }

                            if(data.rtn!=0){
                                layer.error(data.error_msg);
                                return false;
                            }

                            layer.toast('成功');
                        }
                    });
                    return false;
                }
            });
            
            return false;
        });
    }
</script>
<script>
    (function(){
        var total = <?php echo $data['total'];?>;
        var offset = 10;
        $(function(){
            var xhrIng = false;
            $('#bill_user,#bill_status').change(function(){
                $(this).closest('form').submit();
            });
            
            $('.weui_panel_ft').click(function(){
                var _this = this;
                if(offset>=total){
                    $(_this).remove();
                    return false;
                }
                
                var param = {'offset':offset, 'length':10};
                var userId = $('#bill_user').val();
                if(userId){
                    param.user_id = userId;
                }
                
                param.bill_status = $('#bill_status').val();
                
                $.ajax({
                    url:'/shop/bill/index',
                    type:'get',
                    dataType:'json',
                    data:param,
                    beforeSend:function(xhr){
                        if(xhrIng){
                            xhr.abort();
                            return false;
                        }

                        xhrIng = true;
                    },
                    complete:function(){
                        xhrIng = false;
                    },
                    success:function(data, xhr){
                        if(!data){
                            layer.error('请求失败,请稍后再试...');
                            return false;
                        }

                        if(data.rtn!=0){
                            layer.error(data.error_msg);
                            return false;
                        }

                        var html = '';
                        var billStatusHint = <?php echo json_encode(BILL_STATUS_HINT);?>;
                        for(var i=0,len=data.data.list.length;i<len;i++){

                            var bill = data.data.list[i];
                            var extra = '<span class="weui_desc_extra">商品数:'+ bill['bill_product_num'] +'</span>';
                            <?php
                                if(BaseModel::isAdmin()){
                                    echo 'extra = \'<span class="weui_desc_extra">成交价:￥\'+ bill[\'bill_sale_money\'] +\'</span>\'+extra;';
                                }else{
                                    echo 'if(bill[\'bill_status\']===\'CHECKED\'||bill[\'bill_status\']===\'PAID\'||bill[\'bill_status\']===\'POST\'){extra = \'<span class="weui_desc_extra">成交价:￥\'+ bill[\'bill_sale_money\'] +\'</span>\'+extra;}';
                                }
                            ?>
                            extra += '<span class="weui_desc_extra">下单时间:'+ bill['create_time'] +'</span>';

                            var imgSrc = bill['bill_image'] ? bill['bill_image'].replace('<?php echo CDN_URL_PLACEHOLDER;?>', '<?php echo IMG_CDN_URL;?>') : '';
                            
                            html += '<a href="/shop/bill/detail?bill_code='+ bill['bill_code'] +'" class="weui_media_box weui_media_appmsg">\
    <div class="weui_media_hd">\
        <img class="weui_media_appmsg_thumb" src="'+ imgSrc +'" onerror="this.src=\'<?php echo $STATIC_CDN_URL.$staticDir;?>images/qrcode_for_gh_a103c9f558fa_258.jpg\'" >\
    </div>\
    <div class="weui_media_bd">\
        <h4 class="weui_media_title" style="display:inline;">订单号:'+ bill['bill_code'] +'</h4><span style="float: right;color:#E64340">'+ billStatusHint[bill['bill_status']] +'</span>\
        <p class="weui_media_desc">'+ extra +'<span class="weui_btn weui_btn_mini weui_btn_primary" onclick="location.href=\'/shop/bill/detail?bill_code='+ bill['bill_code'] +'\';" style="float:right;">详情</span></p>\
    </div>\
</a>';
                        }

                        $('.weui_panel_bd').append(html);
                        offset += 10;
                        if(offset>=total){
                            $(_this).remove();
                            return false;
                        }
                        xhrIng = false;
                    }
                });
            });
        })
    })();
</script>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>