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
                 <!--    <div class="widget-head am-cf">
                        <div class="widget-title am-fl"><?php echo ($info['id'] ? '编辑' : '添加'); ?>任务表</div>
                    </div> -->
                    <div class="widget-body am-fr">
                        <form action="/index.php/Admin/Task/editTask" method="post" class="ajaxForm am-form tpl-form-border-form tpl-form-border-br">
                             <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">上级分类 <span class="tpl-form-line-small-title"></span></label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <div class="tpl-table-list-select am-align-left" id="goods_cat_id_div" style="width:100%;">
                                        <!-- <input id="category" type="hidden" name="category_id" id="goods_cat_id" value="<?php echo ($info["category_id"]); ?>" /> -->
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label for="title" class="am-u-sm-3 am-form-label">
                                    发布人名称
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="text" disabled="disabled" value="<?php echo ($info['user_name']); ?>"
                                           placeholder="">
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label for="title" class="am-u-sm-3 am-form-label">
                                    任务标题
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="text" id="title" name="title" value="<?php echo ($info['title']); ?>"
                                           placeholder="请输入任务标题">
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label for="mobile_type" class="am-u-sm-3 am-form-label">
                                    支持设备
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                <div >
                                    <select  id="type" name="mobile_type" data-am-selected="">
                                        <option >请选择支持设备</option>
                                        <option value="全部">全部</option>
                                        <option value="安卓">安卓</option>
                                        <option value="苹果">苹果</option>
                                    </select>
                                     <script>
                                       $('#type').val('<?php echo ($info['mobile_type']); ?>');
                                     </script>
                                </div>
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label for="end_time" class="am-u-sm-3 am-form-label">
                                    任务截止时间
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="text" onclick="laydate({format:'YYYY-MM-DD hh:mm:ss',festival: true})"  class="input-text"  name="end_time" id="end_time" value="<?php echo (time_format($info['end_time'],'Y-m-d H:i:s')); ?>" placeholder="请选择任务结束时间">
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label for="price" class="am-u-sm-3 am-form-label">
                                    出价金额（分）
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="number" id="price" name="price" value="<?php echo ($info['price']); ?>"
                                           placeholder="请输入出价金额（分）">
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label for="task_num" class="am-u-sm-3 am-form-label">
                                    任务数量
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="number" id="task_num" name="task_num" value="<?php echo ($info['task_num']); ?>"
                                           placeholder="请输入任务数量">
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label for="total_price" class="am-u-sm-3 am-form-label">
                                    总金额(分)
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="number" id="total_price" name="total_price" value="<?php echo ($info['total_price']); ?>"
                                           placeholder="请输入总金额(分)">
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label for="link_url" class="am-u-sm-3 am-form-label">
                                    链接地址
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="text" id="link_url" name="link_url" value="<?php echo ($info['link_url']); ?>"
                                           placeholder="请输入链接地址">
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label for="validate_words" class="am-u-sm-3 am-form-label">
                                    验证文字内容
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="text" id="validate_words" name="validate_words" value="<?php echo ($info['validate_words']); ?>"
                                           placeholder="请输入验证文字内容">
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label for="remark" class="am-u-sm-3 am-form-label">
                                    备注
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="text" id="remark" name="remark" value="<?php echo ($info['remark']); ?>"
                                           placeholder="请输入备注">
                                    <small></small>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label for="audit_info" class="am-u-sm-3 am-form-label">
                                    审核理由说明
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                    <input type="text" id="audit_info" name="audit_info" value="<?php echo ($info['audit_info']); ?>"
                                           placeholder="请输入审核理由说明">
                                    <small></small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label for="mobile_type" class="am-u-sm-3 am-form-label">
                                     审核状态
                                    <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-7 am-u-sm-pull-2">
                                <div >
                                    <select  id="audit" name="audit_status" data-am-selected="">
                                        <option >请选择审核状态</option>
                                        <option value="0">待审核</option>
                                        <option value="1">通过</option>
                                        <option value="2">未通过</option>
                                    </select>
                                     <script>
                                       $('#audit').val('<?php echo ($info['audit_status']); ?>');
                                     </script>
                                </div>
                                    <small></small>
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
    
<script type="text/javascript" src="/Application/Admin/Statics/layDate-v5.0.9/laydate/laydate.js"></script>
<script type="text/javascript">
        var catListStr = <?php echo ($catListStr); ?>;
        var cat_ids = <?php echo ($cat_ids); ?>;
        $(function(){
            initCatSelect();
        })
        laydate.render({
            elem: '#start_time',
            type: 'datetime'
        });
        laydate.render({
            elem: '#end_time',
            type: 'datetime'
        });
        _NEED_REFRESH = true;
</script>
<script type="text/javascript" charset="utf-8" src="/Application/Admin/Statics/js/goods_class.js?v=1.0"></script>

</body>
</html>