<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript">
        var _speedMark = new Date();
    </script>
    <meta charset="utf-8">
    <title><?php echo $data['title'];?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<!--    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">-->
<!--    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap-theme.min.css">-->
    <script src="/static/public/js/jquery.min.js"></script>
<!--    <script src="/static/bootstrap/js/bootstrap.min.js"></script>-->
    <link rel="stylesheet" href="/static/weui/css/weui.css"/>
    <link rel="stylesheet" href="/static/weui/css/common.css"/>
    <style type="text/css">
        body,button, input, select, textarea,h1 ,h2, h3, h4, h5, h6 { font-family: Microsoft YaHei,'宋体' , Tahoma, Helvetica, Arial, "\5b8b\4f53", sans-serif;}
        p.weui_tabbar_label {padding: 0; margin:0;}
        .weui_tabbar_icon + .weui_tabbar_label {margin: 0;}
    </style>
    <?php if(ini_get('environ') === 'production'){ ?>
    <script>
        window.onerror = function(){return true;};
    </script>
    <?php }?>
</head>
<body>
    <div id="container" class=''>
    </div>
    <script type="text/html" id="tpl_home">
        <div class="hd">
            <h1 class="page_title">WeUI</h1>
            <p class="page_desc">为微信Web服务量身设计</p>
        </div>
        <div class="bd">
            <div class="weui_grids">
                <a href="#/button" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_button"></i>
                    </div>
                    <p class="weui_grid_label">
                        Button
                    </p>
                </a>
                <a href="#/cell" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_cell"></i>
                    </div>
                    <p class="weui_grid_label">
                        Cell
                    </p>
                </a>
                <a href="#/toast" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_toast"></i>
                    </div>
                    <p class="weui_grid_label">
                        Toast
                    </p>
                </a>
                <a href="#/dialog" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_dialog"></i>
                    </div>
                    <p class="weui_grid_label">
                        Dialog
                    </p>
                </a>
                <a href="#/progress" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_progress"></i>
                    </div>
                    <p class="weui_grid_label">
                        Progress
                    </p>
                </a>
                <a href="#/msg" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_msg"></i>
                    </div>
                    <p class="weui_grid_label">
                        Msg
                    </p>
                </a>
                <a href="#/article" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_article"></i>
                    </div>
                    <p class="weui_grid_label">
                        Article
                    </p>
                </a>
                <a href="#/actionsheet" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_actionSheet"></i>
                    </div>
                    <p class="weui_grid_label">
                        ActionSheet
                    </p>
                </a>
                <a href="#/icons" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_icons"></i>
                    </div>
                    <p class="weui_grid_label">
                        Icons
                    </p>
                </a>
                <a href="#/panel" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_panel"></i>
                    </div>
                    <p class="weui_grid_label">
                        Panel
                    </p>
                </a>
                <a href="#/tab" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_tab"></i>
                    </div>
                    <p class="weui_grid_label">
                        Tab
                    </p>
                </a>
                <a href="#/searchbar" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_search_bar"></i>
                    </div>
                    <p class="weui_grid_label">
                        SearchBar
                    </p>
                </a>

                <a href="#/input" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_input"></i>
                    </div>
                    <p class="weui_grid_label">
                        Input
                    </p>
                </a>

                <a href="#/textarea" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_textarea"></i>
                    </div>
                    <p class="weui_grid_label">
                        TextArea
                    </p>
                </a>

                <a href="#/radio" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_radio"></i>
                    </div>
                    <p class="weui_grid_label">
                        Radio
                    </p>
                </a>

                <a href="#/checkbox" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_checkbox"></i>
                    </div>
                    <p class="weui_grid_label">
                        Checkbox
                    </p>
                </a>

                <a href="#/select" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_select"></i>
                    </div>
                    <p class="weui_grid_label">
                        Select
                    </p>
                </a>

                <a href="#/switch" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_switch"></i>
                    </div>
                    <p class="weui_grid_label">
                        Switch
                    </p>
                </a>

                <a href="#/uploader" class="weui_grid">
                    <div class="weui_grid_icon">
                        <i class="icon icon_uploader"></i>
                    </div>
                    <p class="weui_grid_label">
                        Uploader
                    </p>
                </a>
            </div>
        </div>
    </script>
    <script type="text/html" id="tpl_button">
        <div class="hd">
            <h1 class="page_title">Button</h1>
        </div>
        <div class="bd spacing">
            <a href="javascript:;" class="weui_btn weui_btn_primary">按钮</a>
            <a href="javascript:;" class="weui_btn weui_btn_disabled weui_btn_primary">按钮</a>
            <a href="javascript:;" class="weui_btn weui_btn_warn">确认</a>
            <a href="javascript:;" class="weui_btn weui_btn_disabled weui_btn_warn">确认</a>
            <a href="javascript:;" class="weui_btn weui_btn_default">按钮</a>
            <a href="javascript:;" class="weui_btn weui_btn_disabled weui_btn_default">按钮</a>
            <div class="button_sp_area">
                <a href="javascript:;" class="weui_btn weui_btn_plain_default">按钮</a>
                <a href="javascript:;" class="weui_btn weui_btn_plain_primary">按钮</a>

                <a href="javascript:;" class="weui_btn weui_btn_mini weui_btn_primary">按钮</a>
                <a href="javascript:;" class="weui_btn weui_btn_mini weui_btn_default">按钮</a>
            </div>
        </div>
    </script>
    <script type="text/html" id="tpl_cell">
        <div class="hd">
            <h1 class="page_title">Cell</h1>
        </div>
        <div class="bd">
            <div class="weui_cells_title">带说明的列表项</div>
            <div class="weui_cells">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>标题文字</p>
                    </div>
                    <div class="weui_cell_ft">说明文字</div>
                </div>
            </div>
            <div class="weui_cells_title">带图标、说明的列表项</div>
            <div class="weui_cells">
                <div class="weui_cell">
                    <div class="weui_cell_hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>标题文字</p>
                    </div>
                    <div class="weui_cell_ft">说明文字</div>
                </div>
                <div class="weui_cell">
                    <div class="weui_cell_hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>标题文字</p>
                    </div>
                    <div class="weui_cell_ft">说明文字</div>
                </div>
            </div>

            <div class="weui_cells_title">带跳转的列表项</div>
            <div class="weui_cells weui_cells_access">
                <a class="weui_cell" href="javascript:;">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>cell standard</p>
                    </div>
                    <div class="weui_cell_ft">
                    </div>
                </a>
                <a class="weui_cell" href="javascript:;">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>cell standard</p>
                    </div>
                    <div class="weui_cell_ft">
                    </div>
                </a>
            </div>

            <div class="weui_cells_title">带说明、跳转的列表项</div>
            <div class="weui_cells weui_cells_access">
                <a class="weui_cell" href="javascript:;">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>cell standard</p>
                    </div>
                    <div class="weui_cell_ft">说明文字</div>
                </a>
                <a class="weui_cell" href="javascript:;">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>cell standard</p>
                    </div>
                    <div class="weui_cell_ft">说明文字</div>
                </a>

            </div>

            <div class="weui_cells_title">带图标、说明、跳转的列表项</div>
            <div class="weui_cells weui_cells_access">

                <a class="weui_cell" href="javascript:;">
                    <div class="weui_cell_hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>cell standard</p>
                    </div>
                    <div class="weui_cell_ft">说明文字</div>
                </a>
                <a class="weui_cell" href="javascript:;">
                    <div class="weui_cell_hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>cell standard</p>
                    </div>
                    <div class="weui_cell_ft">说明文字</div>
                </a>
            </div>

            <div class="weui_cells_title">单选列表项</div>
            <div class="weui_cells weui_cells_radio">
                <label class="weui_cell weui_check_label" for="x11">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>cell standard</p>
                    </div>
                    <div class="weui_cell_ft">
                        <input type="radio" class="weui_check" name="radio1" id="x11">
                        <span class="weui_icon_checked"></span>
                    </div>
                </label>
                <label class="weui_cell weui_check_label" for="x12">

                    <div class="weui_cell_bd weui_cell_primary">
                        <p>cell standard</p>
                    </div>
                    <div class="weui_cell_ft">
                        <input type="radio" name="radio1" class="weui_check" id="x12" checked="checked">
                        <span class="weui_icon_checked"></span>
                    </div>
                </label>
            </div>
            <div class="weui_cells_title">复选列表项</div>
            <div class="weui_cells weui_cells_checkbox">
                <label class="weui_cell weui_check_label" for="s11">
                    <div class="weui_cell_hd">
                        <input type="checkbox" class="weui_check" name="checkbox1" id="s11" checked="checked">
                        <i class="weui_icon_checked"></i>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>standard is dealt for u.</p>
                    </div>
                </label>
                <label class="weui_cell weui_check_label" for="s12">
                    <div class="weui_cell_hd">
                        <input type="checkbox" name="checkbox1" class="weui_check" id="s12">
                        <i class="weui_icon_checked"></i>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>standard is dealicient for u.</p>
                    </div>
                </label>
            </div>

            <div class="weui_cells_title">开关</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell weui_cell_switch">
                    <div class="weui_cell_hd weui_cell_primary">标题文字</div>
                    <div class="weui_cell_ft">
                        <input class="weui_switch" type="checkbox"/>
                    </div>
                </div>
            </div>

            <div class="weui_cells_title">表单</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_hd"><label class="weui_label">qq</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="number" pattern="[0-9]*" placeholder="请输入qq号"/>
                    </div>
                </div>
                <div class="weui_cell weui_vcode">
                    <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="number" placeholder="请输入验证码"/>
                    </div>
                    <div class="weui_cell_ft">
                        <img src="/static/weui/images/vcode.jpg" />
                    </div>
                </div>
                <div class="weui_cell">
                    <div class="weui_cell_hd"><label class="weui_label">银行卡</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="number" pattern="[0-9]*" placeholder="请输入银行卡号"/>
                    </div>
                </div>
                <div class="weui_cell weui_vcode weui_cell_warn">
                    <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="number" placeholder="请输入验证码"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                        <img src="/static/weui/images/vcode.jpg" />
                    </div>
                </div>
            </div>
            <div class="weui_cells_tips">底部说明文字底部说明文字</div>
            <div class="weui_btn_area">
                <a class="weui_btn weui_btn_primary" href="javascript:" id="showTooltips">确定</a>
            </div>

            <div class="weui_cells_title">上传</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <div class="weui_uploader">
                            <div class="weui_uploader_hd weui_cell">
                                <div class="weui_cell_bd weui_cell_primary">图片上传</div>
                                <div class="weui_cell_ft">0/2</div>
                            </div>
                            <div class="weui_uploader_bd">
                                <ul class="weui_uploader_files">
                                    <li class="weui_uploader_file" style="background-image:url(http://shp.qpic.cn/weixinsrc_pic/pScBR7sbqjOBJomcuvVJ6iacVrbMJaoJZkFUIq4nzQZUIqzTKziam7ibg/)"></li>
                                    <li class="weui_uploader_file" style="background-image:url(http://shp.qpic.cn/weixinsrc_pic/pScBR7sbqjOBJomcuvVJ6iacVrbMJaoJZkFUIq4nzQZUIqzTKziam7ibg/)"></li>
                                    <li class="weui_uploader_file" style="background-image:url(http://shp.qpic.cn/weixinsrc_pic/pScBR7sbqjOBJomcuvVJ6iacVrbMJaoJZkFUIq4nzQZUIqzTKziam7ibg/)"></li>
                                    <li class="weui_uploader_file weui_uploader_status" style="background-image:url(http://shp.qpic.cn/weixinsrc_pic/pScBR7sbqjOBJomcuvVJ6iacVrbMJaoJZkFUIq4nzQZUIqzTKziam7ibg/)">
                                        <div class="weui_uploader_status_content">
                                            <i class="weui_icon_warn"></i>
                                        </div>
                                    </li>
                                    <li class="weui_uploader_file weui_uploader_status" style="background-image:url(http://shp.qpic.cn/weixinsrc_pic/pScBR7sbqjOBJomcuvVJ6iacVrbMJaoJZkFUIq4nzQZUIqzTKziam7ibg/)">
                                        <div class="weui_uploader_status_content">50%</div>
                                    </li>
                                </ul>
                                <div class="weui_uploader_input_wrp">
                                    <input class="weui_uploader_input" type="file" accept="image/jpg,image/jpeg,image/png,image/gif" multiple />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="weui_cells_title">文本域</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <textarea class="weui_textarea" placeholder="请输入评论" rows="3"></textarea>
                        <div class="weui_textarea_counter"><span>0</span>/200</div>
                    </div>
                </div>
            </div>
            <div class="weui_toptips weui_warn js_tooltips">格式不对</div>
            <div class="weui_cells_title">表单报错</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell weui_cell_warn">
                    <div class="weui_cell_hd"><label for="" class="weui_label">卡号</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="number" pattern="[0-9]*" value="weui input error" placeholder="请输入卡号"/>
                    </div>
                    <div class="weui_cell_ft">
                        <i class="weui_icon_warn"></i>
                    </div>
                </div>
                <div class="weui_cell">
                    <div class="weui_cell_hd"><label for="" class="weui_label">日期</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="date" value=""/>
                    </div>
                </div>
                <div class="weui_cell">
                    <div class="weui_cell_hd"><label for="" class="weui_label">时间</label></div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="datetime-local" value="" placeholder=""/>
                    </div>
                </div>
            </div>
            <div class="weui_cells_title">选择</div>
            <div class="weui_cells">

                <div class="weui_cell weui_cell_select weui_select_before">
                    <div class="weui_cell_hd">
                        <select class="weui_select" name="select2">
                            <option value="1">+86</option>
                            <option value="2">+80</option>
                            <option value="3">+84</option>
                            <option value="4">+87</option>
                        </select>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="number" pattern="[0-9]*" placeholder="请输入号码"/>
                    </div>
                </div>
            </div>
            <div class="weui_cells_title">选择</div>
            <div class="weui_cells">
                <div class="weui_cell weui_cell_select">
                    <div class="weui_cell_bd weui_cell_primary">
                        <select class="weui_select" name="select1">
                            <option selected="" value="1">微信号</option>
                            <option value="2">QQ号</option>
                            <option value="3">Email</option>
                        </select>
                    </div>
                </div>
                <div class="weui_cell weui_cell_select weui_select_after">
                    <div class="weui_cell_hd">
                        国家/地区
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <select class="weui_select" name="select2">
                            <option value="1">中国</option>
                            <option value="2">美国</option>
                            <option value="3">英国</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </script>
    <script type="text/html" id="tpl_toast">
        <div class="hd">
            <h1 class="page_title">Toast</h1>
        </div>
        <div class="bd spacing">
            <a href="javascript:;" class="weui_btn weui_btn_primary" id="showToast">点击弹出Toast</a>
            <a href="javascript:;" class="weui_btn weui_btn_primary" id="showLoadingToast">点击弹出Loading Toast</a>
        </div>
        <!--BEGIN toast-->
        <div id="toast" style="display: none;">
            <div class="weui_mask_transparent"></div>
            <div class="weui_toast">
                <i class="weui_icon_toast"></i>
                <p class="weui_toast_content">已完成</p>
            </div>
        </div>
        <!--end toast-->

        <!-- loading toast -->
        <div id="loadingToast" class="weui_loading_toast" style="display:none;">
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
    </script>
    <script type="text/html" id="tpl_dialog">
        <div class="hd">
            <h1 class="page_title">Dialog</h1>
        </div>
        <div class="bd spacing">
            <a href="javascript:;" class="weui_btn weui_btn_primary" id="showDialog1">点击弹出Dialog样式一</a>
            <a href="javascript:;" class="weui_btn weui_btn_primary" id="showDialog2">点击弹出Dialog样式二</a>
        </div>
        <!--BEGIN dialog1-->
        <div class="weui_dialog_confirm" id="dialog1" style="display: none;">
            <div class="weui_mask"></div>
            <div class="weui_dialog">
                <div class="weui_dialog_hd"><strong class="weui_dialog_title">弹窗标题</strong></div>
                <div class="weui_dialog_bd">自定义弹窗内容，居左对齐显示，告知需要确认的信息等</div>
                <div class="weui_dialog_ft">
                    <a href="javascript:;" class="weui_btn_dialog default">取消</a>
                    <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
                </div>
            </div>
        </div>
        <!--END dialog1-->
        <!--BEGIN dialog2-->
        <div class="weui_dialog_alert" id="dialog2" style="display: none;">
            <div class="weui_mask"></div>
            <div class="weui_dialog">
                <div class="weui_dialog_hd"><strong class="weui_dialog_title">弹窗标题</strong></div>
                <div class="weui_dialog_bd">弹窗内容，告知当前页面信息等</div>
                <div class="weui_dialog_ft">
                    <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
                </div>
            </div>
        </div>
        <!--END dialog2-->
    </script>
    <script type="text/html" id="tpl_progress">
        <div class="hd">
            <h1 class="page_title">Progress</h1>
        </div>
        <div class="bd spacing">
            <div class="weui_progress">
                <div class="weui_progress_bar">
                    <div class="weui_progress_inner_bar js_progress" style="width: 0%;"></div>
                </div>
                <a href="javascript:;" class="weui_progress_opr">
                    <i class="weui_icon_cancel"></i>
                </a>
            </div>
            <br>
            <div class="weui_progress">
                <div class="weui_progress_bar">
                    <div class="weui_progress_inner_bar js_progress" style="width: 50%;"></div>
                </div>
                <a href="javascript:;" class="weui_progress_opr">
                    <i class="weui_icon_cancel"></i>
                </a>
            </div>
            <br>
            <div class="weui_progress">
                <div class="weui_progress_bar">
                    <div class="weui_progress_inner_bar js_progress" style="width: 80%;"></div>
                </div>
                <a href="javascript:;" class="weui_progress_opr">
                    <i class="weui_icon_cancel"></i>
                </a>
            </div>
            <div class="weui_btn_area">
                <a href="javascript:;" class="weui_btn weui_btn_primary" id="btnStartProgress">上传</a>
            </div>
        </div>
    </script>
    <script type="text/html" id="tpl_msg">
        <div class="weui_msg">
            <div class="weui_icon_area"><i class="weui_icon_success weui_icon_msg"></i></div>
            <div class="weui_text_area">
                <h2 class="weui_msg_title">操作成功</h2>
                <p class="weui_msg_desc">内容详情，可根据实际需要安排</p>
            </div>
            <div class="weui_opr_area">
                <p class="weui_btn_area">
                    <a href="javascript:;" class="weui_btn weui_btn_primary">确定</a>
                    <a href="javascript:;" class="weui_btn weui_btn_default">取消</a>
                </p>
            </div>
            <div class="weui_extra_area">
                <a href="">查看详情</a>
            </div>
        </div>
    </script>
    <script type="text/html" id="tpl_article">
        <div class="hd">
            <h1 class="page_title">Article</h1>
        </div>
        <div class="bd">
            <article class="weui_article">
                <h1>大标题</h1>
                <section>
                    <h2 class="title">章标题</h2>
                    <section>
                        <h3>1.1 节标题</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                            consequat. Duis aute</p>
                    </section>
                    <section>
                        <h3>1.2 节标题</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    </section>
                </section>
            </article>
        </div>
    </script>
    <script type="text/html" id="tpl_tab">
        <div class="bd">
            <div class="weui_cells_title">Tab</div>
            <div class="weui_cells weui_cells_access">
                <a class="weui_cell" href="#/navbar">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>navbar</p>
                    </div>
                    <div class="weui_cell_ft">
                    </div>
                </a>
                <a class="weui_cell" href="#/tabbar">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>tabbar</p>
                    </div>
                    <div class="weui_cell_ft">
                    </div>
                </a>
            </div>
        </div>
    </script>
    <script type="text/html" id="tpl_navbar">
        <div class="bd" style="height: 100%;">
            <div class="weui_tab">
                <div class="weui_navbar">
                    <div class="weui_navbar_item weui_bar_item_on">
                        选项一
                    </div>
                    <div class="weui_navbar_item">
                        选项二
                    </div>
                    <div class="weui_navbar_item">
                        选项三
                    </div>
                </div>
                <div class="weui_tab_bd">

                </div>
            </div>
        </div>
    </script>
    <script type="text/html" id="tpl_tabbar">
        <div class="weui_tab">
            <div class="weui_tab_bd">

            </div>
            <div class="weui_tabbar">
                <a href="javascript:;" class="weui_tabbar_item weui_bar_item_on">
                    <div class="weui_tabbar_icon">
                        <img src="/static/weui/images/icon_nav_button.png" alt="">
                    </div>
                    <p class="weui_tabbar_label">微信</p>
                </a>
                <a href="javascript:;" class="weui_tabbar_item">
                    <div class="weui_tabbar_icon">
                        <img src="/static/weui/images/icon_nav_msg.png" alt="">
                    </div>
                    <p class="weui_tabbar_label">通讯录</p>
                </a>
                <a href="javascript:;" class="weui_tabbar_item">
                    <div class="weui_tabbar_icon">
                        <img src="/static/weui/images/icon_nav_article.png" alt="">
                    </div>
                    <p class="weui_tabbar_label">发现</p>
                </a>
                <a href="javascript:;" class="weui_tabbar_item">
                    <div class="weui_tabbar_icon">
                        <img src="/static/weui/images/icon_nav_cell.png" alt="">
                    </div>
                    <p class="weui_tabbar_label">我</p>
                </a>
            </div>
        </div>
    </script>
    <script type="text/html" id="tpl_panel">
        <div class="hd">
            <h1 class="page_title">Panel</h1>
        </div>
        <div class="bd">
            <div class="weui_panel weui_panel_access">
                <div class="weui_panel_hd">图文组合列表</div>
                <div class="weui_panel_bd">
                    <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                        <div class="weui_media_hd">
                            <img class="weui_media_appmsg_thumb" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAAAeFBMVEUAwAD///+U5ZTc9twOww7G8MYwzDCH4YcfyR9x23Hw+/DY9dhm2WZG0kbT9NP0/PTL8sux7LFe115T1VM+zz7i+OIXxhes6qxr2mvA8MCe6J6M4oz6/frr+us5zjn2/fa67rqB4IF13XWn6ad83nxa1loqyirn+eccHxx4AAAC/klEQVRo3u2W2ZKiQBBF8wpCNSCyLwri7v//4bRIFVXoTBBB+DAReV5sG6lTXDITiGEYhmEYhmEYhmEYhmEY5v9i5fsZGRx9PyGDne8f6K9cfd+mKXe1yNG/0CcqYE86AkBMBh66f20deBc7wA/1WFiTwvSEpBMA2JJOBsSLxe/4QEEaJRrASP8EVF8Q74GbmevKg0saa0B8QbwBdjRyADYxIhqxAZ++IKYtciPXLQVG+imw+oo4Bu56rjEJ4GYsvPmKOAB+xlz7L5aevqUXuePWVhvWJ4eWiwUQ67mK51qPj4dFDMlRLBZTqF3SDvmr4BwtkECu5gHWPkmDfQh02WLxXuvbvC8ku8F57GsI5e0CmUwLz1kq3kD17R1In5816rGvQ5VMk5FEtIiWislTffuDpl/k/PzscdQsv8r9qWq4LRWX6tQYtTxvI3XyrwdyQxChXioOngH3dLgOFjk0all56XRi/wDFQrGQU3Os5t0wJu1GNtNKHdPqYaGYQuRDfbfDf26AGLYSyGS3ZAK4S8XuoAlxGSdYMKwqZKM9XJMtyqXi7HX/CiAZS6d8bSVUz5J36mEMFDTlAFQzxOT1dzLRljjB6+++ejFqka+mXIe6F59mw22OuOw1F4T6lg/9VjL1rLDoI9Xzl1MSYDNHnPQnt3D1EE7PrXjye/3pVpr1Z45hMUdcACc5NVQI0bOdS1WA0wuz73e7/5TNqBPhQXPEFGJNV2zNqWI7QKBd2Gn6AiBko02zuAOXeWIXjV0jNqdKegaE/kJQ6Bfs4aju04lMLkA2T5wBSYPKDGF3RKhFYEa6A1L1LG2yacmsaZ6YPOSAMKNsO+N5dNTfkc5Aqe26uxHpx7ZirvgCwJpWq/lmX1hA7LyabQ34tt5RiJKXSwQ+0KU0V5xg+hZrd4Bn1n4EID+WkQdgLfRNtvil9SPfwy+WQ7PFBWQz6dGWZBLkeJFXZGCfLUjCgGgqXo5TuSu3cugdcTv/HjqnBTEMwzAMwzAMwzAMwzAMw/zf/AFbXiOA6frlMAAAAABJRU5ErkJggg==" alt="">
                        </div>
                        <div class="weui_media_bd">
                            <h4 class="weui_media_title">标题一</h4>
                            <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
                        </div>
                    </a>
                    <a href="javascript:void(0);" class="weui_media_box weui_media_appmsg">
                        <div class="weui_media_hd">
                            <img class="weui_media_appmsg_thumb" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAAAeFBMVEUAwAD///+U5ZTc9twOww7G8MYwzDCH4YcfyR9x23Hw+/DY9dhm2WZG0kbT9NP0/PTL8sux7LFe115T1VM+zz7i+OIXxhes6qxr2mvA8MCe6J6M4oz6/frr+us5zjn2/fa67rqB4IF13XWn6ad83nxa1loqyirn+eccHxx4AAAC/klEQVRo3u2W2ZKiQBBF8wpCNSCyLwri7v//4bRIFVXoTBBB+DAReV5sG6lTXDITiGEYhmEYhmEYhmEYhmEY5v9i5fsZGRx9PyGDne8f6K9cfd+mKXe1yNG/0CcqYE86AkBMBh66f20deBc7wA/1WFiTwvSEpBMA2JJOBsSLxe/4QEEaJRrASP8EVF8Q74GbmevKg0saa0B8QbwBdjRyADYxIhqxAZ++IKYtciPXLQVG+imw+oo4Bu56rjEJ4GYsvPmKOAB+xlz7L5aevqUXuePWVhvWJ4eWiwUQ67mK51qPj4dFDMlRLBZTqF3SDvmr4BwtkECu5gHWPkmDfQh02WLxXuvbvC8ku8F57GsI5e0CmUwLz1kq3kD17R1In5816rGvQ5VMk5FEtIiWislTffuDpl/k/PzscdQsv8r9qWq4LRWX6tQYtTxvI3XyrwdyQxChXioOngH3dLgOFjk0all56XRi/wDFQrGQU3Os5t0wJu1GNtNKHdPqYaGYQuRDfbfDf26AGLYSyGS3ZAK4S8XuoAlxGSdYMKwqZKM9XJMtyqXi7HX/CiAZS6d8bSVUz5J36mEMFDTlAFQzxOT1dzLRljjB6+++ejFqka+mXIe6F59mw22OuOw1F4T6lg/9VjL1rLDoI9Xzl1MSYDNHnPQnt3D1EE7PrXjye/3pVpr1Z45hMUdcACc5NVQI0bOdS1WA0wuz73e7/5TNqBPhQXPEFGJNV2zNqWI7QKBd2Gn6AiBko02zuAOXeWIXjV0jNqdKegaE/kJQ6Bfs4aju04lMLkA2T5wBSYPKDGF3RKhFYEa6A1L1LG2yacmsaZ6YPOSAMKNsO+N5dNTfkc5Aqe26uxHpx7ZirvgCwJpWq/lmX1hA7LyabQ34tt5RiJKXSwQ+0KU0V5xg+hZrd4Bn1n4EID+WkQdgLfRNtvil9SPfwy+WQ7PFBWQz6dGWZBLkeJFXZGCfLUjCgGgqXo5TuSu3cugdcTv/HjqnBTEMwzAMwzAMwzAMwzAMw/zf/AFbXiOA6frlMAAAAABJRU5ErkJggg==" alt="">
                        </div>
                        <div class="weui_media_bd">
                            <h4 class="weui_media_title">标题二</h4>
                            <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
                        </div>
                    </a>
                </div>
                <a class="weui_panel_ft" href="javascript:void(0);">查看更多</a>
            </div>
            <div class="weui_panel weui_panel_access">
                <div class="weui_panel_hd">文字组合列表</div>
                <div class="weui_panel_bd">
                    <div class="weui_media_box weui_media_text">
                        <h4 class="weui_media_title">标题一</h4>
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
                    </div>
                    <div class="weui_media_box weui_media_text">
                        <h4 class="weui_media_title">标题二</h4>
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
                    </div>
                </div>
                <a href="javascript:void(0);" class="weui_panel_ft">查看更多</a>
            </div>
            <div class="weui_panel">
                <div class="weui_panel_hd">小图文组合列表</div>
                <div class="weui_panel_bd">
                    <div class="weui_media_box weui_media_small_appmsg">
                        <div class="weui_cells weui_cells_access">
                            <a class="weui_cell" href="javascript:;">
                                <div class="weui_cell_hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>
                                <div class="weui_cell_bd weui_cell_primary">
                                    <p>文字标题</p>
                                </div>
                                <span class="weui_cell_ft"></span>
                            </a>
                            <a class="weui_cell" href="javascript:;">
                                <div class="weui_cell_hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>
                                <div class="weui_cell_bd weui_cell_primary">
                                    <p>文字标题</p>
                                </div>
                                <span class="weui_cell_ft"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="weui_panel">
                <div class="weui_panel_hd">文字列表附来源</div>
                <div class="weui_panel_bd">
                    <div class="weui_media_box weui_media_text">
                        <h4 class="weui_media_title">标题一</h4>
                        <p class="weui_media_desc">由各种物质组成的巨型球状天体，叫做星球。星球有一定的形状，有自己的运行轨道。</p>
                        <ul class="weui_media_info">
                            <li class="weui_media_info_meta">文字来源</li>
                            <li class="weui_media_info_meta">时间</li>
                            <li class="weui_media_info_meta weui_media_info_meta_extra">其它信息</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script type="text/html" id="tpl_actionsheet">
        <div class="hd">
            <h1 class="page_title">ActionSheet</h1>
        </div>
        <div class="bd spacing">
            <a href="javascript:;" class="weui_btn weui_btn_primary" id="showActionSheet">点击上拉ActionSheet</a>
        </div>
        <!--BEGIN actionSheet-->
        <div id="actionSheet_wrap">
            <div class="weui_mask_transition" id="mask"></div>
            <div class="weui_actionsheet" id="weui_actionsheet">
                <div class="weui_actionsheet_menu">
                    <div class="weui_actionsheet_cell">示例菜单</div>
                    <div class="weui_actionsheet_cell">示例菜单</div>
                    <div class="weui_actionsheet_cell">示例菜单</div>
                    <div class="weui_actionsheet_cell">示例菜单</div>
                </div>
                <div class="weui_actionsheet_action">
                    <div class="weui_actionsheet_cell" id="actionsheet_cancel">取消</div>
                </div>
            </div>
        </div>
        <!--END actionSheet-->
    </script>
    <script type="text/html" id="tpl_icons">
        <div class="hd">
            <h1 class="page_title">Icons</h1>
        </div>
        <div class="bd spacing">
            <i class="weui_icon_msg weui_icon_success"></i>
            <i class="weui_icon_msg weui_icon_info"></i>
            <i class="weui_icon_msg weui_icon_warn"></i>
            <i class="weui_icon_msg weui_icon_waiting"></i>
            <i class="weui_icon_safe weui_icon_safe_success"></i>
            <i class="weui_icon_safe weui_icon_safe_warn"></i>
            <div class="icon_sp_area">
                <i class="weui_icon_success"></i>
                <i class="weui_icon_success_circle"></i>
                <i class="weui_icon_success_no_circle"></i>
                <i class="weui_icon_info"></i>
                <i class="weui_icon_waiting"></i>
                <i class="weui_icon_waiting_circle"></i>
                <i class="weui_icon_circle"></i>
                <i class="weui_icon_warn"></i>
                <i class="weui_icon_download"></i>
                <i class="weui_icon_info_circle"></i>
                <i class="weui_icon_cancel"></i>
                <i class="weui_icon_search"></i>
                <i class="weui_icon_clear"></i>
            </div>
        </div>
    </script>
    <script type="text/html" id="tpl_searchbar">
        <div class="hd">
            <h1 class="page_title">searchBar</h1>
        </div>
        <div class="bd">
            <!--<a href="javascript:;" class="weui_btn weui_btn_primary">点击展现searchBar</a>-->
            <div class="weui_search_bar" id="search_bar">
                <form class="weui_search_outer">
                    <div class="weui_search_inner">
                        <i class="weui_icon_search"></i>
                        <input type="search" class="weui_search_input" id="search_input" placeholder="搜索" required/>
                        <a href="javascript:" class="weui_icon_clear" id="search_clear"></a>
                    </div>
                    <label for="search_input" class="weui_search_text" id="search_text">
                        <i class="weui_icon_search"></i>
                        <span>搜索</span>
                    </label>
                </form>
                <a href="javascript:" class="weui_search_cancel" id="search_cancel">取消</a>
            </div>
            <div class="weui_cells weui_cells_access search_show" id="search_show">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>实时搜索文本</p>
                    </div>
                </div>
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>实时搜索文本</p>
                    </div>
                </div>
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>实时搜索文本</p>
                    </div>
                </div>
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>实时搜索文本</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </script>

    <script type="text/html" id="tpl_input">
        <div class="hd">
            <h1 class="page_title">Input</h1>
        </div>
        <div class="bd">
            <div class="weui_cells_title">Input</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label class="weui_label">QQ</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="tel" placeholder="请输入QQ号">
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/html" id="tpl_textarea">
        <div class="hd">
            <h1 class="page_title">TextArea</h1>
        </div>
        <div class="bd">
            <div class="weui_cells_title">意见反馈</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <textarea class="weui_textarea" placeholder="请输入意见反馈" rows="3"></textarea>
                        <div class="weui_textarea_counter"><span>0</span>/200</div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/html" id="tpl_radio">
        <div class="hd">
            <h1 class="page_title">Radio</h1>
        </div>
        <div class="bd">
            <div class="weui_cells_title">单选列表项</div>
            <div class="weui_cells weui_cells_radio">
                <label class="weui_cell weui_check_label" for="x11">
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>cell standard</p>
                    </div>
                    <div class="weui_cell_ft">
                        <input type="radio" class="weui_check" name="radio1" id="x11">
                        <span class="weui_icon_checked"></span>
                    </div>
                </label>
                <label class="weui_cell weui_check_label" for="x12">

                    <div class="weui_cell_bd weui_cell_primary">
                        <p>cell standard</p>
                    </div>
                    <div class="weui_cell_ft">
                        <input type="radio" name="radio1" class="weui_check" id="x12" checked="checked">
                        <span class="weui_icon_checked"></span>
                    </div>
                </label>
            </div>
        </div>
    </script>

    <script type="text/html" id="tpl_checkbox">
        <div class="hd">
            <h1 class="page_title">Checkbox</h1>
        </div>
        <div class="bd">
            <div class="weui_cells_title">复选列表项</div>
            <div class="weui_cells weui_cells_checkbox">
                <label class="weui_cell weui_check_label" for="s11">
                    <div class="weui_cell_hd">
                        <input type="checkbox" class="weui_check" name="checkbox1" id="s11" checked="checked">
                        <i class="weui_icon_checked"></i>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>standard is dealt for u.</p>
                    </div>
                </label>
                <label class="weui_cell weui_check_label" for="s12">
                    <div class="weui_cell_hd">
                        <input type="checkbox" name="checkbox1" class="weui_check" id="s12">
                        <i class="weui_icon_checked"></i>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <p>standard is dealicient for u.</p>
                    </div>
                </label>
            </div>
        </div>
    </script>

    <script type="text/html" id="tpl_select">
        <div class="hd">
            <h1 class="page_title">Select</h1>
        </div>
        <div class="bd">
            <div class="weui_cells_title">选择</div>
            <div class="weui_cells">
                <div class="weui_cell weui_cell_select weui_select_before">
                    <div class="weui_cell_hd">
                        <select class="weui_select" name="select2">
                            <option value="1">+86</option>
                            <option value="2">+80</option>
                            <option value="3">+84</option>
                            <option value="4">+87</option>
                        </select>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="tel" placeholder="请输入号码">
                    </div>
                </div>
            </div>
            <div class="weui_cells_title">选择</div>
            <div class="weui_cells">
                <div class="weui_cell weui_cell_select">
                    <div class="weui_cell_bd weui_cell_primary">
                        <select class="weui_select" name="select1">
                            <option selected="" value="1">微信号</option>
                            <option value="2">QQ号</option>
                            <option value="3">Email</option>
                        </select>
                    </div>
                </div>
                <div class="weui_cell weui_cell_select weui_select_after">
                    <div class="weui_cell_hd">
                        国家/地区
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <select class="weui_select" name="select2">
                            <option value="1">中国</option>
                            <option value="2">美国</option>
                            <option value="3">英国</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/html" id="tpl_switch">
        <div class="hd">
            <h1 class="page_title">Switch</h1>
        </div>
        <div class="bd">
            <div class="weui_cells_title">开关</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell weui_cell_switch">
                    <div class="weui_cell_hd weui_cell_primary">声音</div>
                    <div class="weui_cell_ft">
                        <input class="weui_switch" type="checkbox">
                    </div>
                </div>
                <div class="weui_cell weui_cell_switch">
                    <div class="weui_cell_hd weui_cell_primary">振动</div>
                    <div class="weui_cell_ft">
                        <input class="weui_switch" type="checkbox" checked>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/html" id="tpl_uploader">
        <div class="hd">
            <h1 class="page_title">Uploader</h1>
        </div>
        <div class="bd">
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <div class="weui_uploader">
                            <div class="weui_uploader_hd weui_cell">
                                <div class="weui_cell_bd weui_cell_primary">图片上传</div>
                                <div class="weui_cell_ft">0/2</div>
                            </div>
                            <div class="weui_uploader_bd">
                                <ul class="weui_uploader_files">
                                    <li class="weui_uploader_file" style="background-image:url(/static/weui/images/upload_0.jpg)"></li>
                                    <li class="weui_uploader_file weui_uploader_status" style="background-image:url(/static/weui/images/upload_0.jpg)">
                                        <div class="weui_uploader_status_content">
                                            <i class="weui_icon_warn"></i>
                                        </div>
                                    </li>
                                    <li class="weui_uploader_file weui_uploader_status" style="background-image:url(/static/weui/images/upload_1.jpg)">
                                        <div class="weui_uploader_status_content">50%</div>
                                    </li>
                                </ul>
                                <div class="weui_uploader_input_wrp">
                                    <input class="weui_uploader_input" type="file" accept="image/*" multiple="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script src="/static/weui/js/zepto.min.js"></script>
    <script src="/static/weui/js/router.min.js"></script>
    <script src="/static/public/js/index/demo.js"></script>
    <?php include dirname(dirname(__FILE__)).'/common/weui/footer.php';?>