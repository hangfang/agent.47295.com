<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/shop/header.php';
?>
<div class="single_top">
	 <div class="container"> 
	     <div class="register">
		  	  <form id="register"> 
				 <div class="register-top-grid">
					<h3>注册信息</h3>
                    <a class="news-letter" href="javascript:void(0);">
                      <label class="checkbox" style="margin-top:0px;padding-left:0px;"><input type="checkbox" name="checkbox">已有账号?</label>
                    </a>
					 <div style="clear:left;">
						<span>真实姓名</span>
                        <input type="text" name="user_name" class="user_name" max-length="16" placeholder="请输入姓名"> 
					 </div>
					 <div>
						<span>手机号码</span>
						<input type="tel" name="user_mobile" class="user_mobile" placeholder="请输入手机号码">
					 </div>
					 <div>
						 <span>登录密码</span>
						 <input type="password" name="user_pwd" class="user_pwd" placeholder="请输入密码"> 
					 </div>
					 <div>
						 <span>确认密码</span>
						 <input type="password" name="user_pwd_repeat" class="user_pwd_repeat" placeholder="请再次输入密码"> 
					 </div>
                    </div>
                  
				<div class="clearfix"> </div>
				<div class="register-but">
					   <input type="submit" value="确 定" class="submit">
					   <div class="clearfix"> </div>
				</div>
				</form>
             <div class="clearfix"> </div>
                <form style="display:none;" id="bind"> 
				     <div class="register-bottom-grid">
						    <h3>绑定账号</h3>
                                <a class="news-letter" href="javascript:void(0);">
                                <label class="checkbox" style="margin-top:0px;padding-left:0px;"><input type="checkbox" name="checkbox">没有账号?</label>
                              </a>
							 <div style="clear:left;">
								<span>手机号码</span>
								<input type="tel" name="user_mobile" class="user_mobile" placeholder="请输入手机号码">
							 </div>
							 <div>
                                <span>登录密码</span>
                                <input type="password" name="user_pwd" class="user_pwd" placeholder="请输入密码"> 
                            </div>
							 <div class="clearfix"> </div>
                            <div class="register-but" style="clear:left;">
                                   <input type="submit" value="确 定" class="submit">
                                   <div class="clearfix"> </div>
                            </div>
					 </div>
				</form>
		   </div>
     </div>
</div>
<script>
    $(function(){
       $('#register .news-letter').click(function(){
            $('#register').hide();
            $('#bind').show();
       });
       
       
       $('#bind .news-letter').click(function(){
            $('#register').show();
            $('#bind').hide();
       });
       
       $('#register,#bind').submit(function(e){
           return false;
       });
       
       $('#register .submit').click(function(){
            var params = {};
           
            var tmp = $('#register .user_name').val().replace(/\s/ig, '');
            if(!tmp){
                alert('姓名不能留空!');
                return false;
            }
            params['user_name'] = tmp;
           
            var tmp = $('#register .user_mobile').val().replace(/\s/ig, '');
            if(!tmp.match(/^1[\d]{10}$/g)){
                alert('手机号码格式错误!');
                return false;
            }
            params['user_mobile'] = tmp;
           
            var tmp = $('#register .user_pwd').val();
            if(tmp.length<6){
                alert('登录密码不能少于6个字符!');
                return false;
            }
            params['user_pwd'] = tmp;
           
            if(tmp!=$('#register .user_pwd_repeat').val()){
                alert('密码输入不一致!');
                return false;
            }

            $.ajax({
               url:'/wechat/auth/doregister',
               data:params,
               type:'POST',
               dataType: 'json',
               success:function(data, xhr){
                   if(!data){
                       alert('服务器内部错误，请稍后再试...');
                       return false;
                   }
                   
                   if(data.rtn!=0){
                       alert(data.error_msg);
                       return false;
                   }
                   
                   location.href = '/shop/index/index';
               }
            });
       });
       
       $('#bind .submit').click(function(){
            var params = {};
           
            var tmp = $('#bind .user_mobile').val().replace(/\s/ig, '');
            if(!tmp.match(/^1[\d]{10}$/g)){
                alert('手机号码格式错误!');
                return false;
            }
            params['user_mobile'] = tmp;
           
            var tmp = $('#bind .user_pwd').val();
            if(tmp.length<6){
                alert('登录密码不能少于6个字符!');
                return false;
            }
            params['user_pwd'] = tmp;
            
            $.ajax({
               url:'/wechat/auth/bind',
               data:params,
               type:'POST',
               dataType: 'json',
               success:function(data, xhr){
                   if(!data){
                       alert('服务器内部错误，请稍后再试...');
                       return false;
                   }
                   
                   if(data.rtn!=0){
                       alert(data.error_msg);
                       return false;
                   }
                   
                   location.href = '/shop/index/index';
               }
            });
       });
    });
</script>
<?php include BASE_PATH.'/template/shop/footer.php';?>