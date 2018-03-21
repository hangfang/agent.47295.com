<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<div class="weui_cells weui_cells_access" id="user_list">
    <?php
        foreach($data['list'] as $_user){
            $_name = empty($_user['user_name']) ? $_user['user_mobile'] : $_user['user_name'];
            $_url = sprintf('/shop/account/userdetail?id=%s', $_user['id']);
            echo <<<EOF
<a class="weui_cell" href="{$_url}">
    <div class="weui_cell_hd" style="display:none;"><img src="" alt="" style="width:20px;margin-right:5px;display:block"></div>
    <div class="weui_cell_bd weui_cell_primary">
        <p style="margin:0;">{$_name}</p>
    </div>
    <div class="weui_cell_ft">订单数:{$_user['bill_num']}</div>
</a>
EOF;
        }
    ?>
</div>
<?php if($data['total']>10){ ?>
<div class="weui_panel weui_panel_access">
    <div class="weui_panel_hd" style='display:none;'><?php echo $title;?></div>
    <div class="weui_panel_bd">
        <a class="weui_panel_ft" href="javascript:void(0);">查看更多</a>
    </div>
</div>
<?php } ?>
<a href="/shop/account/useradd" class="weui_btn weui_btn_primary" style="margin-top:5px;">新增用户</a>
<script>
    (function(){
        var total = <?php echo $data['total'];?>;
        var offset = 10;
        $(function(){
            var xhrIng = false;
            $('.weui_panel_ft').click(function(){
                var _this = this;
                if(offset>=total){
                    $(_this).remove();
                    return false;
                }

                $.ajax({
                    url:'/shop/account/user',
                    type:'get',
                    dataType:'json',
                    data:{'offset':offset, 'length':10},
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
                            layer.error('请求失败,请稍后再试...');
                            return false;
                        }

                        if(data.rtn!=0){
                            layer.error(data.error_msg);
                            return false;
                        }

                        var html = '';
                        for(var i=0,len=data.data.list.length;i<len;i++){

                            var user = data.data.list[i];
                            var name = user['user_name'] ? user['user_name'] : user['user_mobile'];
                            var url = '/shop/account/userdetail?id='+user['id'];
                            html += '\
                                <a class="weui_cell" href="'+ url +'">\
                                    <div class="weui_cell_hd" style="display:none;"><img src="" alt="" style="width:20px;margin-right:5px;display:block"></div>\
                                    <div class="weui_cell_bd weui_cell_primary">\
                                        <p style="margin:0;">'+ name +'</p>\
                                    </div>\
                                    <div class="weui_cell_ft">订单数:'+ user['bill_num'] +'</div>\
                                </a>';
                        }

                        $('#user_list').append(html);
                        offset += 10;
                        if(offset>=total){
                            $(_this).remove();
                            return false;
                        }
                        xhrIng = false;
                    }
                });
            });
        })
    })();
</script>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>
