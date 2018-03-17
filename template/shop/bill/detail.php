<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<div class="weui_panel weui_panel_access">
    <div class="weui_panel_hd" style='display:none;'><?php echo $title;?></div>
    <div class="weui_panel_bd" id="bill">
        <?php
        $STATIC_CDN_URL = STATIC_CDN_URL;
        $productCostMoney = $productSaleMoney = $productRealMoney = $productNum = 0;
        foreach($billProduct as $_product){
            $productNum += $_product['product_num'];
            
            $_tmp = bcmul($_product['product_cost_money'], $_product['product_num'], 2);
            $productCostMoney = bcadd($productCostMoney, $_tmp, 2);//成本价
            
            $_tmp = bcmul($_product['product_sale_money'], $_product['product_num'], 2);
            $productSaleMoney = bcadd($productSaleMoney, $_tmp, 2);//原价
            
            $_tmp = bcmul($_product['product_real_money'], $_product['product_num'], 2);
            $productRealMoney = bcadd($productRealMoney, $_tmp, 2);//售价
            $_imgSrc = empty($_product['product_image']) ? '' : str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_product['product_image']);
            
            $_extra = '';
            
            if(BaseModel::isAdmin()){
                $_extra .= '<div class="weui_cell" style="padding:15px 0;position:absolute;left:0px;top:0;display:none;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">成本</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_cost_money" type="number" placeholder="请输入成本" value="'. $_product['product_cost_money'] .'" style="width:5rem;vertical-align: top;position: absolute;top: 13px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'" readonly>
                    </div>
                </div>';
                $_extra .= '<div class="weui_cell" style="padding:15px 0;position:absolute;left:0px;top:0;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">市价</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_sale_money" type="number" placeholder="请输入市价" value="'. $_product['product_sale_money'] .'" style="width:5rem;vertical-align: top;position: absolute;top: 13px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'">
                    </div>
                </div>';
                $_extra .= '<div class="weui_cell" style="padding:15px 0;position:absolute;left:8.5rem;top:0;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">售价</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_real_money" type="number" placeholder="请输入售价" value="'. $_product['product_real_money'] .'" style="width:5rem;vertical-align: top;position: absolute;top: 13px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'">
                    </div>
                </div>';
                $_extra .= '<div class="weui_cell" style="padding:15px 0;position:absolute;left:17rem;top:0;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">数量</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_num" type="number" placeholder="请输入数量" value="'. $_product['product_num'] .'" style="width:5rem;vertical-align: top;position: absolute;top: 13px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'">
                    </div>
                </div>';
            }else if(in_array($bill['bill_status'], ['CHECKED', 'PAID', 'POST'])){
                $_extra .= '<div class="weui_cell" style="padding:15px 0;position:absolute;left:8.5rem;top:0;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">成本</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_real_money" type="number" placeholder="请输入成本" value="'. $_product['product_real_money'] .'" style="width:5rem;vertical-align: top;position: absolute;top: 13px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'" readonly>
                    </div>
                </div>';
                $_extra .= '<div class="weui_cell" style="padding:15px 0;position:absolute;left:17rem;top:0;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">数量</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_real_money" type="number" placeholder="请输入数量" value="'. $_product['product_num'] .'" style="width:5rem;vertical-align: top;position: absolute;top: 13px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'">
                    </div>
                </div>';
            }
            
            echo <<<EOF
<div class="weui_media_box weui_media_appmsg bill_product">
    <div class="weui_media_hd">
        <img class="lazy weui_media_appmsg_thumb" data-original="{$_imgSrc}" onerror="this.src='{$STATIC_CDN_URL}{$staticDir}images/qrcode_for_gh_a103c9f558fa_258.jpg'">
    </div>
    <div class="weui_media_bd" style="height:auto;line-height:0;">
        <h4 class="weui_media_title" style="margin: 0px;">{$_product['product_name']}</h4>
        <div class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;">
            {$_extra}
        </div>
    </div>
