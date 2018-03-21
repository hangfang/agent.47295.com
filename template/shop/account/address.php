<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .default_address {border:1px dashed green;color:#000;}
    .default_address:before {
        content:attr(name);
        display:block;
        width:100%;
        height:100%;
        text-align:center;
        padding-top:2.5rem;
    }
</style>
<div class="weui_cells weui_cells_access">
    <?php
        foreach($addressList as $_address){
            $_addressDefault = $_address['address_default'] ? 'default_address' : '';
            echo <<<EOF
<a class="weui_cell {$_addressDefault} address" href="/shop/account/addressupdate?address_id={$_address['id']}" name="默认">
    <div class="weui_cell_hd" style="display:none;"><img src="" alt="" style="width:20px;margin-right:5px;display:block"></div>
    <div class="weui_cell_bd weui_cell_primary">
        <p style="margin:0;">{$_address['address_name']}</p>
    </div>
    <div class="weui_cell_ft">{$_address['address_detail']}</div>
</a>
EOF;
        }
    ?>
</div>
<a href="/shop/account/addressupdate" class="weui_btn weui_btn_primary" style="margin-top:5px;">新增收货地址</a>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>