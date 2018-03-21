<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .weui_label {width:auto;}
</style>
<div class="input">
    <div class="hd" style="display: none;">
        <h1 class="page_title">新增收获地址</h1>
    </div>
    <div class="bd">
        <div class="weui_cells_title">地址信息</div>
        <form class="weui_cells weui_cells_form" id="address">
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">收货人</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input address_name" type="text" name="address_name" placeholder="请输入姓名" value="<?php echo empty($address['address_name']) ? '' : $address['address_name'];?>">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">手机号码</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input address_tel" type="tel" name="address_tel" placeholder="请输入手机号码" value="<?php echo empty($address['address_tel']) ? '' : $address['address_tel'];?>">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">省份</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input user_pwd address_province" type="text" name="address_province" placeholder="请输入省份" value="<?php echo empty($address['address_province']) ? '' : $address['address_province'];?>">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">城市</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input user_pwd_repeat address_city" type="text" name="address_city" placeholder="请输入城市" value="<?php echo empty($address['address_city']) ? '' : $address['address_city'];?>">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">地区</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input user_pwd_repeat address_district" type="text" name="address_district" placeholder="请输入地区" value="<?php echo empty($address['address_district']) ? '' : $address['address_district'];?>">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">详细地址</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input user_pwd_repeat address_detail" type="text" name="address_detail" placeholder="请输入详细地址" value="<?php echo empty($address['address_detail']) ? '' : $address['address_detail'];?>">
                </div>
            </div>
            <div class="weui_cell weui_cell_switch">
                <div class="weui_cell_hd weui_cell_primary">是否启用</div>
                <div class="weui_cell_ft">
                    <input class="weui_switch address_status" type="checkbox" name="address_status" <?php echo empty($address['address_status']) ? 'checked' : '';?>>
                </div>
            </div>
            <div class="weui_cells weui_cells_checkbox">
                <label class="weui_cell weui_check_label" for="address_default">
                    <div class="weui_cell_hd">
                        <input type="checkbox" class="weui_check address_default" name="address_default" id="address_default" <?php echo empty($address['address_default']) ? '' : 'checked';?>>
                        <i class="weui_icon_checked"></i>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p style="margin-bottom: .1rem;">设置为默认地址</p>
                    </div>
                </label>
            </div>
            <?php
                if($userId){
                    echo '<input type="hidden" name="user_id" class="user_id" value="'. $userId .'">';
                }
                
                if($address){
                    echo '<input type="hidden" name="id" class="id" value="'. (empty($address['id']) ? '' : $address['id']) .'">';
                }
            ?>
            <a href="javascript:;" class="weui_btn weui_btn_primary add-btn">确定</a>
        </form>
    </div>
</div>
<script>
    $(function(){
       $('#address .add-btn').click(function(){
            var params = {};
           
            var tmp = $('#address .id').length ? $('#address .id').val() : '';
            if(!tmp){
                params['id'] = tmp;
            }
           
            var tmp = $('#address .user_id').length ? $('#address .user_id').val() : '';
            if(tmp){
                params['user_id'] = tmp;
            }
           
            var tmp = $('#address .address_name').val();
            if(!tmp){
                layer.error('姓名不能留空!');
                return false;
            }
            params['address_name'] = tmp;
           
            var tmp = $('#address .address_tel').val().replace(/\s/ig, '');
            if(!tmp.match(/^1[\d]{10}$/g)){
                layer.error('手机号码格式错误!');
                return false;
            }
            params['address_tel'] = tmp;
           
            var tmp = $('#address .address_province').val();
            if(tmp.length<1){
                layer.error('省份不能为空!');
                return false;
            }
            params['address_province'] = tmp;
           
            var tmp = $('#address .address_province').val();
            if(tmp.length<1){
                layer.error('省份不能为空!');
                return false;
            }
            params['address_province'] = tmp;
           
            var tmp = $('#address .address_province').val();
            if(tmp.length<1){
                layer.error('省份不能为空!');
                return false;
            }
            params['address_province'] = tmp;
           
            var tmp = $('#address .address_city').val();
            if(tmp.length<1){
                layer.error('城市不能为空!');
                return false;
            }
            params['address_city'] = tmp;
           
            var tmp = $('#address .address_district').val();
            if(tmp.length<1){
                layer.error('地区不能为空!');
                return false;
            }
            params['address_district'] = tmp;
           
            var tmp = $('#address .address_detail').val();
            if(tmp.length<5){
                layer.error('详细地址不能少于5个字符!');
                return false;
            }
            params['address_detail'] = tmp;
           
            var tmp = $('#address .address_status').prop('checked');
            params['address_status'] = tmp ? 0 : -1;
           
            var tmp = $('#address .address_default').prop('checked');
            params['address_default'] = tmp ? 1 : 0;

            $.ajax({
               url:'/shop/account/updateaddress',
               data:params,
               type:'POST',
               dataType: 'json',
               success:function(data, xhr){
                   if(!data){
                       layer.error('服务器内部错误，请稍后再试...');
                       return false;
                   }
                   
                   if(data.rtn!=0){
                       layer.error(data.error_msg);
                       return false;
                   }
                   
                   layer.toast('操作成功', function(){location.href='/shop/account/address';});
               }
            });
       });
    });
</script>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>