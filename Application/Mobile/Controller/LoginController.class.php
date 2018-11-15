<?php
/**
 * /**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description    登录控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/28 0031 16:41
 * @CreateBy       PhpStorm
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class LoginController extends CommonController {
    /**
     * 登录页
     **/
    public function login(){
        if(is_login()) {
            $this->redirect('Mobile/User/personalCenter');
        } else{
            $this->display();
        }
    }
    public function weixin_login(){
            $this->display();
    }
    /**
     * 登录
     * @param open_id    微信授权码
     **/
//    public function dologin(){
//        $data = $_GET['code'];
//        print_r($data);
//        echo '2222';
//        die;
//        $UserModel = D('Home/User');
//        $data = I('post.');
//        if($UserModel->validate($UserModel->_login_validate)->create($data)){
//            $userInfo = $UserModel->doLogin($data['open_id']);
//            if( $userInfo['status'] == 1 ){ //登录成功
//                unset($userInfo['data']['password']);
//                if($userInfo['data']['disabled'] == 0){
//                    $this->ajaxReturn(V(3, '您的账号已被停用'));
//                }
//                /* 存入session */
//                session('user_auth',$userInfo['data']);
//                $this->ajaxReturn(V(1, '登录成功',session('user_auth')['user_id']));
//            } else {
//                $this->ajaxReturn(V(2, $userInfo['info']));
//            }
//        }
//        $this->ajaxReturn(V(0, $UserModel->getError()));
//    }
    public function dologin(){
        $appId = 'wxabfa47477f012987';
        $appSecret = '3c1823358a4f46931da2fccb229985c8'; //appsecret,微信公众号基本设置里面找
        $code = $_GET['code']; //接收上面url返回code，5分钟有效期，code直接$_GET['code']接收，vdump($code);die();

        //通过下面url获取access_t和 openid，具体看代码
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appId.'&secret='.$appSecret.'&code='.$code.'&grant_type=authorization_code';
        $data = json_decode($this->curl($url));//调取function.php封装的CURL函数
        p($data);
        die;
        //存取session
        session('openId',$data->openid);
        $arr = array(
            'access_token'=>$data->access_token,
            'openid' =>$data->openid,
            'createtime'=> date('Y-m-d H:i:s',time())
        );

        //添加到数据库
        $wx = M('wxinfo');
        $list = M('wxinfo')->where("openid='".$data->openid."'")->find();
        if(!empty($list)){
            $wx->access_token = $data->access_token;
            $wx->openid = $data->openid;
            $wx->updatetime = date("Y-m-d H:i;s",time());
            $wx->where("openid='".$data->openid."'")->save();
        }else{
            $wx->add($arr);
        }

        header('Location:http://www.xd666.com/Tui');


    }

    public function curl($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
    /**
     * 退出登录
     **/
    public function logout(){
        session('user_auth',null);
        define('UID', null);
        $this->ajaxReturn(V(1, '您退出了登录'));
    }
}