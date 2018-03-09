<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include $viewPath.'header.php';
?>
<div id="banner" class="banner" style="display:none;">
	<div class="container">
		<div class="banner_desc">
			<h1>琳玲Dai购</h1>
			<h2>最贴心的生活助手</h2>
			<div class="button">
                <a href="javascritp:void(0)" class="hvr-shutter-out-horizontal">Shop Now</a>
            </div>
		</div>
	</div>
</div>
<div class="content_top">
	<h3 class="m_1" style="display:none;">新品到货</h3>
	<div class="container" id="container">
	   <div class="box_1">
	       <div class="col-md-7-bak">
               <?php 
                $STATIC_CDN_URL = STATIC_CDN_URL;
                for($i=0,$len=count($data['list']); $i<$len; $i++){
                    if(($i+1)%3==0){
                        echo '<div class="section group">';
                    }

                    $_order = $data['list'][$i];
                    
                    $_imgSrc = empty($_order['order_image']) ? '' : str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_order['order_image']);
                    
                    $_extra = '';
                    if((!empty($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']==='admin') || in_array($_order['order_status'], ['CHECKED', 'POST', 'PAID'])){
                        $_extra .=  '<h3><a href="/shop/order/detail?order_code='. $_order['order_code'] .'">原价:￥'.$_order['order_sale_money'].'</a></h3>';
                        $_extra .=  '<span><span class="amount">最终价:￥'.$_order['order_real_money'].'</span></span>';
                    }
                    
                    $_orderName = date('Y-m-d H:i:s');
                    echo <<<EOF
<div class="col_1_of_3 span_1_of_3">
    <div class="shop-holder">
         <div class="product-img">
            <a href="/shop/order/detail?order_code={$_order['order_code']}">
                <img width_bak="225" height_bak="265" data-original="{$_imgSrc}" src="{$STATIC_CDN_URL}{$staticDir}images/default215x215.png" class="lazy img-responsive"  alt="item4" style="height:17rem;">
            </a>
            <a href="javascript:void(0);" class="button "></a>
        </div>
    </div>
    <div class="shop-content" style="height: 50px;margin-top: .7rem;">
            <div><a href="/shop/order/detail?order_code={$_order['order_code']}" rel="tag" style="display: block;height: 20px;overflow: hidden;">{$_orderName}</a></div>
{$_extra}
    </div>
</div>
EOF;
                    if(($i+1)%3==0){
                        echo '<div class="clearfix"></div></div>';
                    }
                }
                ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
</div>

<script>
    (function(){
        var total = <?php echo $data['total'];?>;
        var offset = 0;
        $(function(){
            var xhrIng = false;
            var flag = true;
            $(document).scroll(function(){
                var tmp = $(document).height() / ($(document).scrollTop() + window.innerHeight);
                if(tmp < 1.05){
                    if(offset>=total-1){
                        flag && $('.col-md-7-bak').append('<div class="col_1_of_3 span_1_of_3" style="margin: 0 auto; text-align: center; width: 100%; color: gray; border-top: dashed #bbb 1px; padding-top: 10px;">已加载完毕</div>');
                        return flag = false;
                    }
                    
                    $.ajax({
                        url:'/shop/product/latest',
                        type:'get',
                        dataType:'json',
                        data:{'offset':offset+=12, 'length':12},
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
                                alert('请求失败,请稍后再试...');
                                return false;
                            }
                            
                            if(data.rtn!=0){
                                alert(data.error_msg);
                                return false;
                            }
                            
                            var html = '';
                            for(var i=0,len=data.data.list.length;i<len;i++){
                                
                                var order = data.data.list[i];
                                if((i+1)%3==0){
                                    html += '<div class="section group">';
                                }
                                
                                var extra = '';
                                <?php
                                    if((!empty($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']==='admin') || in_array($_order['order_status'], ['CHECKED', 'POST', 'PAID'])){
                                        $_extra .=  '<h3><a href="/shop/order/detail?order_code=\'+ order[\'order_code\'] +\'">原价:￥\'+ order[\'order_sale_money\']+\'</a></h3>';
                                        $_extra .=  '<span><span class="amount">最终价:￥\'+ order[\'order_real_money\'] +\'</span></span>';
                                        echo 'extra =\''.$_extra.'\';';
                                    }
                                ?>
                                
                                var d = new Date(order['create_time']*1000);
                                var orderName = d.getFullYear()+'-'+d.getMonth()+'-'+d.getDate()+' '+d.getHours()+':'+d.getSeconds()+':'+d.getMinutes();
                                var imgSrc = order['order_image'] ? order['order_image'].replace('<?php echo CDN_URL_PLACEHOLDER;?>', '<?php echo IMG_CDN_URL;?>') : '';
                                html += '<div class="col_1_of_3 span_1_of_3">\
    <div class="shop-holder">\
         <div class="product-img">\
            <a href="/shop/order/detail?order_code='+ order['order_code'] +'">\
                <img width_bak="225" height_bak="265" src="'+ imgSrc +'" onerror="this.src=\'<?php echo $STATIC_CDN_URL.$staticDir;?>images/default215x215.png\';" class="lazy img-responsive"  alt="item4" style="height:17rem;">\
            </a>\
            <a href="javascript:void(0);" class="button "></a>\
        </div>\
    </div>\
    <div class="shop-content" style="height: 50px;margin-top: .7rem;">\
            <div><a href="/shop/order/detail?order_code='+ order['order_code'] +'" rel="tag">'+ orderName +'</a></div>\
            '+ extra +'\
    </div>\
</div>';
                                if((i+1)%3==0){
                                    html += '<div class="clearfix"></div></div>';
                                }
                            }
                            
                            $('.col-md-7-bak').append(html);
                            xhrIng = false;
                        }
                    });
                }  
            });
        })
    })();
</script>
<?php include $viewPath.'footer.php';?>