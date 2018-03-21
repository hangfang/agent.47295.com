<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .weui_label {width:auto;}
</style>
<div class="input">
    <div class="hd" style="display: none;">
        <h1 class="page_title">新增账户</h1>
    </div>
    <div class="bd">
        <div class="weui_cells_title">账户信息</div>
        <form class="weui_cells weui_cells_form" id="register">
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">真实姓名</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input user_name" type="text" name="user_name" placeholder="请输入姓名">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">手机号码</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input user_mobile" type="tel" name="user_mobile" placeholder="请输入手机号码">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">登录密码</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input user_pwd" type="password" name="user_pwd" placeholder="请输入密码">
                </div>
            </div>
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">确认密码</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input user_pwd_repeat" type="password" name="user_pwd_repeat" placeholder="请再次输入密码">
                </div>
            </div>
            <a href="javascript:;" class="weui_btn weui_btn_primary register-btn">确定</a>
        </form>
    </div>
</div>
<script>
    $(function(){
       $('#register .register-btn').click(function(){
            var params = {};
           
            var tmp = $('#register .user_name').val().replace(/\s/ig, '');
            if(!tmp){
                layer.error('姓名不能留空!');
                return false;
            }
            params['user_name'] = tmp;
           
            var tmp = $('#register .user_mobile').val().replace(/\s/ig, '');
            if(!tmp.match(/^1[\d]{10}$/g)){
                layer.error('手机号码格式错误!');
                return false;
            }
            params['user_mobile'] = tmp;
           
            var tmp = $('#register .user_pwd').val();
            if(tmp.length<6){
                layer.error('登录密码不能少于6个字符!');
                return false;
            }
            params['user_pwd'] = tmp;
           
            if(tmp!=$('#register .user_pwd_repeat').val()){
                layer.error('密码输入不一致!');
                return false;
            }
            layer.loading(true);

            $.ajax({
               url:'/shop/account/adduser',
               data:params,
               type:'POST',
               dataType: 'json',
               success:function(data, xhr){
                    layer.loading(false);
                    if(!data){
                        layer.error('服务器内部错误，请稍后再试...');
                        return false;
                    }

                    if(data.rtn!=0){
                        layer.error(data.error_msg);
                        return false;
                    }
                   
                   layer.toast('操作成功', function(){location.href = '/shop/account/userdetail?id='+data.data.id;});
               }
            });
       });
    });
</script>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>