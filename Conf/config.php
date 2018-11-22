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
            'rsaPrivateKey'    => 'MIIEpAIBAAKCAQEApyPD34P4YQbGjSZ7nx3qIQD4uBOADwAWf/Oc5UqYPbQU+UJt
                                    01Z3qSh6RYVmUy1Khjh87blm0jGX/TSWGQ+qHvve/Xhm9H2+L0q2hWq8hl0Zb9LF
                                    Xyanjti4s8kDrZW9gD96Ch6WRaPbjMYiA/mU/cZ99gn/hMC4V8q1j4Z+FrFgpnt+
                                    Ncq9MGyXU/Q9ylPetoD80RlDL6VXW78pVEF27P3YG+6jnn3VI+A4+cYk6Xz03iG9
                                    WqGMCAllQ8/xJ2qiwXOANCh4HJLfm9mfPTGYPyReSiUDChQ/O/e5Z24b5LsxSyxl
                                    ETwPx3ChATo6R8UA9D54nUWsJL3vX7FV6zd9sQIDAQABAoIBAQCBNiQSIuscz2kl
                                    6+88Bpte4mplnCWu8wlx53qOxIlHgnWIcXOII1ukuXc1UbjWargnZXonKeK0csSm
                                    akXzn+mLxHoSiEdHkgI6e075e5e9ZDHrAg21zqNQyniX/LnMM2vpvqjxM85GjjOH
                                    9BCN9B/bxNzSHc6c6YRZ0otkJrxJe517iqySLJ+a83aWY0Yg9GhrlaWBNLgLihjw
                                    w/Yvgm9Bn2AtTEy+vCfO+BXJuWuBLHnHUQ8PcIwB2+/l6w9qaX1Z5xOJDp/8ERj4
                                    bXVeWwhd2zNnCl5SL9Rcfn1+DjtOEq+xmUAvRZ2SXag9UCsaRxDSSeTC72GkxkZ7
                                    Xzc1MQIBAoGBANFN3Y6nQYzhcJH/ILodNgb/OM4/vRzQj/7YaZNBaKpa3SE8kWlb
                                    4vBXewUXjTPC0LmW42dCPHjyH/Y+uKFc8B2gdHX+RIlqhJml7ruS7nt3+tUrSFQW
                                    0avJACXMlQg50k64ikfG5kPIlD0MXQxTb3uKhZBCyq54rugzkPpZiMwhAoGBAMxt
                                    u1ZhyY6yawnGBQtZBnOLVRdi5fqVIzK2t3q4bxxlBfiM6QNUvr7i+1uerj3VmcoB
                                    dxZhzclWhGd6DvkOqVm+W8GqtIbfSG5IypZ6v6kE2Vdjw133W2xAvjegRBVJu/37
                                    lxr/sjvIo18i40qSO6+JTwvUesFr+CxESQzMh/+RAoGBAMmkfIjMdghlwG/HHR5w
                                    ufYvt+hr26OBAtkAYWXupAlLFLLKiUS5s37cqLYVJIHzc+b4iQiX9W6HxWcUsgcz
                                    ZFkGlmx+lETXC/yCseaf/YWR52OrGTl56nHXaX8BA6szGXuhqgbQhlJTUQ0ndhVD
                                    5KleoKKE1oMT3V3zH/8wduxBAoGASVmaHV2K75uPEnuttaukx/KXfoOq1yWJ8pli
                                    7jKdE4ggA1W0CdDvfBh/bhlala89tiCedTZ0myhi66n9E1oyY5QM4kl46ufU2lVd
                                    825ptKCbx2JxaBboA8ibN+RWaXkCbhhG2JYkgpT0IZ+oBErCBbLz3N/Jh/tQmbmK
                                    qH/+0DECgYBFfBYcidalpJ72vGwMTFj/69Y8mzY2aMZh+9E1k0DSS4h6uTGiK3ix
                                    4pTSXcjHvf9IDB9tRfdV3cHqnJL04RmdhydSCs1ZZ9UMpJem/Z+BFr1zHbKcDHHK
                                    tgC8emvS2zBXB5fqJ1yx6ktjSGVijwRn2UZ5qH3YXQPeGE8Gn8bbvA==',
            /*支付宝公钥，在支付宝上获取*/
            'alipayrsaPublicKey'    => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAicQEk3C9NvoGm+nlVdswq7sJSIBp61U7YCBhqbYMrdZ2lZl8Gj3LKuFQywRjEDcEOhbLvp7tiVgh0Sy+d9KggW03DQ6Axxr9Y6T/647L4gJ604aiBFgJoFJTTHhMnSWDL61XAKkJuLM41hh0hzHRa/tjw8y/BJ9IxVf3ZavRxNlssGa6hpJZuBXDHIxfr4WkL7wOV/pBeuoFlP+BCgh1W5kLYHwM4jAKwjLPSIBuKRhDqzv9tcaaofRTFT/vGbSXIWTZxk0oechtWQXJJaZtSA1zzK/qvARpOP5qwHTTrIJ+IVHQDrLtrbmgVO1pcuwAehZYzsI/prr2SY3ZlwSLLwIDAQAB',
            /*支付宝回调地址*/
            'notifyUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Payment/Alipay/alipayNotify',
            /*用于web支付返回地址*/
            'returnUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Payment/Alipay/AlipayReturn',
        ),


    );