</div>
EOF;
        }
        ?>
        <div class="weui_media_box weui_media_appmsg">
            <div class="weui_media_hd" style="height:auto;line-height:0;display:none;">
                <span class="weui_desc_extra">总计</span>
            </div>
            <div class="weui_media_bd">
                <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;">
                    <?php
                    if(BaseModel::isAdmin()){
                        echo '<span class="weui_desc_extra bill_cost_money_total" style="line-height:3;font-size:13px;display:none;">成本'. $productCostMoney .'</span>';
                        echo '<span class="weui_desc_extra bill_sale_money_total" style="line-height:3;font-size:13px;position:absolute;">市价'. $productSaleMoney .'</span>';
                        echo '<span class="weui_desc_extra bill_real_money_total" style="line-height:3;position:absolute;left:8.5rem;font-size:13px;">售价'. $productRealMoney .'</span>';
                    }else if(in_array($bill['bill_status'], ['CHECKED', 'PAID', 'POST'])){
                        echo '<span class="weui_desc_extra bill_real_money_total" style="line-height:3;font-size:13px;left:8.5rem;">售价'. $productRealMoney .'</span>';
                    }
                    ?>
                    <span class="weui_desc_extra bill_number_total" style="position:absolute;line-height:3;left:17rem;font-size:13px;"><?php echo $productNum;?>件</span>
                </p>
                <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;">
                    <span class="weui_desc_extra bill_real_money_total" style="line-height:3;font-size:13px;padding:0;">成交(<?php echo $productRealMoney;?>)</span>
                    <span class="weui_desc_extra" style="line-height:3;font-size:13px;padding:0;">-</span>
                    <span class="weui_desc_extra" style="line-height:3;font-size:13px;width:6rem;padding:0;">优惠<input type="number" class="bill_discount_money" value="<?php echo $bill['bill_discount_money'] ? $bill['bill_discount_money'] : '0';?>"style="position: absolute;border: none;width: 4rem;color:#000;" bill_code="<?php echo $bill['bill_code'];?>"/></span>
                    <span class="weui_desc_extra" style="line-height:3;font-size:13px;padding:0;">=</span>
                    <span class="weui_desc_extra bill_pay_money_total" style="line-height:3;font-size:13px;color:red;padding:0;">应收(<?php echo bcsub($productRealMoney, $bill['bill_discount_money'], 2);?>)</span>
                </p>
            </div>
        </div>
        <div class="weui_media_box weui_media_appmsg">
            <div class="weui_media_bd">
                <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;display: block;">
                    <?php
                    if(BaseModel::isAdmin() || $bill['bill_status']==='INIT'){
                        echo '<span class="weui_btn weui_btn_mini weui_btn_warn cancel_bill" style="float: left;display: block;" bill_code="'. $bill['bill_code'] .'">取消订单</span>';
                    }
                    ?>
                    <span class="weui_btn weui_btn_mini weui_btn_primary update_bill" style="float: right;display: block;margin:0;">确定</span>
                </p>
            </div>
        </div>
    </div>
