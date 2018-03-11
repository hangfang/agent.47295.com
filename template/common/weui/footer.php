        <div style="height:47px;width:100%;visibility: hidden;" id="mask"></div>
    </div>
    <!-- start-已完成-start -->
    <div id="toast" style="display: none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <i class="weui_icon_toast"></i>
            <p class="weui_toast_content">已完成</p>
        </div>
    </div>
    <!-- end--已完成--end -->
    <!-- start-加载中-start -->
    <div id="loadingToast" class="weui_loading_toast" style="display: none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <div class="weui_loading">
                <div class="weui_loading_leaf weui_loading_leaf_0"></div>
                <div class="weui_loading_leaf weui_loading_leaf_1"></div>
                <div class="weui_loading_leaf weui_loading_leaf_2"></div>
                <div class="weui_loading_leaf weui_loading_leaf_3"></div>
                <div class="weui_loading_leaf weui_loading_leaf_4"></div>
                <div class="weui_loading_leaf weui_loading_leaf_5"></div>
                <div class="weui_loading_leaf weui_loading_leaf_6"></div>
                <div class="weui_loading_leaf weui_loading_leaf_7"></div>
                <div class="weui_loading_leaf weui_loading_leaf_8"></div>
                <div class="weui_loading_leaf weui_loading_leaf_9"></div>
                <div class="weui_loading_leaf weui_loading_leaf_10"></div>
                <div class="weui_loading_leaf weui_loading_leaf_11"></div>
            </div>
            <p class="weui_toast_content">数据加载中</p>
        </div>
    </div>
    <!-- end--加载中--end -->
    <!-- start-确认弹框-start -->
    <div class="weui_dialog_confirm" id="dialog1" style="display:none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">请确认</strong></div>
            <div class="weui_dialog_bd">自定义弹窗内容，居左对齐显示，告知需要确认的信息等</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog default">取消</a>
                <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <!-- end--确认弹框--end -->
    <!-- start-提示弹框-start -->
    <div class="weui_dialog_alert" id="dialog2" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">警告</strong></div>
            <div class="weui_dialog_bd">弹窗内容，告知当前页面信息等</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <!-- end--提示弹框--end -->
    <div id="tabbar" class="tabbar">
        <div class="weui_tab">
            <div class="weui_tab_bd">

            </div>
            <div class="weui_tabbar">
                    <a href="javascript:void(0);" class="weui_tabbar_item <?php if($controllerName==='product'||$controllerName==='index'||$controllerName==='category'||$controllerName==='activity'){echo 'weui_bar_item_on';}?>" id="shop_entry_btn">
                        <div class="weui_tabbar_icon">
                            <img src="<?php echo STATIC_CDN_URL;?>static/weui/images/icon_nav_actionSheet.png" alt="">
                        </div>
                        <p class="weui_tabbar_label">导航</p>
                    </a>
                <?php if(empty($_SESSION['user']) || $_SESSION['user']['user_type']!=='admin'){ ?>
                    <a href="/shop/order/cart" id="contact" class="weui_tabbar_item <?php if($controllerName==='order'){echo 'weui_bar_item_on';}?>">
                        <div class="weui_tabbar_icon">
                            <img src="<?php echo STATIC_CDN_URL;?>static/weui/images/icon_nav_article.png" alt="">
                        </div>
                        <p class="weui_tabbar_label">购物车</p>
                    </a>
                    <a href="/shop/activity/index" class="weui_tabbar_item <?php if($controllerName==='activity'){echo 'weui_bar_item_on';}?>">
                        <div class="weui_tabbar_icon">
                            <img src="<?php echo STATIC_CDN_URL;?>static/weui/images/icon_nav_msg.png" alt="">
                        </div>
                        <p class="weui_tabbar_label">订单</p>
                    </a>
                    <a href="/shop/account/index" class="weui_tabbar_item <?php if($controllerName==='manage_account'){echo 'weui_bar_item_on';}?>">
                        <div class="weui_tabbar_icon">
                            <img src="<?php echo STATIC_CDN_URL;?>static/weui/images/icon_nav_panel.png" alt="">
                        </div>
                        <p class="weui_tabbar_label">账户中心</p>
                    </a>
                <?php }else{?>
                    <a href="/wechat/manage_menu/index" id="contact" class="weui_tabbar_item <?php if($controllerName==='manage_menu'){echo 'weui_bar_item_on';}?>">
                        <div class="weui_tabbar_icon">
                            <img src="<?php echo STATIC_CDN_URL;?>static/weui/images/icon_nav_article.png" alt="">
                        </div>
                        <p class="weui_tabbar_label">菜单</p>
                    </a>
                    <a href="/wechat/manage_material/index" class="weui_tabbar_item <?php if($controllerName==='manage_material'){echo 'weui_bar_item_on';}?>">
                        <div class="weui_tabbar_icon">
                            <img src="<?php echo STATIC_CDN_URL;?>static/weui/images/icon_nav_msg.png" alt="">
                        </div>
                        <p class="weui_tabbar_label">素材</p>
                    </a>
                    <a href="/wechat/manage_account/index" class="weui_tabbar_item <?php if($moduleName==='wechat'){echo 'weui_bar_item_on';}?>">
                        <div class="weui_tabbar_icon">
                            <img src="<?php echo STATIC_CDN_URL;?>static/weui/images/icon_nav_panel.png" alt="">
                        </div>
                        <p class="weui_tabbar_label">管理中心</p>
                    </a>
                <?php }?>
            </div>
        </div>
    </div>
    <!-- start-ActionSheet-start -->
    <div id="actionSheet_wrap">
        <div class="weui_mask_transition" style="display: none;"></div>
        <div class="weui_actionsheet" id="weui_actionsheet">
            <div class="weui_actionsheet_menu">
            </div>
            <div class="weui_actionsheet_action">
                <div class="weui_actionsheet_cell" id="actionsheet_cancel">取消</div>
            </div>
        </div>
    </div>
    <!-- start-ActionSheet-start -->
