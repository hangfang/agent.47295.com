<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .weui_media_desc .add_to_cart {float:right;}
</style>
<div class="weui_cells_title">搜索</div>
<div class="weui_cells">
    <div class="weui_cell weui_cell_select weui_select_before">
        <div class="weui_cell_hd">
            <select class="weui_select" name="type" id="type">
                <option value="Name">姓名</option>
                <option value="Mobile">手机号码</option>
                <option value="CtfId">身份证</option>
            </select>
        </div>
        <div class="weui_cell_bd weui_cell_primary">
            <input class="weui_input" type="tel" name="search" id="search" placeholder="请输入搜索关键字" style="float:left;width:60%;">
            <a href="javascript:;" class="weui_btn weui_btn_mini weui_btn_primary" style="float:right;" id="do_search">按钮</a>
        </div>
    </div>
</div>
<div class="weui_panel weui_panel_access">
    <div class="weui_panel_hd" style='display:none;'><?php echo $title;?></div>
    <div class="weui_panel_bd">
    </div>
    <a class="weui_panel_ft" href="javascript:void(0);">查看更多</a>
</div>
<script>
    (function(){
        $(function(){
            var xhrIng = false;
            $('#do_search').click(function(){
                var _this = this;
                
                var search = $('#search').val();
                if(!search || !search.replace(/\s+/ig, '')){
                    layer.error('参数错误');
                    return false;
                }
                
                var type = $('#type').val();
                if(!type){
                    layer.error('参数错误');
                    return false;
                }
                
                $.ajax({
                    url:'/wechat/manage_kaifang/getList',
                    type:'get',
                    dataType:'json',
                    data:{'search':search, 'type':type},
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
                        for(var i=0,len=data.data.length;i<len;i++){

                            var log = data.data[i];
                            
                            if(log['Version'] && log['Version']!='-'){
                                log['Name'] += '('+log['Version']+')';
                            }else if(log['Address'] && log['Address']!='-'){
                                log['Name'] += '('+log['Address']+')';
                            }else if(log['Gender'] && log['Gender']!='-'){
                                log['Name'] += '('+(log['Gender']==='M'?'男':'女')+')';
                            }
                            
                            var extra = '';
                            if(log['CtfId']){
                                extra += '<span class="weui_desc_extra">'+log['CtfTp']+':'+log['CtfId']+'</span>';
                            }
                            html += '<a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">\
    <div class="weui_media_hd">\
        <img class="weui_media_appmsg_thumb" src="<?php echo STATIC_CDN_URL;?>static/shop/images/qrcode_for_gh_a103c9f558fa_258.jpg" >\
    </div>\
    <div class="weui_media_bd">\
        <h4 class="weui_media_title">'+ log['Name'] +'</h4>\
        <p class="weui_media_desc">'+extra+'</p>\
    </div>\
</a>';
                        }

                        $('.weui_panel_bd').html(html);
                        xhrIng = false;
                    }
                });
            });
        })
    })();
</script>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>