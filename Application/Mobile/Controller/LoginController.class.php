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
        $this->display();
//        if(is_login()) {
//            $this->redirect('Mobile/Index/index');
//        }
//        else{
//            $this->display();
//        }
    }

    /**
     * 登录
     * @param open_id    微信授权码
     **/
    public function dologin(){
        $UserModel = D('Home/User');
        $data = I('post.');
        if($UserModel->validate($UserModel->_login_validate)->create($data)){
            $userInfo = $UserModel->doLogin($data['open_id']);
            if( $userInfo['status'] == 1 ){ //登录成功
                unset($userInfo['data']['password']);
                /* 存入session */
                session('user_auth',$userInfo['data']);
                $this->ajaxReturn(V(1, '登录成功',session('user_auth')['user_id']));
            } else {
                $this->ajaxReturn(V(2, $userInfo['info']));
            }
        }
        $this->ajaxReturn(V(0, $UserModel->getError()));
    }
    /**
     * 退出登录
     **/
    public function logout(){
        session('user_auth',null);
        $this->redirect('Mobile/Index/Index');
    }
}