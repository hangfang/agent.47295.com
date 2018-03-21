<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<div class="weui_cells">
    <div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
            <p><?php echo $user['user_name'];?></p>
        </div>
        <div class="weui_cell_ft"><?php echo $user['user_mobile'];?></div>
    </div>
    <?php
        $options = '<option value="">请选择</option><option value="-1">新增收货地址</option>';
        foreach($addressList as $_address){
            $options .= '<option value="'. $_address['id'] .'" '. ($bill['address_id']===$_address['id'] ? 'selected' : '') .'>'. $_address['detail'] .'</option>';
        }
            
        echo <<<EOF
<div style="border-top: solid 1px #eee;">
    <div class="hd" style="display:none;">
        <h1 class="page_title">Radio</h1>
    </div>
    <div class="bd">
        <div class="weui_cells_title" style="display:none;">收货地址</div>
        <div class="weui_cells weui_cells_radio">
            <div class="weui_cell weui_cell_select weui_select_after">
                <div class="weui_cell_hd">
                    收货地址
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <select class="weui_select address_id" name="address_id" user_id="{$user['id']}">
                        {$options}
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
EOF;
    ?>
</div>
<div class="weui_cells weui_cells_access" id="bill_list">
    <?php
        foreach($billList as $_bill){
            $_status = empty($_tmp=BILL_STATUS_HINT[$_bill['bill_status']]) ? '未知' :$_tmp;
            $_url = sprintf('/shop/bill/detail?bill_code=%s', $_bill['bill_code']);
            $_name = date('Y-m-d H:i:s', $_bill['create_time']);
            echo <<<EOF
<a class="weui_cell" href="{$_url}">
    <div class="weui_cell_hd" style="display:none;"><img src="" alt="" style="width:20px;margin-right:5px;display:block"></div>
    <div class="weui_cell_bd weui_cell_primary">
        <p style="margin:0;">{$_name}</p>
    </div>
    <div class="weui_cell_ft">{$_status}</div>
</a>
EOF;
        }
    ?>
</div>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>
<script>
    $(function(){
        $('.address_id').on('change', function(){
            var userId = $(this).attr('user_id');
            if(!userId){
                layer.error('用户id非法');
                return false;
            }
                
            var addressId = $(this).val();
            if(addressId==-1){
                location.href = '/shop/account/addressupdate?user_id='+userId;
                return false;
            }

            var param = {"user_id":userId, "address_id":addressId};

            layer.loading(true);
            $.ajax({
                url:'/shop/account/defaultaddress',
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
        });
    })
</script>
