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
namespace Pack\Controller;
use Common\Controller\CommonController;
class LoginController extends CommonController {
    /**
     * 登录页
     **/
    public function login(){
        if(is_login()) {
            $this->redirect('User/personalCenter');
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
        $userModel = D('Home/User');
        $userInfo = $userModel->doLogin('123456');
        session('user_auth', $userInfo['data']);
        define(UID, session('user_auth')['user_id']);
        $this->redirect('Pack/User/Invitation/user_id/'.UID);
        $this->ajaxReturn(V(1, '登录成功',$userInfo));
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
                'register_time' => NOW_TIME
            );
            $userid = $userModel->add($userData);
            /*生成邀请码*/
            $invitation_code = $userModel->createCode($userid);
            $userModel->where('user_id = '.$userid)->setField('invitation_code',$invitation_code);
            if ($userid) {
                $shopDate = array(
                    'user_id' => $userid,
                    'shop_name' => $weiChatData['nickname'] . '的店铺',
                    'shop_img' => $weiChatData['headimgurl']
                );
                D('Home/Shop')->add($shopDate);
                $userInfo = $userModel->doLogin($weiChatData['openid']);
                session('user_auth', $userInfo['data']);
                define(UID, session('user_auth')['user_id']);
            }
            $this->redirect('User/Invitation');
        }
        $this->redirect('User/personalCenter/login/1');
    }
    /**
     * 退出登录
     **/
    public function logout(){
        session('user_auth',null);
        define('UID', null);
        $this->ajaxReturn(V(1, '您退出了登录'));
    }

    //微信登陆
    public function thirdLogin() {
        $open_id = I('open_id', '');
        $nick_name = I('nick_name', '');
        $head_pic = I('head_pic', '');

        if (!$open_id){
            $this->ajaxReturn(V(0, '参数有误'));
        }

        $where['open_id'] = $open_id;
        $where['status'] = array('eq', 1);

        $userModel = D('Home/User');
        $select = 'u.user_id,u.head_pic,u.nick_name,u.invitation_code,u.open_id,s.shop_accounts,s.top_time,s.shop_type,s.partner_time,s.take_task,s.task_count,s.task_num,s.vol,s.appeal_num,s.be_appeal_num,s.complain_num,s.be_complain_num';
        $user = $userModel->getUserInfo(array('open_id'=>$open_id), $select);

        unset($where);
        if (empty($user['user_id'])) {
            $map['nick_name'] = $nick_name;
            $map['head_pic'] = $head_pic;
            $map['open_id'] = $open_id;
            $map['register_time'] = time();

            $userid = $userModel->add($map);
            if ($userid) {
                $invitation_code = $userModel->createCode($userid);
                $userModel->where(array('user_id'=>$userid))->setField('invitation_code',$invitation_code);

                    $shopDate = array(
                        'user_id' => $userid,
                        'shop_name' => $nick_name . '的店铺',
                        'shop_img' => $head_pic,
                        'add_time' => NOW_TIME
                    );
                    D('Home/Shop')->add($shopDate);
                    $userInfo = $userModel->doLogin($open_id);
                    session('user_auth', $userInfo['data']);
                    define(UID, session('user_auth')['user_id']);


                $this->ajaxReturn(V(1, '登录成功', $userInfo['data']['user_id']));
            } else {
                $this->ajaxReturn(V(0, '登录失败'));
            }

        } else {
            session('user_auth', $user);
            define(UID, session('user_auth')['user_id']);

            $this->ajaxReturn(V(1, '登录成功', $user['user_id']));
        }

    }
}