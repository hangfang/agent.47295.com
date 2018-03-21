<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .weui_media_desc .add_to_cart {float:right;}
</style>
<div class="weui_cells">
    <form method="post" action="/shop/bill/index" target="_blank">
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
                    <option <?php $billStatus ? '' : 'selected'?> value="">订单状态</option>
                    <?php
                        foreach(BILL_STATUS_HINT as $_status=>$_hint){
                            echo '<option '. ($billStatus==$_status ? 'selected' : '') .' value='. $_status .'>'. $_hint .'</option>';
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
                
                $_time = date('Y-m-d', $_bill['create_time']);
                $_bill['bill_status'] = BILL_STATUS_HINT[$_bill['bill_status']];
                echo <<<EOF
<a href="/shop/bill/detail?bill_code={$_bill['bill_code']}" class="weui_media_box weui_media_appmsg">
    <div class="weui_media_hd">
        <img class="lazy weui_media_appmsg_thumb" data-original="{$_imgSrc}" src="{$STATIC_CDN_URL}{$staticDir}images/qrcode_for_gh_a103c9f558fa_258.jpg" >
    </div>
    <div class="weui_media_bd">
        <h4 class="weui_media_title" style="display:inline;">{$_SESSION['user']['user_name']}:{$_time}</h4><span style="float: right;color:#E64340">{$_bill['bill_status']}</span>
        <p class="weui_media_desc">{$_extra}<span class="weui_btn weui_btn_mini weui_btn_primary" onclick="location.href='/shop/bill/detail?bill_code={$_bill['bill_code']}';" style="float:right;">详情</span></p>
    </div>
</a>
EOF;
            }
        ?>
    </div>
    <?php if($data['total']>10){ echo '<a class="weui_panel_ft" href="javascript:void(0);">查看更多</a>';}?>
</div>
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