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


        /* 支付宝支付相关配置 */
        'AliPay' => array(
            /*应用ID，在支付宝上获取*/
            'appId'    => '2018110962095545',
            /*签名方式*/
            'signType'    => 'RSA2',
            /*应用密钥，与应用公钥一组，公钥填写到支付宝上*/
            'rsaPrivateKey'    => '2MIIEpAIBAAKCAQEA2py19em3L2u5UI12KeR6S2AVsM9OBDta7a8YgkzblsBro61aBqDhXx+smgMmEgDqk93kAoJN5p7cfT9fquXPEOkgz33yrn9csz9Wv+uBQRrGfME5yXq/cKlm1lFlsDBY+p9bLjVTL9aWit7Fj4RLlNxjKJhzqOgFccg03kyPdMrYdVlzEDgfswHu7CPKcaqPjTWA19TNPCnLk3Js+4yX+NmVITcAFH3KQS1pgtkz1fJ/75jSFETDHyBg3UI+8jRuOQEQAdBN/E+IlzvxJtqe6Zgr6JeMCII8CeexoICaDEGuYVAyiB+CzXRu5m1iCnXVa2/cJMFUS7ulFVzkpTLyKQIDAQABAoIBAGtW9RVY+4RCPYyq2PE66T1nYV8eeu0jA8Oe7MbV3CBKPM/yRzjn96EQWVyJ3UCLyKJwGguNVZi+Of8ZhezQx/pCrcSTz/wUNz0gBYVYqm+AKSOKcMhYB/XWh9MFxotbQeVVJ50pu73KfMKVHaYZTE88Y0fEi03NFxdGOYdJSksKgY2MsqNs3e62xauBSNKRbpuFap9rL23NqpkzuDjRps55GYZvKoddjoK9j0pQ18d0fEWZwLu1Yh2TrWUHwKtkwVZOewumfgxKGRnmf6z7UZ0B/smUu/MdapZVXqk6xZtTPf/PdH1GhAm+5iEzaZRpuFmC4pr0L3akXvJ8nii6pgECgYEA/m4YLY24ApO1hgQWUyA4Os4dr2X4grd0JWQIYbYiI/YZdfu4AYBnuAf8quMr40SlvIWlo0drar6tmlAU14/aCIcG1HHbjdOy3447rLKyx2CP9ZVj+Lfl/Lbl4DA8sYGyHgTEgTZSg3dkn3wyIgdWn4/HJT3kf45eWpEcoGm29UECgYEA2/YJirCXMJe7Ldm/vP5be0BepRH/jxxdTt/pk0Qgx7/UXlEcTojypXYe+tDnknPg2WLin/CLXef4z2Be7eDyEiuB0smQxK/i1zFm+ew8aP2z3JchG0SB0lHq4/I7FkbSKCTgn4h+qkIL1V1pK8JGNcrlGo29JBTN++bIigHdOukCgYAmqsSx55/XhGtJ+sUJzRO+nCSWy4CBHTBhnlpHv05g+L3V2HOWhtU8erpDnlLFL2GgzQ7nSathhakMkq8YM5yHPz4ie0tKuqmsoAkhrebNtjcqs5GF8yzYw1McRzhyIavYNBFJMeBIapS4SD+2PDdF+KvjVQys9g4Q5YzcNDTCAQKBgQC73I3Xsuzd5qlKBrCY1NEDGj37U7zl2H7kz55pVYV9tJvHlhTMCnDa2mCpLOSrE2cVozwVB9ev82BwlUled4Gn/RqbEeYf5myiCCKAD8CklxIXs8flxwPtwmMQHiFS7FevS5fBCU3NUi1TJL2fTwSyCsDThfzlpRMsMrZSEDeHiQKBgQDJlAMwyJJqnWG/Kyap2XR/Gr6/6KIQNOncDDI59MtEqdeHzFi9A0TwhlxRiH1prAIL9WP/9cOKk9IlXlwgGXXJNwFShbzDz4Cqv7K9MPwm5B4DXilxbMQrqXh0Os+e/aYD5ogPbfhqfpfcpSJbEvDaEkDoY8IWFiNGc7ADd7Pxtw==',
            /*支付宝公钥，在支付宝上获取*/
            'alipayrsaPublicKey'    => '1MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAicQEk3C9NvoGm+nlVdswq7sJSIBp61U7YCBhqbYMrdZ2lZl8Gj3LKuFQywRjEDcEOhbLvp7tiVgh0Sy+d9KggW03DQ6Axxr9Y6T/647L4gJ604aiBFgJoFJTTHhMnSWDL61XAKkJuLM41hh0hzHRa/tjw8y/BJ9IxVf3ZavRxNlssGa6hpJZuBXDHIxfr4WkL7wOV/pBeuoFlP+BCgh1W5kLYHwM4jAKwjLPSIBuKRhDqzv9tcaaofRTFT/vGbSXIWTZxk0oechtWQXJJaZtSA1zzK/qvARpOP5qwHTTrIJ+IVHQDrLtrbmgVO1pcuwAehZYzsI/prr2SY3ZlwSLLwIDAQAB',
            /*支付宝回调地址*/
            'notifyUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Payment/Alipay/alipayNotify',
            /*用于web支付返回地址*/
            'returnUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Home/Pay/myWallet',
        ),
        'APP_NAME' => '帮帮鸭',

    );