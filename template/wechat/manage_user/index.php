<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<div class="weui_cells weui_cells_access" id="user_list">
    <?php
        foreach($userList as $_user){
            $_name = empty($_user['remark']) ? $_user['nickname'] : $_user['remark'];
            $_url = sprintf('/wechat/manage_user/detail?user_openid=%s', $_user['openid']);
            echo <<<EOF
<a class="weui_cell" href="{$_url}">
    <div class="weui_cell_hd"><img src="{$_user['headimgurl']}" alt="" style="width:20px;margin-right:5px;display:block"></div>
    <div class="weui_cell_bd weui_cell_primary">
        <p style="margin:0;">{$_name}</p>
    </div>
    <div class="weui_cell_ft">{$_user['province']}-{$_user['city']}</div>
</a>
EOF;
        }
    ?>
</div>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>

<script>
    var next_openid = '<?php echo $nextOpenId;?>';
    var count = <?php echo $openIdList['count'];?>;
    var total = <?php echo $openIdList['total'];?>;
    
    $(function(){
        $(document).scroll(function(){
            var tmp = $(document).height() / ($(document).scrollTop() + window.innerHeight);
            if(tmp < 1.05 && total>count){
                $.ajax({
                    url:'/wechat/manage_user/getusers',
                    type:'POST',
                    dataType:'json',
                    data:{'next_openid':next_openid},
                    beforeSend:function(){
                        $('#loadingToast').show();
                    },
                    success: function(data, xhr){
                        $('#loadingToast').hide();
                        if(!data){
                            $('#dialog2').find('.weui_dialog_title').html('错误').end().find('.weui_dialog_bd').html('请求数据失败').end().show();
                            return false;
                        }
                        
                        if(data.rtn!=0){
                            $('#dialog2').find('.weui_dialog_title').html('错误').end().find('.weui_dialog_bd').html(data.error_msg).end().show();
                            return false;
                        }
                        
                        count += data.length;
                        var html = '';
                        for(var i in data){
                            var name = data[i]['remark'] ? data[i]['remark'] : data[i]['nickname'];
                            var url = '/wechat/manage_user/detail?user_openid='+data[i]['openid'];
                            html += '\
<a class="weui_cell" href="'+ url +'">\
    <div class="weui_cell_hd"><img src="'+ data[i]['headimgurl'] +'" alt="" style="width:20px;margin-right:5px;display:block"></div>\
    <div class="weui_cell_bd weui_cell_primary">\
        <p style="margin:0;">'+ name +'</p>\
    </div>\
    <div class="weui_cell_ft">'+ data[i]['province'] +'-'+ data[i]['city'] +'</div>\
</a>';
                        }
                        
                        next_openid = data[i]['openid'];
                        $('#user_list').append(html);
                    }
                });
            }  
        });
    })
</script>