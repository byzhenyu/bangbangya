<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html lang="zh">
<head>
  <script>
      // 防止iframe
      if(self != top)
          top.location.replace(self.location);
  </script>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>欢迎使用	<?php echo C('WEB_TITLE');?>, 请登录</title>
  <link rel="stylesheet" type="text/css" href="/Application/Admin/Statics/css/login.css?v=1.0">
  <script type="text/javascript" src="/Public/jquery-2.0.3.min.js"></script>
</head>
<body>
<div class="htmleaf-container">
  <div class="wrapper">
    <div class="container">
      <h1><?php echo C('WEB_TITLE');?>管理平台</h1>

      <form class="form ajaxForm" name="login" action="<?php echo U('Login/dologin');?>" method="post">
        <input type="text" name="admin_name" placeholder="用户名" value="">
        <input type="password" name="password" placeholder="密码" value="">

        <div class="formControls">
          <input class="input-text" type="text" placeholder="验证码" name="verify" style="width:100px;" >
          <img id="chk_img" src="<?php echo U('Login/chkcode','','');?>"><a id="changeChkcode" href="javascript:;">换一张</a>
        </div>

        <button type="submit" id="login-button">登录</button>
      </form>
    </div>

    <ul class="bg-bubbles">
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
    </ul>
  </div>
</div>
<script type="text/javascript" src="/Public/layer/layer.js"></script>
<script type="text/javascript" src="/Application/Admin/Statics/js/common.js"></script>
<script type="text/javascript" src="/Static/js/ajaxForm.js"></script>
<script>
    $("#chk_img").click(function(){
        $(this).attr('src',"<?php echo U('Login/chkcode','','');?>#" + Math.random());
    })
    $("#changeChkcode").click(function(){
        $(this).prev('img').trigger('click');
    })

    function validate() {
        var admin_name = $("input[name=admin_name]").val();
        if (admin_name == "") {
            toastr("请输入登录帐号");
            return false;
        }

        var password = $("input[name=password]").val();
        if (password == "") {
            toastr("请输入登录密码");
            return false;
        }


//        var chkcode = $("input[name=chkcode]").val();
//        if (chkcode == "" || (chkcode.length < 4 || chkcode.length > 4)) {
//            toastr("请输入4位有效验证码");
//            return false;
//        }
    }

    function callback(data) {
        if(!data.status){
            toastr(data.info);
            $("#changeChkcode").trigger('click');
            return false;
        }else{
            window.location.href = "<?php echo U('/Admin/Index/index');?>";
        }

    }
</script>


</body>
</html>