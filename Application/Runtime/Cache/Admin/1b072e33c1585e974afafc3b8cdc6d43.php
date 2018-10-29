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
                        <div class="widget-title am-fl"><?php echo ($info['id'] ? '编辑' : '添加'); ?>店铺</div>
                    </div>
                    <div class="widget-body am-fr">
                        <form action="/index.php/Admin/Shop/shopDetail" method="post" class="ajaxForm am-form tpl-form-border-form tpl-form-border-br">


                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    店铺名称
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <?php echo ($info['shop_name']); ?>
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    头像
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <img src="<?php echo ($info['shop_img']); ?>">
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    保证金
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <?php echo ($info['shop_accounts']); ?>
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    置顶截止时间
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <?php echo time_format($info['top_time']);?>
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    合作商类型
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <?php echo getShopLevel($info['shop_type']);?>
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    合作商截止时间
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <?php echo time_format($info['partner_time']);?>
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    发任务量
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                         <?php echo ($info['task_count']); ?>
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    接任务
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <?php echo ($info['magic_guild_num']); ?>
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    总发单
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <?php echo ($info['task_count']); ?>
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    总成交
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                   <?php echo ($info['vol']); ?>
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    申诉
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                   <?php echo ($info['complain_num']); ?>
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    被申诉
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                  <?php echo ($info['be_complain_num']); ?>
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3">

                                    <button type="button" class="am-btn am-btn-primary am-btn-warning" onclick="goback();">返回</button>
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