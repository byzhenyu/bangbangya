<?php
/**
 * @Description    登录注册控制器
 * @Author         <406752025@qq.com>
 * @Date           2018/10/29
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class UserController extends CommonController {
	public function __construct(){
        parent::__construct();
        $this->get_global_config();
    }
    /**
     * 好友邀请
     * @return [type] [description]
     */
    public function friendQequest()
    {
    	$userModel = D('Home/User');
    	$where['user_id'] = UID;
    	$field = 'head_pic,nick_name';
    	$code = $userModel->createCode(UID);  /*创建邀请码*/
    	$userInfo = $userModel->getUserInfo($where,$field);
    	P($code);
    	p($userInfo);
    	die;
    	$this->assign('code',$code);
    	$this->assign('userInfo',$userInfo);
    	$this->display();
    }
    
}