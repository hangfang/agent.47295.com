<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<div class="weui_msg">
    <div class="weui_icon_area"><i class="weui_icon_success weui_icon_msg"></i></div>
    <div class="weui_text_area">
        <h2 class="weui_msg_title"><?php echo $title;?></h2>
        <p class="weui_msg_desc"><?php echo $msg;?></p>
    </div>
    <div class="weui_opr_area">
        <p class="weui_btn_area">
            <a href="<?php echo $detail;?>" class="weui_btn weui_btn_primary"><?php echo empty($btn) ? '确定' : $btn;?></a>
            <a href="javascript:;" class="weui_btn weui_btn_default" style="display:none;">取消</a>
        </p>
    </div>
    <div class="weui_extra_area">
        <a href="<?php echo $detail;?>">查看详情</a>
    </div>
</div>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>