</body>
</html>
<script>
    var layer = {
        toast:function(msg){
            $('#toast').find('p').html(msg).end().show();
            setTimeout(function(){
                $('#toast').find('p').html('已完成').end().hide();
            }, 2000);
        },
        error:function(msg){
            $('#dialog2').find('.weui_dialog_bd').html(msg).end().show();
            $('#dialog2').find('a').one('click', function(){
                $('#dialog2').find('.weui_dialog_bd').html('弹窗内容，告知当前页面信息等').end().hide();
            });
        },
        loading:function(show){
            if(typeof show!=='undefined' && show){
                $('#loadingToast').show();
            }else{
                $('#loadingToast').hide();
            }
        },
        confirm:function(msg, yes, no){
            $('#dialog1').find('.weui_dialog_bd').html(msg).end().show();
            $('#dialog1').find('a:eq(1)').one('click', function(){
                $('#dialog1').find('.weui_dialog_bd').html('自定义弹窗内容，居左对齐显示，告知需要确认的信息等').end().hide();
                if(typeof yes === 'function'){
                    yes.call();
                }
            });
            $('#dialog1').find('a:eq(0)').one('click', function(){
                $('#dialog1').find('.weui_dialog_bd').html('自定义弹窗内容，居左对齐显示，告知需要确认的信息等').end().hide();
                if(typeof no === 'function'){
                    no.call();
                }
            });
        },
        actionSheet:function(menu){
            var defaultHtml = '<div class="weui_actionsheet_cell"><a href="<?php echo STATIC_CDN_URL.$staticDir;?>images/qrcode_for_gh_a103c9f558fa_258.jpg">公众号：琳玲港货</a></div>\
                <div class="weui_actionsheet_cell"><a href="tel:<?php echo SERVICE_TEL;?>">联系电话：<?php echo SERVICE_TEL;?></a></div>';
            var mask = $('.weui_mask_transition');
            var weuiActionsheet = $('#weui_actionsheet');
            if(Object.prototype.toString.call(menu)!=='[object Array]'){
                menu = [
                    {
                        'url':'<?php echo STATIC_CDN_URL.$staticDir;?>images/qrcode_for_gh_a103c9f558fa_258.jpg',
                        'text':'公众号：琳玲港货'
                    },{
                        'url':'tel:<?php echo SERVICE_TEL;?>',
                        'text':'联系电话：<?php echo SERVICE_TEL;?>'
                    },
                ];
            }
            
            var html = '';
            for(var i in menu){
                html += '<div class="weui_actionsheet_cell">';
                if(typeof menu[i].url!=='undefined' && typeof menu[i].text!=='undefined'){
                    html += '<a href="'+ menu[i].url +'" id="weui_actionsheet_cell_'+ i +'">'+ menu[i].text +'</a>';
                }
                html += '</div>';
            }
            
            if(!html){
                html = defaultHtml;
            }
            weuiActionsheet.find('.weui_actionsheet_menu').html(html);
            for(var i in menu){
                if(typeof menu[i].click==='function'){
                    weuiActionsheet.find('#weui_actionsheet_cell_'+i).click(function(){
                        menu[i].click.call();
                    });
                }
            }
            
            weuiActionsheet.addClass('weui_actionsheet_toggle');
            mask.show().addClass('weui_fade_toggle').one('click', function () {
                hideActionSheet(weuiActionsheet, mask);
            });
            $('#actionsheet_cancel').one('click', function () {
                hideActionSheet(weuiActionsheet, mask);
            });
            weuiActionsheet.unbind('transitionend').unbind('webkitTransitionEnd');

            function hideActionSheet(weuiActionsheet, mask) {
                weuiActionsheet.removeClass('weui_actionsheet_toggle');
                mask.removeClass('weui_fade_toggle');
                weuiActionsheet.on('transitionend', function () {
                    mask.hide();
                }).on('webkitTransitionEnd', function () {
                    mask.hide();
                })
                
                weuiActionsheet.find('.weui_actionsheet_menu').html(defaultHtml);
            }
        }
    };
    $(function(){
        $("img.lazy").lazyload({effect: "fadeIn"});
        
        $('.add_to_cart').click(function(){
            var data = $(this).attr('data');

            if(data){
                try{
                    var json = JSON.parse(data);
                    if(typeof localStorage.cart==='undefined'){
                        localStorage.cart = JSON.stringify([json]);
                    }else{
                        var cart = JSON.parse(localStorage.cart);
                        if(Object.prototype.toString.call(cart)!=='[object Array]'){
                            cart = [json];
                        }else{
                            cart.unshift(json);
                        }
                        localStorage.cart = JSON.stringify(cart);
                        layer.toast('成功');
                    }
                }catch(e){
                    layer.error('加入购物车失败，请稍后再试');
                    return false;
                }
                
                return false;
            }
            
            return false;
        });
        
        $('#shop_entry_btn').click(function(){
            var menu = [
                {'url':'/shop/category/index','text':'分类'},
                {'url':'/shop/activity/index','text':'限时活动'},
                {'url':'/shop/index/index','text':'新品到货'},
            ];
            layer.actionSheet(menu);
        });
    });
</script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=55696994" charset="UTF-8"></script>