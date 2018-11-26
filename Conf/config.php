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
            'rsaPrivateKey'    => 'MIIEowIBAAKCAQEAzb2z8aHo/abmJAX2M0+uYnWJBt/CnpNUgh68POMDIDKdjEPzRydYWCeyGf8ghShXJihFJCfhmF8r5jfKtUPfoSi7VUK232++V47rN0Yq+gxfbBFtiahL264N0yenEAcm5J+8H9/CZJslhbH/1oBPeLESzCAQTFmR/Bbx3EzHGJfegXIIxgncwUo59oAEHBdFEUJ6/g3R6HQD9PPiZwk9BQsM6mMI9qLGRpVp9hFA5pqR9myBg/kcWV99g0f3ptdLosNZZKFC1kD2GiFvEwA7A17unoyp87OhnaBL/kGk2CHDVDkixCzs/Nto29crZsnY6mxqrTgvJNPhmbbCU1mE9wIDAQABAoIBAE+oKSlexLSgj+5WmJIZQa1BUIJm1BVNeaS/faYdRrKKplEOZfygkSXgiKZkDxU/Hy7Y3Jw5DLFP99E0vLkdxFHDUXSbVe6AdzOzVAKcmjsh2zgeL0ji7ivp4dRlP9uGyVovNkko+zy0jPyMhD9qglp6RIrfxy+oZksyHwZqe5NbVtmA2zk9mPD/aDvkxxr2plNoaF3PImDbzhQn2NUGFV6aaIRtAN1PxsTy8745gUQfSmPghXPJSKuEFZfdDpLDnr0Mkg5TrNLQRm9bKaY2rb9SbnKF0ZqrgQ8MLD0yYGYaIZf3rKWiFrqF895cGjvnQ91AmZoofvtcYYJ9k1H/A2ECgYEA/iLYC5CXvQqn6AcsBmEcFRWjKmCGZlkelcJKSSF+kRD3jjku6dlQ8ByqmHJnXDheWR6dVxsyKwN26qpX1IlXeA9ALe/ORs86xC6y7lA4+4EzLcpjXoxhEdIQTWRCB3/IxjeGSYFTKfZfdcug1eBbHQs+zWmonRbhQ02nvA/koI8CgYEAzz/+h4dmrli93veHx0CFxy1VdxfF7hzM/yNJmfLsVHQ/+fO3L4lOfHbwpJX+M+IwNfxxi77ZgIL1anizswHfnfnxjZ0pt/tZo6CN/at4IUgYr+4WaBJhrYg8XQV7MIouqHa9qG/hfIRJuN3Ou8mw+yUr4/sKJCVHs2UHPNRaORkCgYAVrTcsVVr7/uqved0hdisFCWFvfeg41qUrNPVNI5Q+H2peMTZ1L9odgPR0KF30mcJCa8AT6/ftf1AnV/xB3q0dSn+461l25Loofas5dJJHsY+B+l9bczHcaUSGtWSEazve3GGR53wGekNOMt43H/nlkk37C2YccBUqvnbuLZTctQKBgQCM8f6N+T2RsHRgYIMMtQrF7ETFW3uWdivMEBmMAzNKNE0WFfMs9vpTlygNJpzceCVFccteuIp+5ZE1uaSsRsfnTgZCwvGOV/gcWaJ3M9fc+oUfkJHYcTfQpmg28vVWi4Mzup09Iel9nxHsEZz5BS/BfQQeZan97wSWMkz7x4cccQKBgDqXBFm+VsitkVmbLDFC92Y0wb1CxpfUqf8ioHyd6uyVNTuTwkdTtnz06vKIdRhPcuC8Y8xCfM6K/eB0riTpq4JwkVwrceKNS0TJLCOYS/VeMWT/G5i7eoMj5G8MEssMBofhMtDmCHWP5YTtPJhNNIW6TY0haZbIID3nisUZQwHf',
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