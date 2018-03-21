<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<div class="weui_panel">
    <div class="weui_panel_hd">物流信息</div>
    <div class="weui_panel_bd">
        <?php
        if($expressDetail){
            foreach($expressDetail as $_detail){
                echo <<<EOF
<div class="weui_media_box weui_media_text">
    <p class="weui_media_desc">{$_detail['AcceptStation']}</p>
    <ul class="weui_media_info">
        <li class="weui_media_info_meta">{$_detail['AcceptTime']}</li>
    </ul>
</div>
EOF;
            }
        }else{
            $time = date('Y-m-d H:i:s');
            echo <<<EOF
<div class="weui_media_box weui_media_text">
    <p class="weui_media_desc">暂无物流信息</p>
    <ul class="weui_media_info">
        <li class="weui_media_info_meta">{$time}</li>
    </ul>
</div>
EOF;
        }
        ?>
    </div>
</div>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>