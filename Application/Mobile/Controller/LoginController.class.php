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
    public function dologin()
    {
          /*测试打开*/
//        $userModel = D('Home/User');
//        $userInfo = $userModel->doLogin('123456');
//        session('user_auth', $userInfo['data']);
//        define('UID', session('user_auth')['user_id']);
////        $this->redirect('Mobile/User/Invitation/user_id/'.UID);
//        $this->ajaxReturn(V(1, '登录成功',UID));
        $code = $_GET['code'];
        $weiChat_token = $this->getWeiChat($code);
        $weiChatData = $this->getWeiChatInfo($weiChat_token['access_token'], $weiChat_token['openid']);
        $userModel = D('Home/User');
        $userInfo = $userModel->doLogin($weiChatData['openid']);
        if ($userInfo['status'] == 1) { //登录成功
            if ($userInfo['data']['disabled'] == 0) {
                V(3, '您的账号已被停用');
            }
            /* 存入session */
            session('user_auth', $userInfo['data']);
            define('UID', session('user_auth')['user_id']);
        } else {
            $userData = array(
                'head_pic' => $weiChatData['headimgurl'],
                'nick_name' => $weiChatData['nickname'],
                'head_pic' => $weiChatData['headimgurl'],
                'open_id' => $weiChatData['openid'],
                'open_id' => $weiChatData['openid'],
                'register_time' => NOW_TIME
            );
            $userid = $userModel->add($userData);
            if ($userid) {
                $shopDate = array(
                    'user_id' => $userid,
                    'shop_name' => $weiChatData['nickname'] . '的店铺',
                    'shop_img' => $weiChatData['headimgurl'],
                    'add_time' => NOW_TIME
                );
                D('Home/Shop')->add($shopDate);
                $userInfo = $userModel->doLogin($weiChatData['openid']);
                session('user_auth', $userInfo['data']);
                define('UID', session('user_auth')['user_id']);
            }
            $this->redirect('Mobile/User/Invitation');
        }
        $this->redirect('Mobile/User/personalCenter/login/1');
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