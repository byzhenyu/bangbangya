<?php
/**
 * @Description    登录注册控制器
 * @Author         <406752025@qq.com>
 * @Date           2018/10/29
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class LoginController extends CommonController {
    public function __construct(){
        parent::__construct();
        $this->get_global_config();
    }
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