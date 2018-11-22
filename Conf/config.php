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
            'rsaPrivateKey'    => 'MIIEpQIBAAKCAQEAtrUAF0kBBA/Cra4OXTp4aJtt1K3vWHoACpnxFshTDc9ODwQDnlXZXTWZ4K6WJSzW9awIbB/bjm4pawVUE1ScF316u3UAjC0zlWe+QerkYsHtGOw91bH8Kb3jG3tz6GeVCqtCopLU9zfQqXFMpGC/sA58yimcGYd56+BLT6CqeXeNSmWJF/4UhM5SCl+sK8aDm0CCkNPOhbcBBb970PfcFKDQy4+/Yf6OXYJe8JlKKaGOHMkKOk9usbCSXLB4TqpAz/Cip4Nn41yI2poxEQZCKv2m13egS180qupGkvoQbPxNurW91PBe6kXl7ZkuYpkPOxBpe/6UG/Zwg9cZRY0+wQIDAQABAoIBACJ1MHczxYVGk6i90ueyR1MzPRuHUkSVfbyCG/33+fH4zvf2AdiyyDPUEBlX0+le84uRzmTV/Up3bEhK0Ph0VJv5hlQkVN4bCEd9MVytODWH7Xdt1pue3bfhoBtmxQ1nu6wvcHnIx3F4jVnnjFTUClLh6JcYEeCetFfQDWQPIReXKsEvC+p0uBaMP7G7xNRQ411iueIMJ7LJcXihONLZlig6Bzh9ZDSbdM0+iDe1UhmMCDGe5U15nvGbE7PnE6ioWRxkUs2IuWEf9ii6tTQoDLZpTAkbft270crrV6bZkOB2XVmCfXpd1DMoxyxP/2FDyht6YRPoUdXXr/rjO2njTyECgYEA57cH9JrlgMX4cJtex/WBxhbQBTkzl5dcLrHo0BkGqtaEVCNk/YidjAe+AQhym8dbZJN0GADekOZluPXTWCQetwDFS9EsQSS8NfY+JsqHsxEvx5exQTR/XJOi9fcV8Uh3t1mN/eADShuEKLJdAxXsfIhmVMAtBwxHUuRJwSyQ4qcCgYEAydsTE7Y/doPsc558h8CBjqlDb32cXg6kTWWDIUc4FtwD+bR/qXNRPSR8geY2t1rbQJ3lnvReN4jOd6mU5DCXw1+LIMgu6Obc0U25XqNXRWXmWChBe+UcKsyb7qMoEAW21qwNl0K8EKUQZXkOhrzI1lEkb5IIxkOuVRP4QMtNCFcCgYEAq/kr7l8H9or32Jt3vPB5YIuN2FKb9+ZehEmGgOwSBrkvfULHsWOzijo0yo96gCN1sS+++hGrHd/hn2TTdpdYNHpd8+dK8Q5SobogZqnqDPy0eW4cIEjWPd9VEzhPEMS4FVxiBgo8ZsQFUi8O2TtsSQDy8fOvd4ulY+AK3VQ2NAECgYEAiEj5YF20C3iWtJlUyvMWhhOSDDxqzrZkCD15g4ZqQETcCkPrzaSmPPnddnbQios1bTMuTVwAIM6lV/WPKWIFlUt0y/VcdrMoc8heV59A1QIvu2WykMvncJ4kycIu0mKJR/dyVaYhA7vujhRl0mKz1r+CMjIO6o3XrmmFO3oH0JECgYEAg1ShMKj3FIY53p1bu3Sohq4njv34vLSZ7eUzrlGs1XnVTBZN5KNDuiyq6/SpuLrAZIFQyh9MzPoN9cdeLa/6NpkBJmN9hyb3KraCGVien6mdF47fwWNSTssWqyYIS8HqKylY0bLhVmQJlLS/kCJFrLmkKVnkpWcQpEpgw7bFCF0=',
            /*支付宝公钥，在支付宝上获取*/
            'alipayrsaPublicKey'    => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAicQEk3C9NvoGm+nlVdswq7sJSIBp61U7YCBhqbYMrdZ2lZl8Gj3LKuFQywRjEDcEOhbLvp7tiVgh0Sy+d9KggW03DQ6Axxr9Y6T/647L4gJ604aiBFgJoFJTTHhMnSWDL61XAKkJuLM41hh0hzHRa/tjw8y/BJ9IxVf3ZavRxNlssGa6hpJZuBXDHIxfr4WkL7wOV/pBeuoFlP+BCgh1W5kLYHwM4jAKwjLPSIBuKRhDqzv9tcaaofRTFT/vGbSXIWTZxk0oechtWQXJJaZtSA1zzK/qvARpOP5qwHTTrIJ+IVHQDrLtrbmgVO1pcuwAehZYzsI/prr2SY3ZlwSLLwIDAQAB',
            /*支付宝回调地址*/
            'notifyUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Payment/Alipay/alipayNotify',
            /*用于web支付返回地址*/
            'returnUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Home/Pay/myWallet',
        ),
        'APP_NAME' => '帮帮鸭',

    );