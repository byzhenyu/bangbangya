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
        /*阿里云OSS配置*/
        'ALIOSS_CONFIG' => [
            'KEY_ID'=>'xR6v3xsAOAFiPiPy',
            'KEY_SECRET'=>'l7dpek8hyfPZX7edNJ3I3cPrPu9baL',
            'END_POINT'=>'oss-cn-beijing.aliyuncs.com',
            'BUCKET'=>'bangbangya'
        ],
        /* 支付宝支付相关配置 */
        'AliPay' => array(
            /*应用ID，在支付宝上获取*/
            'appId'    => '2018112262292235',
            /*签名方式*/
            'signType'    => 'RSA2',
            /*应用密钥，与应用公钥一组，公钥填写到支付宝上*/
            'rsaPrivateKey'    => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC6kNM+1XoEzDn2fYB1/dYVbh/tCJVw6rSfAbSX0HrpU7akhwH6T+H4y0Gu9/UKOdzlqPi9BJFhNsmWVNk0mXsIBk40KGd+fUvrJDeohhERTNneCjJzmtQjxbWnCSuv+Bw+OCNFZzoL8HbqrJNX6gggE6veVyY9JSTI6fm4mA2Llyi6Oq+papvr2R6CtmwTiCB8W7bIy03eWgyLC5eZ/nj35WDvvPMBVAq3nSsof0cr/MEDc4USjMy0dZtsjJXbRBnXVNXMtzLGCHfsIcSQxrruwKbrveCk2U1unPYGEtmLemphG6Rgv9t6ChARTo8ts2lQLMiF4DZj+lMP4FO7w3AhAgMBAAECggEAGqJKRhIb1UuCitnn2PBf+7c4/5HWI8Sd/IU1zuaIZX75+Ar+EUoOLIJ34g6sAMW5719sAy//wS0dIkUCrSusEL01tOR/weMJT4xgFcLt9NZP1A8InNBhsKmtASeiAQEepxNElcfi1YmCsIXqkwZqGoo0Yw11/Aytb7FXYS7z4BV553u8TeugxTskQHnsuUxtlUr85xRmZS5ktqd84C+gXlb7FFVm66R/Vl3UAQtkmIXW1pjqS6ie1WxIIM6cXaIgqVDLSgJ/UOWRTrn5B/jl0KZ0mpfQeaxFQJa+MTm6Uokf9CEpdI788T+qCl2MYClc1Y+rtBBWat+gc789S0B48QKBgQDdd2RWZRn3NgUMjQqr44znEiZrwVaE1ZTxolcz3z8G+ccpbJ+jYswUCumPGyWMKekoK5aVYiacg+iKVORYt3om+tnRAv4Lj75dNGq+GabkO4e5szGLTDTaoGaVSHh8oUIidGX3M+iAM1ApmIhsZ7TpGqFGo+i7KqbU6EaGjLDpewKBgQDXqEBM9pvCyPv6cE2XmnF1kH/geqt4G8raolLiQsdPdmty2hzkxNqgBcLCD8ZeMrbpYqaEFeM4O6EpsENLlI/g0UbnmRRJmHDJ78Wyrxv1ha80xYGiB2lEKC3fz6oGxZHZ5QSLowiROleGihP894bYrEhhfkIOrDDs7wY8iMWUEwKBgQC2SmfUlTNiQJy3UADQq2R3boEhgOpuPD91BWMx1zhRzZcZicd8/P1uIe1NVdL6JCU+Y2J3Y2rUud7WvjmWrK9nFmPjnsrtSUwv+81A3Gx6wGVffRfyyK9YpV7K0fQa4cFGCvlDGP7vR01cb6kbUeGfXcQSGSifpuPbq7UhWvzjsQKBgCMRcAtT2rZM+nSaCHQLoEGO0syNBg8M7eqplLLISyLHzecqQqKMxERbS3VFHWu58XZ4HT3DVWSShb2CtR64NMFJJq+Ie/0Ptd+QhuRshy5go8IAD/lqHRsBMZyAZ1SckgANtrSCOWVsSElPcLqlAZ+APHDmGBXMuFP+9lOnpgFTAoGADvsji14PW0JFxRWC/J5sbodJiYSeCTvHbPN875ezoh36OyOBUixa7He58WksIT6wKkRJ9CjBInW5FJCw+/JMh4PRQQd73wl8VYKksgv9lPLsA+OL3YJHFR5New4rIT7xrRAjtcxXddHj8Ke5vdw8K6iWjhuRxAPeYNyrrP23d6Y=',
            /*支付宝公钥，在支付宝上获取*/
            'alipayrsaPublicKey'    => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAicQEk3C9NvoGm+nlVdswq7sJSIBp61U7YCBhqbYMrdZ2lZl8Gj3LKuFQywRjEDcEOhbLvp7tiVgh0Sy+d9KggW03DQ6Axxr9Y6T/647L4gJ604aiBFgJoFJTTHhMnSWDL61XAKkJuLM41hh0hzHRa/tjw8y/BJ9IxVf3ZavRxNlssGa6hpJZuBXDHIxfr4WkL7wOV/pBeuoFlP+BCgh1W5kLYHwM4jAKwjLPSIBuKRhDqzv9tcaaofRTFT/vGbSXIWTZxk0oechtWQXJJaZtSA1zzK/qvARpOP5qwHTTrIJ+IVHQDrLtrbmgVO1pcuwAehZYzsI/prr2SY3ZlwSLLwIDAQAB',
            /*支付宝回调地址*/
            'notifyUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Payment/Alipay/alipayNotify',
            /*用于web支付返回地址*/
            'returnUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Home/Pay/myWallet',
        ),
        'APP_NAME' => '帮帮鸭',
        //极光推送 key secret
        'USER_PUSH_APIKEY'=>'6acce159b66e2c31030ae9a6',
        'USER_PUSH_SECRETKEY'=>'25480972b24efa1559349ebf'

    );