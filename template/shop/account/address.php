<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>a.default_address {border:1px dashed green;color:#000;}</style>
<div class="weui_cells weui_cells_access">
    <?php
        foreach($addressList as $_address){
            $_addressDefault = $_address['address_default'] ? 'default_address' : '';
            echo <<<EOF
<a class="weui_cell {$_addressDefault} address" href="javascript:void(0);" address_id="{$_address['id']}" user_id="{$_address['user_id']}">
    <div class="weui_cell_hd" style="display:none;"><img src="" alt="" style="width:20px;margin-right:5px;display:block"></div>
    <div class="weui_cell_bd weui_cell_primary">
        <p style="margin:0;">{$_address['address_name']}</p>
    </div>
    <div class="weui_cell_ft">{$_address['address_detail']}</div>
</a>
EOF;
        }
    ?>
</div>
<a href="/shop/account/addressupdate" class="weui_btn weui_btn_primary" style="margin-top:5px;">新增收货地址</a>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>
<script>
    $(function(){
        $('.address').on('click', function(){
            var _this = this;
            var userId = $(this).attr('user_id');
            if(!userId){
                layer.error('用户id非法');
                return false;
            }
                
            var addressId = $(this).attr('address_id');
            if(!addressId){
                layer.error('收货地址id非法');
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

                    layer.toast('成功', function(){$('.address').removeClass('default_address');$(_this).addClass('default_address')});
                }
            });
            return false;
        });
    })
</script>