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
            $this->redirect('Mobile/Index/index');
        }
        else{
            $this->display();
        }
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
            if(is_array($userInfo)){

                $this->ajaxReturn(V(1, '登录成功,正在跳转！',$userInfo));
            }
        }
        $this->ajaxReturn(V(0, $UserModel->getError()));
    }
    /**
     * 注册页
     **/
    public function register(){
        if(is_login()) {
            $this->redirect('Mobile/Index/index');
        }
        else{
            $this->display();
        }
    }
    /**
     * 退出登录
     **/
    public function logout(){
        session('user_auth',null);
        $this->redirect('Mobile/Index/Index');
    }
}