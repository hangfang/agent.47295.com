<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    /*轮播图*/
	.focus{ width:100%;  margin:0 auto; position:relative; overflow:hidden;   }
	.focus .hd{ width:100%; height:5px;  position:absolute; z-index:1; bottom:0; text-align:center;  padding:0px !important;}
	.focus .hd ul{ overflow:hidden; display:-moz-box; display:-webkit-box; display:box; height:5px; background-color:rgba(51,51,51,0.5);   }
	.focus .hd ul li{ -moz-box-flex:1; -webkit-box-flex:1; box-flex:1; }
	.focus .hd ul .on{ background:#FF4000;  }
	.focus .bd{ position:relative; z-index:0; }
	.focus .bd li img{ width:100%;  height:215pxpx; }
	.focus .bd li a{ -webkit-tap-highlight-color:rgba(0, 0, 0, 0); /* 取消链接高亮 */ }
    .article img {width:100%;height:100%;}
    
    /*商品描述*/
    .weui_panel .weui_media_info {margin-top:0px !important;}
    .weui_panel .weui_media_info_meta {color:#888;clear:both;font-size:1.4rem;line-height:2.2rem;}
    .weui_panel .weui_media_info_meta span{color:red;}
</style>
<script src="<?php echo STATIC_CDN_URL;?>static/public/js/TouchSlide.1.1.js?v=<?php echo STATIC_VERSION;?>"></script>
     <?php
        $_index = 0;
        $product['product_image'] = empty($product['product_image']) ? [] : explode(',', $product['product_image']);
        $STATIC_CDN_URL = STATIC_CDN_URL;
        $_hd = '';
        $_bd = '';
        foreach($product['product_image'] as $_image){
            $_hd .= '<li></li>';
            $_image = empty($_image) ? '{$STATIC_CDN_URL}{$staticDir}images/default215x215.png' : str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_image);
            $_bd .= <<<EOF
<li>
    <a href="javascript:void(0)">
        <img _src="{$_image}" class="carousel-inner img-responsive" onerror="this.src='{$STATIC_CDN_URL}{$staticDir}images/default215x215.png'"/>
    </a>
</li>
EOF;
        }
    ?>
<div id="focus" class="focus">
    <div class="hd">
        <ul><?php echo $_hd;?></ul>
    </div>
    <div class="bd">
        <ul><?php echo $_bd;?></ul>
    </div>
</div>
<script type="text/javascript">
    TouchSlide({ 
        slideCell:"#focus",
        titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
        mainCell:".bd ul", 
        effect:"left", 
        autoPlay:true,//自动播放
        autoPage:true, //自动分页
        switchLoad:"_src" //切换加载，真实图片路径为"_src" 
    });	
</script>
<div class="weui_panel">
    <div class="weui_panel_bd">
        <div class="weui_media_box weui_media_text">
            <ul class="weui_media_info">
                <li class="weui_media_info_meta">商品名称：<span><?php echo $product['product_name'];?></span></li>
                <li class="weui_media_info_meta">分类：<a href="/shop/category/product?category_id=<?php echo $category['category_id'];?>"><span><?php echo $category['category_name'];?></span></a></li>
                <li class="weui_media_info_meta">规格：<span><?php echo $product['product_model'];?></span></li>
                <li class="weui_media_info_meta">销量：<span><?php echo $product['product_purchased'];?>件</span></li>
                <li class="weui_media_info_meta" style="display:none;">浏览量：<span><?php echo $product['product_views'];?>次</span></li>
                <?php echo !empty($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']==='admin' ? '<li class="weui_media_info_meta">Kissbaby原价：<span>￥'.$product['product_sale_price'].'</span></li>' : '';?>
                <?php echo !empty($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']==='admin' ? '<li class="weui_media_info_meta">Kissbaby会员价：<span>￥'.$product['product_vip_price'].'</span></li>' : '';?>
                <li class="weui_media_info_meta" style='position: relative;height: 2rem;width: 100%;height: 2.23rem;padding:0px;'><a href="javascript:void(0);" data='<?php echo str_replace('\'', '###', json_encode($product));?>' class="weui_btn weui_btn_mini weui_btn_primary add_to_cart" style='width: 8rem;height: 2.23rem;display:block;margin:0 auto;'>+购物车</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="article">
    <div class="bd">
        <article class="weui_article" style="padding:0;">
            <section>
                <section>
                    <?php 
                        $product['product_description'] = str_replace('width', 'width_bak', $product['product_description']);
                        $product['product_description'] = str_replace('height', 'height_bak', $product['product_description']);
                        echo str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $product['product_description']);
                    ?>
                </section>
            </section>
        </article>
    </div>
</div>
<?php if($related){?>
<div class="weui_panel">
    <div class="weui_panel_hd">猜你喜欢</div>
    <div class="weui_panel_bd">
        <div class="weui_media_box weui_media_small_appmsg">
            <div class="weui_cells weui_cells_access">
                <?php
                foreach($related as $_related){
                    $_related['product_image'] = empty($_related['product_image']) ? [] : explode(',', $_related['product_image']);
                    $_imgSrc = empty($_related['product_image'][0]) ? '{$STATIC_CDN_URL}{$staticDir}images/default215x215.png' : str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_related['product_image'][0]);
                    
                    echo <<<EOF
<a class="weui_cell" href="/shop/product/detail?product_id={$_related['product_id']}">
    <div class="weui_cell_hd"><img src="{$_imgSrc}" onerror="this.src='{$STATIC_CDN_URL}{$staticDir}images/default215x215.png';" alt="" style="width:20px;margin-right:5px;display:block"></div>
    <div class="weui_cell_bd weui_cell_primary">
        <p style="margin:0px;line-height: 1.5rem;height: 1.5rem;overflow:hidden;">{$_related['product_name']}</p>
    </div>
    <span class="weui_cell_ft"></span>
</a>
EOF;
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php }?>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>
<script>
    var openInWechat = navigator.userAgent.toLowerCase().match(/MicroMessenger/i)=="micromessenger" ? true : false;
    if(openInWechat){
        wx.ready(function(){
            wx.onMenuShareTimeline({
                title: '<?php echo '【琳玲港货】我推荐港货['.$product['product_name'].']，省心省事！';?>', // 分享标题
                link: '<?php echo BASE_URL.'/shop/product/detail?product_id='.$product['product_id'];?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?php echo $_image;?>', // 分享图标
                success: function () {
                // 用户确认分享后执行的回调函数
                    layer.toast('分享成功');
                },
                cancel: function () {
                // 用户取消分享后执行的回调函数
                    layer.toast('取消分享');
                }
            });

            wx.onMenuShareAppMessage({
                title: '<?php echo '【琳玲港货】'.KISSBABY_DESCRIPTION;?>', // 分享标题
                desc: '<?php echo '我推荐港货['.$product['product_name'].']，【琳玲港货】省心省事！';?>', // 分享描述
                link: '<?php echo BASE_URL.'/shop/product/detail?product_id='.$product['product_id'];?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?php echo $_image;?>', // 分享图标
                type: 'link', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                // 用户确认分享后执行的回调函数
                    layer.toast('分享成功');
                },
                cancel: function () {
                // 用户取消分享后执行的回调函数
                    layer.toast('取消分享');
                }
            });
            
            wx.onMenuShareQQ({
                title: '<?php echo '【琳玲港货】'.KISSBABY_DESCRIPTION;?>', // 分享标题
                desc: '<?php echo '我推荐港货['.$product['product_name'].']，【琳玲港货】省心省事！';?>', // 分享描述
                link: '<?php echo BASE_URL.'/shop/product/detail?product_id='.$product['product_id'];?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?php echo $_image;?>', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                    layer.toast('分享成功');
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    layer.toast('取消分享');
                }
            });
            
            wx.onMenuShareWeibo({
                title: '<?php echo '【琳玲港货】'.KISSBABY_DESCRIPTION;?>', // 分享标题
                desc: '<?php echo '我推荐港货['.$product['product_name'].']，【琳玲港货】省心省事！';?>', // 分享描述
                link: '<?php echo BASE_URL.'/shop/product/detail?product_id='.$product['product_id'];?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?php echo $_image;?>', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                    layer.toast('分享成功');
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    layer.toast('取消分享');
                }
            });
            
            wx.onMenuShareQZone({
                title: '<?php echo '【琳玲港货】'.KISSBABY_DESCRIPTION;?>', // 分享描述
                desc: '<?php echo '我推荐港货['.$product['product_name'].']，【琳玲港货】省心省事！';?>', // 分享描述
                link: '<?php echo BASE_URL.'/shop/product/detail?product_id='.$product['product_id'];?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?php echo $_image;?>', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                    layer.toast('分享成功');
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    layer.toast('取消分享');
                }
            });
        });
    }
</script>