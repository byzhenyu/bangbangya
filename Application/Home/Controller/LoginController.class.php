<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/20 0020 13:19
 * @CreateBy       PhpStorm
 */

namespace Home\Controller;
use Common\Controller\CommonController;
class LoginController extends CommonController{
    public function dologin()
    {
        /*测试打开*/
//        $userModel = D('Home/User');
//        $userInfo = $userModel->doLogin('olI8S1dXlD9JEWiJOdZff1ICYsC0');
//        session('user_auth', $userInfo['data']);
//        define(UID, session('user_auth')['user_id']);
//        $this->redirect('Home/User/personalCenter/login/1');
//        $this->ajaxReturn(V(1, '登录成功',$userInfo));
        $code = $_GET['code'];
        if (empty($code)) {
            $this->redirect('Index/index');
        }
        $weiChat_token = $this->getWeiChat($code);
        $weiChatData = $this->getWeiChatInfo($weiChat_token['access_token'], $weiChat_token['openid']);
        $userModel = D('Home/User');
        $userInfo = $userModel->doLogin($weiChatData['openid']);
        if ($userInfo['status'] == 1) { //登录成功
            if ($userInfo['data']['disabled'] == 0) {
                $this->redirect('Home/index/index/login/2');
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
                'register_time' => NOW_TIME
            );
            $userid = $userModel->add($userData);
            /*生成邀请码*/
            $invitation_code = $userModel->createCode($userid);
            $userModel->where('user_id = ' . $userid)->setField('invitation_code', $invitation_code);
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
                define(UID, session('user_auth')['user_id']);
            }
            $this->redirect('Home/index/index/login/1');
        }
        $this->redirect('Home/index/index/login/1');
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