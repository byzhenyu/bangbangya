<?php

    return array(
        /* 数据库设置 */
        'DB_TYPE'               =>  'mysqli',     // 数据库类型
        'DB_HOST'               =>  '47.105.143.119', // 服务器地址
        'DB_NAME'               =>  'ln_bangbangya',          // 数据库名
        'DB_USER'               =>  'xijiushop',      // 用户名
        'DB_PWD'                =>  'xijiushop',          // 密码
        'DB_PORT'               =>  '3306',        // 端口
        'DB_PREFIX'             =>  'ln_',    // 数据库表前缀
        'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8

        /* XSS过滤 */
        'DEFAULT_FILTER'    => 'trim,filter_xss',
        
        /* 模板路径配置 */
        // 'SHOW_PAGE_TRACE' => true,
        'TMPL_PARSE_STRING' => array(
            '__PUBLIC__'    => '/Public',
            '__STATIC__'    => '/Static',
            '__ADMIN__'     => '/Application/Admin/Statics',
            '__SHOP__'     => '/Application/Shop/Statics',
            '__HOME__'     => '/Application/Home/Statics',
            '__JS__'      => '/Application/Admin/Statics/js',
            '__UPLOADS__'   => '/Uploads/',
        ),

        'URL_HTML_SUFFIX'       =>  '',  // URL伪静态后缀设置

        //'MODULE_ALLOW_LIST' => array ('Home','Core','Admin', 'Payment', 'Api','Shop'),
        'DEFAULT_MODULE' => 'Admin',

        /* 图片上传相关配置 */
        'PICTURE_UPLOAD' => array(
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => 2*1024*1024, //上传的文件大小限制 (0-不做限制)
            'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => './Uploads/Picture/', //保存根路径
            'savePath' => '', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ), //图片上传相关配置（文件上传类配置）
        'FIELD_UPLOAD' => array(
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => 0, //上传的文件大小限制 (0-不做限制)
            'exts'     => 'jpg,png,gif,jpeg,doc,docx,ppt,pptx,pps,xls,xlsx,pot,vsd,rtf,wps,et,dps,pdf,txt,mp4', //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => './Uploads/', //保存根路径
            'savePath' => '', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ),
        'UPLOAD_FIELD_ROOT' => '/Uploads',
        // 前台所用图片上传目录
        'UPLOAD_PICTURE_ROOT' => '/Uploads/Picture',
        /* UPLOAD上传图片路径调用 */
        'UPLOAD_URL' => '/Uploads/',
        //上传大小
        'UPLOAD_SIZE' => 5*1024*1024,
        'UPLOAD_VIDEO_SIZE' => 20*1024*1024,
        /* 图片存放服务器地址 */
        'IMG_SERVER' => 'http://yimall.host5.liuniukeji.com',
        // app默认头像
        'DEFAULT_PHOTO' => '/Public/images/avatr.png',


        /*自动登录需要使用的加密KEY值*/
        'ENCTYPTION_KEY' => 'LNShop!@#$',
        'AUTO_LOGIN_TIME' => 604800, //一周免登录时间

        'TMPL_ACTION_ERROR'     =>  THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
        'TMPL_ACTION_SUCCESS'   =>  THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认成功跳转对应的模板文件

        /* 短信账号 */
        'SMS_SIGN' => '一起背单词',
        'SMS_USERID' => '233',
        'SMS_USERNAME' => 'ln_yqbdc',
        'SMS_PASSWORD' => 'lnkj123',
        'ALIOSS_CONFIG' => [
            'KEY_ID'=>'xR6v3xsAOAFiPiPy',
            'KEY_SECRET'=>'l7dpek8hyfPZX7edNJ3I3cPrPu9baL',
            'END_POINT'=>'oss-cn-beijing.aliyuncs.com',
            'BUCKET'=>'bangbangya'
        ],
        /* 支付宝支付相关配置 */
        'AliPay' => array(
            /*应用ID，在支付宝上获取*/
            'appId'    => '2018110962095545',
            /*签名方式*/
            'signType'    => 'RSA2',
            /*应用密钥，与应用公钥一组，公钥填写到支付宝上*/
            'rsaPrivateKey'    => 'MIIEowIBAAKCAQEAsMjBKFh7GXw5O3gyARjDTXEuYJQUw1Xvs7MnDsmTe7Cuz+RYFx5FFCKdMsxcyqTsu3kc2c+mdyfZx5pIF7z0ApMdVnUvKdb37vp6jlfBqiEvKKRpie7MsPsg1vMRACXpHbF6sryCb5tC68FXvZB16GcYzlSH1U7llxJNot1WTEhE7b8/OMUgu1t9zItqnl4uyHhXf8P41e4xZejaVPxbJlsTfVWgDxM3Y1+iwW8oHOG/khE6j5pq40PrUjzq1yRFr7j1RLuJSI2koWVw6qVTd5eIzHE0ON15RlSMepraJ6Qz26QkKz3m2AMulYwRDZ76tP2LTr6OZh7rwuk9iVcBvQIDAQABAoIBADUsLOz1sBD6TDyW6nNp/1RLtqdV4ujd8Dscp6LK/pE1k170T4qkkCJ+RsoArehFsu6wfv0EeA1e7yIaRZTeQIaFuU1H8eejwO8gZ7xoqNeDI6wVx6i1KtwQrM/6TCK/RF6qAz2/dXwcNwSyIrHhwZUlbVCiHDkMggHuf2t9J3fJxOvEoR3REEi/GvWTdm77HRfXLN7+jLP/qlTfZ58iq8ydSCv50e5xCr4pzPWQYQJDODfmwwOz4VrYB5O2b1OzLWf+hvBb9B6dJYU73VXkv4OytEK5TysUGT8r+TjazT+TSx+phHCbkoQ3k0JKBgJb/a9RzQ5rjP9mkzWbboT/zAECgYEA2rZ3Sg2EO85kHv8+7GKLsaq8cE7bjTVYUbbfan6x36NdfVbYq8ynMcnCFLjoLsAhFC9mjd3tJBlCya6cb4/1XJhiQuGMqo9IqrNKEq/Qd4zRQYBBZ1HGlDQ82+4eVBCzfVr00haSme4IvQsTvYo2HqTUpZ5D0g9JbkNreIKmqmMCgYEAzuxaE3mQFnXoxthVEHzXUaYysvR/9PHhHcEyNayZTS7ssXVsW6H3vys4Af1XTeZBkoOC2DHLjGqBg/rs6NxYdF/pNEWjl1BRY9+LIH4hDG5HNs/AMQBlzLdtZhixo6tMAKVnXwpHhQVGmy3UemrdOQHUKkIHrabJnLdnsKgqTV8CgYAU0+V+PtVF4Ly2GvaCCkxlSe4R/+B2jQrxFSoneRM1SdhgVEHj8mRFoIID+SvbL962jmEEx4qKsoEitaceFKZ3/+bzmYkMwQJZhyNZrjZ6/AT9aNpRnX78pBDbnMx0kvaUzHGeBBpH4FwirIhft2a5+lZpwy2QNnZ2sqLsYfy/IwKBgQC0H6hVlZdpBeDI315FCPeCWtN9VjrgpYGaHigv8vxL5NIjtBzMM1Tvc5bAnKDX7d0cxiArREV1CO2PTunV1qGlRCxD1W8Pc9o1v01jzofEQ2b4fqZFwZvcNbwkiNBxsdZqJZGzeMZDNBF/WcjBe67xRfdDhdEbR7nvEvRPIkQYrwKBgCv4HdLb6/mbn2xdwzKvGoAxbG+VAHzNH1gVbubfo0bYuoG9AWEjWk4KKrHrG0fErYpRkcwdL2oF3GJCq+/F7SfvEd4cwcmD8ucMBBgWe5tq3KpR5Ne3z9TbJr8zmSV7NhCaiM5LNbSk4MQg6ACLM3azWpof7X3DD9DsS9kNP2Bk',
            /*支付宝公钥，在支付宝上获取*/
            'alipayrsaPublicKey'    => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAicQEk3C9NvoGm+nlVdswq7sJSIBp61U7YCBhqbYMrdZ2lZl8Gj3LKuFQywRjEDcEOhbLvp7tiVgh0Sy+d9KggW03DQ6Axxr9Y6T/647L4gJ604aiBFgJoFJTTHhMnSWDL61XAKkJuLM41hh0hzHRa/tjw8y/BJ9IxVf3ZavRxNlssGa6hpJZuBXDHIxfr4WkL7wOV/pBeuoFlP+BCgh1W5kLYHwM4jAKwjLPSIBuKRhDqzv9tcaaofRTFT/vGbSXIWTZxk0oechtWQXJJaZtSA1zzK/qvARpOP5qwHTTrIJ+IVHQDrLtrbmgVO1pcuwAehZYzsI/prr2SY3ZlwSLLwIDAQAB',
            /*支付宝回调地址*/
            'notifyUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Payment/Alipay/alipayNotify',
            /*用于web支付返回地址*/
            'returnUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Payment/Alipay/AlipayReturn',
        ),


    );