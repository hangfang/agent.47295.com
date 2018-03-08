<div class="footer" style="padding-top:0em;">
	<div class="container">
		<div class="footer_bottom">
			<div class="copy">
                <p>微信号: <span>琳玲港货</span></p>
                <p>手机号码: <a href="tel:+86 15914186940"><span>+86 159 1418 6940</span></a></p>
                <p>经营许可证：<a href="http://www.miibeian.gov.cn" target="_blank">鄂ICP备12003993号-1</a></p>
                <p>Copyright &copy; 2018.琳玲港货 All rights reserved.</p>
	        </div>
	    </div>
	</div>
</div>
<div style="width: 100%; height: 100%; z-index: 9998; position: absolute; top: 0px; left: 0px; display: none;" id="mask"></div>
<script>
    $("img.lazy").lazyload({effect: "fadeIn"});
    $(function(){
       $('#shopping-cart').click(function(){
            var tar = $(this).siblings('.header-bottom-right');
            if(tar.css('display')!=='none'){
                tar.fadeOut();
            }else{
                tar.fadeIn();
                $('#mask').show();
            }
        });
        
        $('#mask').on('click', function(){
            $('#shopping-cart').siblings('.header-bottom-right').fadeOut();
            $(this).hide();
        });
    });
</script>
</body>
</html>