<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .weui_label {width:auto;}
</style>
<div class="input">
    <div class="hd">
        <h1 class="page_title">登录信息</h1>
    </div>
    <div class="bd">
        <div class="weui_cells_title" style="display:none;">账户信息</div>
        <form class="weui_cells weui_cells_form" style="display:none;" id="register">
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
            <a href="javascript:;" class="weui_btn weui_btn_primary register-btn">注  册</a><a href="javascript:;" class="weui_btn weui_btn_warn go-login">去登录</a>
        </form>
        <form class="weui_cells weui_cells_form" style="" id="bind">
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
            <a href="javascript:;" class="weui_btn weui_btn_primary login-btn">登  录</a><a href="javascript:;" class="weui_btn weui_btn_warn go-register">去注册</a>
        </form>
    </div>
</div>
<script>
    $(function(){
       $('#register .go-login').click(function(){
            $('#register').hide();
            $('#bind').show();
            $('.page_title').html('登录信息');
       });
       
       
       $('#bind .go-register').click(function(){
            $('#register').show();
            $('#bind').hide();
            $('.page_title').html('注册信息');
       });
       
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

            $.ajax({
               url:'/shop/login/register',
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
                   
                   location.href = '/shop/index/index';
               }
            });
       });
       
       $('#bind .login-btn').click(function(){
            var params = {};
           
            var tmp = $('#bind .user_mobile').val().replace(/\s/ig, '');
            if(!tmp.match(/^1[\d]{10}$/g)){
                layer.error('手机号码格式错误!');
                return false;
            }
            params['user_mobile'] = tmp;
           
            var tmp = $('#bind .user_pwd').val();
            if(tmp.length<6){
                layer.error('登录密码不能少于6个字符!');
                return false;
            }
            params['user_pwd'] = tmp;
            
            $.ajax({
               url:'/shop/login/dologin',
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
                   
                   location.href = '/shop/index/index';
               }
            });
       });
    });
</script>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>