<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<div class="weui_cells">
    <div class="weui_cell">
        <div class="weui_cell_bd weui_cell_primary">
            <p><?php echo $user['user_name'];?></p>
        </div>
        <div class="weui_cell_ft"><?php echo $user['user_mobile'];?></div>
    </div>
</div>
<div class="weui_cells weui_cells_access" id="bill_list">
    <?php
        foreach($billList as $_bill){
            $_status = empty($_tmp=BILL_STATUS_HINT[$_bill['bill_status']]) ? '未知' :$_tmp;
            $_url = sprintf('/shop/bill/detail?bill_code=%s', $_bill['bill_code']);
            $_name = date('Y-m-d H:i:s', $_bill['create_time']);
            echo <<<EOF
<a class="weui_cell" href="{$_url}">
    <div class="weui_cell_hd" style="display:none;"><img src="" alt="" style="width:20px;margin-right:5px;display:block"></div>
    <div class="weui_cell_bd weui_cell_primary">
        <p style="margin:0;">{$_name}</p>
    </div>
    <div class="weui_cell_ft">{$_status}</div>
</a>
EOF;
        }
    ?>
</div>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>
