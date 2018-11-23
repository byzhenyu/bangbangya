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
            'appId'    => '2018110962095545',
            /*签名方式*/
            'signType'    => 'RSA2',
            /*应用密钥，与应用公钥一组，公钥填写到支付宝上*/
            'rsaPrivateKey'    => 'MIIEowIBAAKCAQEA0i8SEOUmH+NhG1L5lQNlJUnPlzGNY2ZizfUvnfyVjDLCtKboIZL1EtZ/xyHEMaXV+X+69rMAgzDS9JQJ1SYBlzn/6FWmGb38Bbr0zbSfBsZ14RY2C/O/hJ/YlEhGLQ5DWRbDF4dFezbEQEbRhRmnYxP/Rkz6JRKrDDcee1ilA9bu1GpcVP+7fHE/PgBQ5b7FahF2u3HMIxMxWnxlDWT2FqfARAi2oQwQNH3rPsRaNzZlEYYjToucrJmAbtY1q/NZovA69wKraNNdNKp3W/mhBj30qrV4izFfIGjAq8+jCyzcspedJ5oF5ba2YhKiQggsTZ7M3nPCO0nV7B5gnFleWQIDAQABAoIBAApctbhNg86oqB3vvkxBaZgoBmr+z2iqII7k2p8gV8McsYq0e9dD5n8yQgRwPrrv4Re222HUO188ZfoW3lE62XM6McqjhlfYjPc/W1hU4k40NJWPQcTfFH8WXlgZJBSOknVu8NlVGvDTJ2O/2g6E0ELLNpehredr/zDP+tm76g7BUA9Gp7NdHgBq7TI6prJmm43ag0/0EWybWam/e7VRj8+IWQlrZPypxnOdMY6uew0ENlnyr/CIG77TiQ6/aE+vYK2R6FVKQlFZ5Mhao5UFUcCGh7yC5L5UoQaNuK/oXg5tiruHRUAzFxle2D3nrdez6tDywz/S6Bii3oCdWVCaTM0CgYEA6tEAi4BnQ01C2IiHcvE4MTbYiX+PnZSkUsnN3dHhK3Zz372s6Cz87EXKWpEzZgtGRUrr6k6zntn+uWE3stn1xDBCk76yvMMxHQuwk4OvLkVcafWx9XKj2GgopGGsv0fbjSHGAzxOM7E438vGy/EzHS/E9tn32TZ4tW1en1exGx8CgYEA5SUwX8OnfqOUUyBKIDB7A7kKZ3Pe50XfwRU53KkmruMP8IxYmt5UJIkTBtLNbemZfnIjOXUamqD7m9leP7P8ch1McVOFcfdOQO7JPxGBIvqKNPlrrrw3sHXz8vWrkYkxXg9GAt1nSoXJ2lSys2xYTlrQZEXCWqyiSiIPEnw6z4cCgYAH2pW6FMFfbuYdd/OP1KGcg8kaUDnte1tDqZxJHwv4+C1H/oAaA+cxv+PUw6aEczwWdTsOyzMWzRPH/4htYvzlYvfXGTnBNoYeApYVWQIy+f7tTFp3ay4vvswdM2cjvrOJNOf6k7nJ4NOWKKqth5O+FirwVroDed7vsojHq5cw4QKBgAGQGl9c10Se2wEiJD9J9VzWI0MHYErViCwp9+YQZUomFFLAsk4EHDDPmoieYXZRdJGPN7xJly/czzqF5gWrNZqVppLTTgKz/B2nQ0oh1W6mpNCtrtVXRU33z/0lPdFVTTfmBMU6O10fwnuxvjJJF+UY63jznNq/eK/mwZGPtVr7AoGBAMi18Ch5H6RUHQJAAiFQBlOF2esyqE90LWczVuXd2LccbRxh7CF4vrpW+l55NcYl5CkS+3uhT3OdIPRsZ+6LopICMd+5Na2p7lIhC/nYMSAbRC8q27dPiyVLGEUFJyyEFSJsmjMcSpYlP14PxecsxhZ11kbq56JsV4QQlk3pj+vj',
            /*支付宝公钥，在支付宝上获取*/
            'alipayrsaPublicKey'    => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0i8SEOUmH+NhG1L5lQNlJUnPlzGNY2ZizfUvnfyVjDLCtKboIZL1EtZ/xyHEMaXV+X+69rMAgzDS9JQJ1SYBlzn/6FWmGb38Bbr0zbSfBsZ14RY2C/O/hJ/YlEhGLQ5DWRbDF4dFezbEQEbRhRmnYxP/Rkz6JRKrDDcee1ilA9bu1GpcVP+7fHE/PgBQ5b7FahF2u3HMIxMxWnxlDWT2FqfARAi2oQwQNH3rPsRaNzZlEYYjToucrJmAbtY1q/NZovA69wKraNNdNKp3W/mhBj30qrV4izFfIGjAq8+jCyzcspedJ5oF5ba2YhKiQggsTZ7M3nPCO0nV7B5gnFleWQIDAQAB',
            /*支付宝回调地址*/
            'notifyUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Payment/Alipay/alipayNotify',
            /*用于web支付返回地址*/
            'returnUrl'    => 'http://bby.host5.liuniukeji.net/index.php/Home/Pay/myWallet',
        ),
        'APP_NAME' => '帮帮鸭',

    );