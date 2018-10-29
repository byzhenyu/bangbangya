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
                        <div class="widget-title am-fl"><?php echo ($info['id'] ? '编辑' : '添加'); ?>任务类型</div>
                    </div>
                    <div class="widget-body am-fr">
                        <form action="/index.php/Admin/TaskCategory/editTaskCategory" method="post" class="ajaxForm am-form tpl-form-border-form tpl-form-border-br">
                            <div class="am-form-group">
                                <label for="category_name" class="am-u-sm-3 am-form-label">
                                    分类名称
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="text" id="category_name" name="category_name" value="<?php echo ($info['category_name']); ?>"
                                           placeholder="请输入">
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label for="category_explain" class="am-u-sm-3 am-form-label">
                                    类别说明
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="text" id="category_name" name="category_explain" value="<?php echo ($info['category_explain']); ?>"
                                           placeholder="请输入">
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">
                                    分类图片
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-9">
                                    <div class="row">
                                        <div class="am-u-sm-4">
                                            <div class="am-form-group am-form-file">
                                                <div class="tpl-form-file-img">
                                                    <img src="<?php echo ($info['category_img']); ?>" alt="" style="min-height:122px; width: 122px;" id="img_">
                                                </div>
                                                <input type="hidden" value="<?php echo ($info['category_img']); ?>" name="category_img" id="img" />
                                                <button type="button" class="am-btn am-btn-success am-btn-sm" id="btnUpload">上传</button>
                                                <button type="button" class="am-btn am-btn-danger am-btn-sm" onclick="delFile($('#img').val(), '')" id="btn_delete_">删除</button>
                                                <?php if($info['category_img'] == ''): ?><script>
                                                        $("#img_, #btn_delete_").hide();
                                                    </script><?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="am-u-sm-8"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3">
                                    <input type="hidden" name="id" value="<?php echo ($info['id']); ?>">
                                    <button type="submit" class="am-btn am-btn-primary tpl-btn-bg-color-success">提交</button>
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
    
    <script type="text/javascript" charset="utf-8" src="/Public/ajaxupload/ajaxupload.js?v=1.0"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/ajaxupload/imgupload.js?v=1.0"></script>
    <script type="text/javascript">

        $(function(){
            ajaxUpload('#btnUpload', $("#img"), 'TaskCategory', '');
        })
    </script>

</body>
</html>