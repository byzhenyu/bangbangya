<?php
/**
     * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
     * @Description      商家后台登录类
     * @Author     	     gejun mail@gejun.net
     * @Copyright        Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
     * @Date             20170905
     * @CreateBy         
     * @Modified By     
     */

namespace Agent\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->get_global_config();
    }

    public function index(){
        $agent_auth = session('agent_auth');
        $agent_id = $agent_auth['agent_id'];
        if($agent_id) {
            redirect(U('/Agent/Index'));
        }
        $this->display();
    }

    public function dologin(){
        
        $user = D('Common/Agent');
        if (! $user->create(I('post.'), 4)){
            $this->ajaxReturn(V(0, $user->getError()));
        }
    
        $mobile = I('post.mobile', '');
        $password = I('post.password', '');
        $loginInfo = D('Agent/Agent')->agentLogin($mobile, $password);
        if( $loginInfo['status'] == 1 ){ //登录成功
            unset($loginInfo['password']);
            /* 存入session */
            $this->_autoSession($loginInfo['data']);
            $this->ajaxReturn(V(1, '登录成功'));
        } else {
            $this->ajaxReturn(V(0, $loginInfo['info']));
        }
    }

    
    public function logout(){
        session(null);
        $this->redirect('index');
    }
    
    /* 记录登录SESSION和COOKIES */
    private function _autoSession($user){
        
        if ($user['id'] == 0) {
            session(null);
            $this->error('您不是代理商，不能登录！' );
        }
        $where['id'] = $user['id'];
        $shopInfo = M('Agent')->field('id,agent_name')->where($where)->find();
        $auth = array(
            'agent_id'             => $user['id'],
            'agent_name'       =>   $shopInfo['agent_name'],
            'last_login_time' => $user['last_login_time'],
        );

        session('agent_auth', $auth);
    }
    

    /**
     * [get_global_config 获取配置]
     * @return [type] [description]
     */
    public function get_global_config()
    {
        /* 读取数据库中的配置 */
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $configParse = new \Common\Tools\ConfigParse();
            $config      =   $configParse->lists();
            S('DB_CONFIG_DATA',$config,60);
        }
        C($config); //添加配置

    }

    //生成验证码图片
    public function chkcode(){
        $Verify = new \Think\Verify(array(
            'length' => 4,
            'useNoise' => FALSE,
            'imageH' =>40,
            'imageW' => 100,
            'fontSize'=>14,
            'useCurve'=>false
        ));
        $Verify->entry(1);
    }


}