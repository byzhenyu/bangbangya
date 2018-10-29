<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo C('WEB_TITLE');?> - 六牛科技技术支持</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <link rel="stylesheet" type="text/css" href="/Application/Admin/Statics/ui/css/admin.css?v=1.1" media="all">
    <link rel="stylesheet" type="text/css" href="/Application/Admin/Statics/ui/css/amazeui.min.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Application/Admin/Statics/ui/css/app.css?v=1.07" media="all">
    <!-- <script type="text/javascript" src="/Application/Admin/Statics/layDate-v5.0.9/laydate/laydate.js"></script> -->
    <!--[if lt IE 9]-->
    <script type="text/javascript" src="/Public/jquery-1.10.2.min.js"></script>
    <!--[endif]-->
    <script type="text/javascript" src="/Public/jquery-2.0.3.min.js"></script>
    


</head>
<body>
    
    <!-- 内容区 -->
    <div id="content">
        
    <div class="row-content am-cf">
        <div class="row">
           <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
                <div class="widget am-cf">
                    <div class="widget-head am-cf">
                        <div class="widget-title am-fl"><?php echo ($config['id'] ? '编辑' : '添加'); ?>参数配置</div>
                    </div>
                    <div class="widget-body am-fr">

                        <form action="/index.php/Admin/Config/editConfig" method="post" class="ajaxForm am-form tpl-form-border-form tpl-form-border-br">
                            <div class="am-form-group">
                                <label for="key" class="am-u-sm-3 am-form-label">
                                    配置标识 <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="text" class="tpl-form-input" id="key" name="key" placeholder="请输入配置标识"  value="<?php echo ($config["key"]); ?>">
                                    <small>请填写标题文字1-30字符。</small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label for="name" class="am-u-sm-3 am-form-label">
                                    配置标题 <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="text" class="tpl-form-input" id="name" name="name" placeholder="请输入配置标题"  value="<?php echo ($config["name"]); ?>">
                                    <small>请填写标题文字1-60字符。</small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label for="group" class="am-u-sm-3 am-form-label">
                                    配置分组 <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <div class="tpl-table-list-select am-align-left">
                                        <select data-am-selected="{}" style="display: none;" name="group" id="group">
                                            <option value="0">选择参数所属配置分组</option>
                                            <?php if(is_array(C("CONFIG_GROUP_LIST"))): $i = 0; $__LIST__ = C("CONFIG_GROUP_LIST");if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" ><?php echo ($group); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                        <?php if($config['group'] != 0): ?><script>
                                                $('#group').val('<?php echo ($config["group"]); ?>');
                                            </script><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label for="type" class="am-u-sm-3 am-form-label">
                                    配置类型 <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <div class="tpl-table-list-select am-align-left">
                                        <select data-am-selected="" style="display: none;" name="type" id="type">
                                            <option value="0">选择参数所属配置类型</option>
                                            <?php if(is_array(C("CONFIG_TYPE_LIST"))): $i = 0; $__LIST__ = C("CONFIG_TYPE_LIST");if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" ><?php echo ($type); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                        <?php if($config['type'] != 0): ?><script>
                                                $('#type').val('<?php echo ($config["type"]); ?>');
                                            </script><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label for="value" class="am-u-sm-3 am-form-label">
                                    配置值 <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <textarea type="text" class="tpl-form-input" id="value" name="value" placeholder="请输入配置值"><?php echo ($config["value"]); ?></textarea>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label for="desc" class="am-u-sm-3 am-form-label">
                                    配置项说明
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <textarea type="text" class="tpl-form-input" id="desc" name="desc" placeholder="配置项说明" ><?php echo ($config["desc"]); ?></textarea>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    排序 <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <div class="row">
                                        <div class="am-u-sm-3">
                                            <input type="text" placeholder="从小到大排序" id="sort" name="sort" value="<?php echo ($config['sort']); ?>">
                                            <small>请输入整数类型</small>
                                        </div>
                                        <div class="am-u-sm-7 am-u-sm-pull-2"></div></div>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">是否启用</label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <div class="am-form-group">
                                        <label class="am-radio-inline">
                                            <input type="radio" name="status" id="able" value="0" data-am-ucheck> 启用
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="status" id="disable" value="1" data-am-ucheck> 禁用
                                        </label>
                                    </div>
                                    <?php if($config['status'] == 0 or $config['display'] == ''): ?><script>
                                            $('#able').attr('checked','true');
                                        </script>
                                    <?php else: ?>
                                        <script>
                                            $('#disable').attr('checked','true');
                                        </script><?php endif; ?>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3">
                                    <input type="hidden" name="id" value="<?php echo ($config['id']); ?>">
                                    <button type="submit" class="am-btn am-btn-primary tpl-btn-bg-color-success ">提交</button>
                                    <button type="button" class="am-btn am-btn-primary am-btn-warning " onclick="goback();">返回</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <div class="align-center" style="margin-top: 0px; margin-bottom:15px;">
        <!--<small>版权所有 &copy; <a href="javascript:void(0)" target="_blank" >六牛科技</a></small>-->
    </div>
    <!-- /内容区 -->
    <script type="text/javascript" src="/Application/Admin/Statics/ui/js/theme.js"></script>
    <script type="text/javascript" src="/Application/Admin/Statics/ui/js/amazeui.min.js"></script>
    <script type="text/javascript" src="/Application/Admin/Statics/ui/js/amazeui.datatables.min.js"></script>
    <script type="text/javascript" src="/Application/Admin/Statics/ui/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="/Application/Admin/Statics/ui/js/app.js?v=1.02"></script>
    <script type="text/javascript" src="/Public/layer/layer.js"></script>
    <script type="text/javascript" src="/Application/Admin/Statics/js/common.js?v=1.01"></script>
    <script type="text/javascript" src="/Static/js/ajaxForm.js"></script>


    <script>
        // 定义全局变量
        RECYCLE_URL = "<?php echo U('recycle');?>"; // 默认逻辑删除操作执行的地址
        RESTORE_URL = "<?php echo U('restore');?>"; // 默认逻辑删除恢复执行的地址
        DELETE_URL = "<?php echo U('del');?>"; // 默认删除操作执行的地址
        UPLOAD_IMG_URL = "<?php echo U('uploadImg');?>"; // 默认上传图片地址
        UPLOAD_FIELD_URL = "<?php echo U('uploadField');?>"; // 默认上传图片地址
        DELETE_FILE_URL = "<?php echo U('delFile');?>"; // 默认删除图片执行的地址
        CHANGE_STAUTS_URL = "<?php echo U('changeDisabled');?>"; // 修改数据的启用状态
    </script>
    


</body>
</html>