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
//        'DB_TYPE'               =>  'mysqli',     // 数据库类型
//        'DB_HOST'               =>  '127.0.0.1', // 服务器地址
//        'DB_NAME'               =>  'ln_bangbangya',          // 数据库名
//        'DB_USER'               =>  'root',      // 用户名
//        'DB_PWD'                =>  'root',          // 密码
//        'DB_PORT'               =>  '3306',        // 端口
//        'DB_PREFIX'             =>  'ln_',    // 数据库表前缀
//        'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
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
            'appId'    => '2018071260608046',
            /*签名方式*/
            'signType'    => 'RSA2',
            /*应用密钥，与应用公钥一组，公钥填写到支付宝上*/
            'rsaPrivateKey'    => 'MIIEowIBAAKCAQEAwFLJE177vhgpb4Oi2hJRQr6CXWZq/KjT52PBijASI6BaSWkPrY112FPuSqU66Wjt5S6Isgzw5tMpltFh/VIsl/BElxy0XatZwnp/qDlAicuDK/A2jqEYb/AOO7e7hUNdo+9hvzSEEivd3oMk/EeLOQPF8rOMH9/11Yqr0jhXd0CFyuOkU7wUaizHlw60MNB6A6FuxfheZCsXfnOq5bzaLbQIRQdPdRvtmudHYhEfferS8Nzw1LUDN6rWJaSrqYLV5gBMlXT94V21tQsTfmifNT6av6k0Gm4B7+rB5SSQfPk/RLC/y59lRJLtMwnZeZbfLRV6EltNS+gjN4CuqOmvrwIDAQABAoIBAAF84ndW5cWrtC5bq1faimxkwudsEmnP+Iuytt2Vz54c8HXe7TMfhAjfZVmIe7Mwj56VRJN0DC0M9yZmikaGazflsPImU7Lhz3j0r6Yig0BNpb8iRZnG30SGa+XEXxwB0GijQnNTOa5WTooZI6EcAJS5Vb+x6eSZO/Kh9wBSVULj5nYT+/TI8hwZDahZYZ634SfP4KezeUGcpP1pmHg3n/rRCG43RURXS+rS3V8FVNeYY0SFwxw/ZeP5g8vm9jUDl3597Qdt7x8pAECwVMTOea3B3mhQxUcnjfyLnFeAGw5fIbh+vQXQO742znerk5H1WPOgKMttzlTakGrvEAyUGvECgYEA4VcMDQp2rq5Hhp5XbNmAolgznLw6QnThGDwUhn+fGAqOwxwMxobQVdrEiZGOmt9uH1wn6efd3ySf76C2zFC3/dQOv13Wi21+IA0je2suMETGr2U7DzSADxskm6bmSr1HQHfn18T0AEoSpmdQm4gnhX0sfDyZuXQlc9bQLX8EXGcCgYEA2n23QhHHN8NjGuYDifEQSO4QLCDniPTktQFx4ljwuFIZBu2MN1QBvhCHoRdZsBSGXUhyj8GqId3R9XTkun/GCwaffWKOhBPf4Y1afL1ESXT9I066FsxDNe+9Hhuul05+81/+Kuc8B1chgqRRGtUzTxjhNJNOkwE3sSVett4UBXkCgYEAwHO7kxewrRejdtGdERXhysVRrzcC+ucfp7cIQKaWlc3Oh8xZDOTu4aRp3qQ2Cwdv4dCvNhIVaBK7lcjI1k7KFTsbLU1TgtkMswsFls6/L/JNTldCzYWlKUGhTl6IqKRnSiQeGiXXzrd0njH2oh7ywgtaBvEqyTztD7YTLgghDVkCgYB5Rb4qFeQVt4WSsIjWT89SK5EebUUWZ5dqQFsN33/9TtRFXCSvsRkmfdZKO8O0UDAkJsovsLbWBWE8dJ1gRvuNsydjWQjxlnZ1oxfOlN8KRr0ak+AGN2o2zdKSm5rUsOUWAPn6pk5TZLFc6gd6dUv9YzndDMZYIhzfXnar2agTQQKBgD902db0lZpTOZBYtR+7ugPvIv+nwgIgbm3+IFJETVn6UfdE/3IskHIHoBgMcbIQ01cSQjA49x9frbs+Xdrmg7lu3RyPQUZtrspE420/iw17931UiDSDKs1GguNLDuobKSToyOnKTRtpMz9KrIQBfjEf4i+Fbg9+44Oeu1qqbvCG',
            /*支付宝公钥，在支付宝上获取*/
            'alipayrsaPublicKey'    => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjoMejr7d55Bw9nQHqxtVSgC/3XT8HGJofNvfChDwj+7kRs6QPelfsY1G+DZwh3lnbCWNnrChAO57bVCQCpEQavjbW0T91MfYGiSkisKXketr9o66WjBSgJLpL5AsLP/tXapWmfEqKR2KSmaHWQISjuAWA2y0i6oQ3wNlyBQ5oxh0l/nF4RRKoWvzPdvph2D4laiwSTY8efdMGY5P6os14ntUHFYql601R8Br5yau8McOpKw77J5LnT1qPPoSREZtWBLhweb1KPNRjvW5AzCxVo7sqlDYbfMGsxEoofYiXOwx3PWsGrmrSSN9QESBeMam4SZFaWQJ+6o7LNEEA1JRVwIDAQAB',
            /*支付宝回调地址*/
            'notifyUrl'    => 'http://exam.host5.liuniukeji.com/index.php/Payment/Alipay/alipayNotify',
            /*用于web支付返回地址*/
            'returnUrl'    => 'http://exam.host5.liuniukeji.com/index.php/Payment/AlipayReturn/index',
        ),


    );