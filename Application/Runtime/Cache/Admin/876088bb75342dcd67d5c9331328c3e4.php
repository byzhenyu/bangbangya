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
                        <div class="widget-title am-fl"><?php echo ($catInfo['article_cat_id'] ? '编辑' : '添加'); ?>文章分类</div>
                    </div>
                    <div class="widget-body am-fr">

                        <form action="/index.php/Admin/ArticleCategory/editArticleCategory" method="post" class="ajaxForm am-form tpl-form-border-form tpl-form-border-br">
                            <div class="am-form-group">
                                <label for="cat_name" class="am-u-sm-2 am-form-label">
                                    分类名称 <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" id="cat_name" name="cat_name" placeholder="请输入标题文字"  value="<?php echo ($catInfo['cat_name']); ?>">
                                    <small>请填写标题文字1-25字符。</small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label">
                                    排序 <span class="tpl-form-line-small-title must-input">*</span>
                                </label>
                                <div class="am-u-sm-10">
                                    <div class="row">
                                        <div class="am-u-sm-3">
                                            <input type="text" placeholder="从小到大排序" id="sort" name="sort" value="<?php echo ($catInfo['sort']); ?>">
                                            <small>请输入整数类型</small>
                                        </div>
                                        <div class="am-u-sm-9"></div></div>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label">是否显示</label>
                                <div class="am-u-sm-10">
                                    <div class="am-form-group">
                                        <label class="am-radio-inline">
                                            <input type="radio" name="display" id="display" value="1" data-am-ucheck> 显示
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="display" id="hide" value="0" data-am-ucheck> 隐藏
                                        </label>
                                    </div>
                                    <?php if($articleInfo['display'] == 1 or $articleInfo['display'] == ''): ?><script>
                                            $('#display').attr('checked','true');
                                        </script>
                                        <?php else: ?>
                                        <script>
                                            $('#hide').attr('checked','true');
                                        </script><?php endif; ?>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <div class="am-u-sm-10 am-u-sm-push-2">
                                    <input type="hidden" name="article_cat_id" value="<?php echo ($catInfo['article_cat_id']); ?>">
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