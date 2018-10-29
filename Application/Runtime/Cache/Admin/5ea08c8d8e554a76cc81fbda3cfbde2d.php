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
                        <div class="widget-title  am-cf">意见反馈列表</div>
                    </div>

                    <div class="widget-body  am-fr">
                        <div class="am-u-sm-12 am-u-md-8 am-u-lg-8">
                            <div class="am-form-group">
                                <div class="am-btn-toolbar">
                                    <div class="am-btn-group am-btn-group-xs">
                                        <button type="button" class="am-btn am-btn-default am-btn-danger" onclick="javascript:recycle('chkbId', '确认删除?! 删除后无法恢复!', true)">
                                            <span class="am-icon-trash-o"></span> 批量删除
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="/index.php/Admin/FeedBack/listFeedBack" method="get">

                            <div class="am-u-sm-12 am-u-md-4 am-u-lg-4">
                                <div class="am-input-group am-input-group-sm tpl-form-border-form cl-p">
                                    <input type="text" class="am-form-field" name="mobile" placeholder="请输入手机号码查询" value="<?php echo ($mobile); ?>">
                                    <span class="am-input-group-btn">
                                        <button class="am-btn  am-btn-default am-btn-success tpl-table-list-field am-icon-search" type="submit"></button>
                                        <a class="am-btn  am-btn-default am-btn-danger tpl-table-list-field am-icon-refresh" type="button" href="/index.php/Admin/FeedBack/listFeedBack"></a>
                                    </span>
                                </div>
                            </div>

                            <input name="p" value="1" type="hidden"/>
                        </form>

                        <div class="am-u-sm-12">
                            <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black " id="example-r">
                                <thead>
                                    <tr>
                                        <th width="2%"><input class="check-all" type="checkbox"></th>
                                        <th class="align-center" width="4%">ID</th>
                                        <th class="align-center" width="4%">反馈人手机号</th>
                                        <th class="align-center" width="18%">反馈时间</th>
                                        <th class="align-center" width="10%">类型</th>
                                        <th width="15%">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(is_array($info)): foreach($info as $key=>$v): ?><tr class="gradeX" title="<?php echo ($v['comment']); ?>">
                                            <td><input class="ids" type="checkbox" name="chkbId" value="<?php echo ($v['id']); ?>"></td>
                                            <td class="align-center"><?php echo ($v['id']); ?></td>
                                            <td class="align-center"><?php echo ($v['mobile']); ?></td>
                                            <td class="align-center"><?php echo time_format($v['create_time']);?></td>
                                            <td class="align-center"><?php echo show_feed_type($v['type']);?></td>
                                            <td class="f-14">
                                                <div class="tpl-table-black-operation">
                                                    <a href="<?php echo U('FeedBack/feedBackInfo',array('id'=>$v['id']));?>">
                                                        <i class="am-icon-eye"></i> 查看
                                                    </a>
                                                    <a href="javascript:void(0)" onclick="javascript:recycle(<?php echo ($v['id']); ?>, '确认删除?!此步骤无法恢复!', true)" class="tpl-table-black-operation-del">
                                                        <i class="am-icon-trash"></i> 删除
                                                    </a>
                                                </div>
                                            </td>
                                        </tr><?php endforeach; endif; ?>

                                <!-- more data -->
                                </tbody>
                            </table>

                            <?php if(empty($info)): ?><h4>aOh! 没有相关内容!</h4><?php endif; ?>
                        </div>
                        <div class="am-u-lg-12 am-cf">

                            <div class="am-fr">
                                <div class="am-pagination tpl-pagination">
                                    <?php echo ($page); ?>
                                </div>
                            </div>
                        </div>
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
    
    <script type="text/javascript">
    </script>

</body>
</html>