</div>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>
<script>
$(function(){
    function refreshBill(){
        var products = $('#bill .bill_product');
        var billTotalNumber = 0;
        var billCostMoneyTotal = 0;
        var billSaleMoneyTotal = 0;
        var billRealMoneyTotal = 0;
        for(var i=0,len=products.length;i<len;i++){
            var obj = products.eq(i);
            var productNum = obj.find('.product_num').val();
            billTotalNumber = productNum - 0 + billTotalNumber;

            var productCostMoney = obj.find('.weui_media_desc').find('.product_cost_money').val();
            billCostMoneyTotal = productCostMoney*productNum + billCostMoneyTotal;

            var productSaleMoney = obj.find('.weui_media_desc').find('.product_sale_money').val();
            billSaleMoneyTotal = productSaleMoney*productNum + billSaleMoneyTotal;

            var productRealMoney = obj.find('.weui_media_desc').find('.product_real_money').val();
            billRealMoneyTotal = productRealMoney*productNum + billRealMoneyTotal;
            
        }
        
        $('#bill .bill_number_total').html(billTotalNumber + '件');
        $('#bill .bill_cost_money_total').html('成本'+new Number(billCostMoneyTotal).toFixed(2));
        $('#bill .bill_sale_money_total').html('市价'+new Number(billSaleMoneyTotal).toFixed(2));
        $('#bill .bill_real_money_total').html('售价'+new Number(billRealMoneyTotal).toFixed(2));
        $('#bill .bill_pay_money_total').html('应收'+new Number(billRealMoneyTotal-$('#bill .bill_discount_money').val()).toFixed(2));
    }
    
    $('#bill').on('click', '.update_bill', function(){
       location.href = '/shop/bill/index'; 
    });

    $('#bill').on('keyup', '.product_num', function(){
        var tmp = $(this).val();
        if(tmp<0){
            layer.error('购买数量非法');
            return false;
        }
    });
    
    $('#bill').on('blur', '.product_num', function(){
        var _this = this;
        var param = {};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        var tmp = $(this).attr('product_id');
        if(!tmp){
            layer.error('商品id非法');
            return false;
        }
        param.product_id = tmp;
        
        var tmp = $(this).val();
        if(tmp<0){
            layer.error('商品数量非法');
            return false;
        }
        param.product_num = tmp;
        layer.loading(true);

        $.ajax({
            url:'/shop/bill/updateproductnum',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                refreshBill();
            }
        });
        return false;
    });

    $('#bill').on('keyup', '.product_sale_money', function(){
        var productSaleMoney = $(this).val();
        if(productSaleMoney<0){
            layer.error('市场价不能小于0');
            return false;
        }
        
        var productRealMoney = $(this).parent('.product_list').find('.product_real_money').val();
        if(productSaleMoney - productRealMoney < 0){
            layer.error('市场价不能小于成交价');
            return false;
        }
        
        var productCostMoney = $(this).parent('.product_list').find('.product_cost_money').val();
        if(productSaleMoney - productCostMoney < 0){
            layer.error('市场价不能小于成本价');
            return false;
        }
    });

    $('#bill').on('blur', '.product_sale_money', function(){
        var productSaleMoney = $(this).val();
        if(productSaleMoney<0){
            layer.error('市场价不能小于0');
            return false;
        }
        
        var productRealMoney = $(this).parent('.product_list').find('.product_real_money').val();
        if(productSaleMoney - productRealMoney < 0){
            layer.error('市场价不能小于成交价');
            return false;
        }
        
        var productCostMoney = $(this).parent('.product_list').find('.product_cost_money').val();
        if(productSaleMoney - productCostMoney < 0){
            layer.error('市场价不能小于成本价');
            return false;
        }
        var param = {"product_sale_money":productSaleMoney};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        var tmp = $(this).attr('product_id');
        if(!tmp){
            layer.error('商品id非法');
            return false;
        }
        param.product_id = tmp;
        
        layer.loading(true);

        $.ajax({
            url:'/shop/bill/updateproductprice',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                refreshBill();
            }
        });
        return false;
    });

    $('#bill').on('keyup', '.product_real_money', function(){
        var productRealMoney = $(this).val();
        if(productRealMoney<0){
            layer.error('成交价不能小于0');
            return false;
        }
        
        var productSaleMoney = $(this).parent('.product_list').find('.product_sale_money').val();
        if(productRealMoney -  productSaleMoney > 0){
            layer.error('成交价不能大于市场价');
            return false;
        }
        
        var productCostMoney = $(this).parent('.product_list').find('.product_cost_money').val();
        if(productRealMoney - productCostMoney < 0){
            layer.error('成交价不能小于于成本价');
            return false;
        }
    });

    $('#bill').on('blur', '.product_real_money', function(){
        var productRealMoney = $(this).val();
        if(productRealMoney<0){
            layer.error('成交价不能小于0');
            return false;
        }
        
        var productSaleMoney = $(this).parent('.product_list').find('.product_sale_money').val();
        if(productRealMoney -  productSaleMoney > 0){
            layer.error('成交价不能大于市场价');
            return false;
        }
        
        var productCostMoney = $(this).parent('.product_list').find('.product_cost_money').val();
        if(productRealMoney - productCostMoney < 0){
            layer.error('成交价不能小于于成本价');
            return false;
        }
        var param = {"product_real_money":productRealMoney};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        var tmp = $(this).attr('product_id');
        if(!tmp){
            layer.error('商品id非法');
            return false;
        }
        param.product_id = tmp;
        
        layer.loading(true);

        $.ajax({
            url:'/shop/bill/updateproductprice',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                refreshBill();
            }
        });
        return false;
    });
    
    $('#bill').on('blur', '.bill_discount_money', function(){
        var param = {};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        var billDiscountMoney = $(this).val();
        if(billDiscountMoney<0){
            layer.error('优惠金额不能小于0');
            return false;
        }
        
        var billRealMoney = $('#bill').find('.bill_real_money_total').eq(0).html().replace(/[^\d\.]/ig, '');
        var billPayMoney = billRealMoney - billDiscountMoney;
        if(billPayMoney < 0){
            layer.error('优惠金额不能大于成交价');
            return false;
        }
        param.bill_discount_money = new Number(billDiscountMoney).toFixed(2);
        
        layer.loading(true);
        $.ajax({
            url:'/shop/bill/updatediscountmoney',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                $('#bill').find('.bill_pay_money_total').html('应收(￥'+ new Number(billPayMoney).toFixed(2) +')');
                layer.toast('成功');
            }
        });
    });
    
    $('#bill').on('click', '.cancel_bill', function(){
        var _this = this;
        layer.confirm('确定要取消订单吗？', function(){
            var param = {};
            var tmp = $(_this).attr('bill_code');
            if(!tmp){
                layer.error('操作非法');
                return false;
            }
            param.bill_code = tmp;
            
            layer.loading(true);
            $.ajax({
                url:'/shop/bill/cancel',
                dataType:'json',
                data:param,
                type:'post',
                success:function(data, xhr){
                    layer.loading(false);
                    if(!data){
                        layer.error('请求失败,请稍后再试...');
                        return false;
                    }

                    if(data.rtn!=0){
                        layer.error(data.error_msg);
                        return false;
                    }
                    
                    location.href = '/shop/bill/index';
                }
            });
        });
        return false;
    });
});
</script>