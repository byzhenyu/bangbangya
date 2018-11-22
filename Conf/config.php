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
            'rsaPrivateKey'    => 'MIIEowIBAAKCAQEA2n2INzvggKlIVWYUeVjmztQXyFzC2EeBVl8Aq2mZt+hXHXT+
                                    yitf+7ae+k+tvN0OphN6TbqXlbeFZDRXcCSNolUe7pji5VPuXRBhDIcGw/sYQsTy
                                    kFE6zpWc3yxerFhQSg0PEUlcL+V3udgvgK2b3fOa6dS8794ZLXqsrXahgoIS6x4S
                                    qDFRnOC7+5GbJmmGxA2jXpojl9Vsuv1RBsPSWaDdjHMyR6B43GA8R2UMtJ5RSJPa
                                    +aEc16TZC8vxLbk4m25ARJd7Y50zhL4f1rN4oh2+JzpAt6D4iN4CCIr99CmOive3
                                    Bp542kKvehOMp5Y0yyLyoW2w4l7BhgivQcrpIQIDAQABAoIBADKKh0//kdcxUYIv
                                    sty/K+T8OuQmUQwmvkx0fXspWl2oaT5vKeEqV3GqtOvCMrZVjGXua4Q/fYEsira7
                                    RN60Fe9PMXCP/Sx/VcxBVbHIKlB7bQlE9bdckvXmtfsKEHxdkXUPwESHa8PImQFs
                                    eNCTWFLnXxZoF6yE+94BWqBlqMa/UqV8DpzNrZR20mFcqo6dNaAtcu/gcITk8yD4
                                    Vt1GqFoZhKM3T903NVynTHdgXgDnlfmaABIASZ9PWPfaAzCMQbEQc9HZTHYggI2n
                                    iMmlJA4jko19Y6kljIX2rLyhSwTzFwAgW1Vwr5A/V52q4B7FYZSQHhMLclvmh665
                                    xYJHQGECgYEA7jboQQkGGDV1+xKIGUdeJg/BYwTHy5IAZt409jY0nUMDwx69HjQT
                                    zS3sfLAdAZ4fpSZwYg5hQRb0Wwol2eLc5xIbZtZQsPyiYvunSp3bC5w9nTZHHXMs
                                    Um44EmYNxx1MrCeujoC+aitdst60/MUwOFq1I1AEcdc517wD211MKEMCgYEA6s2h
                                    JeekF3Wpl0x7MFrtlfmMXh3InEoYtCqsH4ymWSUsDWE3T04x+upavMbAlwWmI2Zc
                                    UWeHQtW/WQExdHaBOnsaTFsByXdgj7jpEBo25O34bwGzwpDlwq8yHcfO0N/PlB10
                                    Mb8WJRe+n3GVcmgMAjcfAadwczNAaVzW9bt7VMsCgYAVjBHmEBLlDbDmTP7SiooR
                                    l0s+0afg2gv/Qgo4Wx7XOsCUXattl/hSjtzdSnDVPKBQJ/HybKqPYKwpr/lMhCDH
                                    JfQkkS4rVC68FVdEgJLXsgJAvjAgkdJogl/ACkskv5m32L8JVMvkfpezHkHwu0Vp
                                    U4se7pX4Vid9RBx1MzM4UQKBgQCbQkMndEcduBDe2ZSvgcCOJce6SAlpxtkU8Q6F
                                    ghRQ5/J8GmdfSj5kQuii4O1iLzsPN1UeAs3+1KXFLbPM5btHtzTJfnftBsHT5xo9
                                    KohX0j8u77o0kmlK1VvXM1K+7O8uald1uXvkJRJIDewbDU+7e0VGa7hAhIg340rE
                                    jCbeBwKBgHtjbU7q76bCXWxiIGtQvfAOamHMIQoL7eSd1nos/GheMknMOpgC49ox
                                    lCz7D2MsWj0BU4TLKFuuZht4in5HW8i3IOxw0WZkNZ89pEByFj6cptWxZHNwgrXO
                                    C5z712JnkbeKYEkCM7DHDYPwuqnr/WAB3peW7ZJpWGGoGezSg0oV',
            /*支付宝公钥，在支付宝上获取*/
            'alipayrsaPublicKey'    => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAicQEk3C9NvoGm+nlVdswq7sJSIBp61U7YCBhqbYMrdZ2lZl8Gj3LKuFQywRjEDcEOhbLvp7tiVgh0Sy+d9KggW03DQ6Axxr9Y6T/647L4gJ604aiBFgJoFJTTHhMnSWDL61XAKkJuLM41hh0hzHRa/tjw8y/BJ9IxVf3ZavRxNlssGa6hpJZuBXDHIxfr4WkL7wOV/pBeuoFlP+BCgh1W5kLYHwM4jAKwjLPSIBuKRhDqzv9tcaaofRTFT/vGbSXIWTZxk0oechtWQXJJaZtSA1zzK/qvARpOP5qwHTTrIJ+IVHQDrLtrbmgVO1pcuwAehZYzsI/prr2SY3ZlwSLLwIDAQAB',
            /*支付宝回调地址*/
            'notifyUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Payment/Alipay/alipayNotify',
            /*用于web支付返回地址*/
            'returnUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Payment/AlipayReturn/index',
        ),


    );