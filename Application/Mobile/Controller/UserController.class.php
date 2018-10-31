<?php
/**
 * @Description    用户控制器
 * @Author         <byzhenyu@qq.com>
 * @Date           2018/10/29
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class UserController extends CommonController {
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
    	$userInfo = $userModel->getUserInfo($where, $field);
    	P($code);
    	p($userInfo);
    	exit;
    	$this->assign('code', $code);
    	$this->assign('userInfo', $userInfo);
    	$this->display();
    }
    /**
     * 任务总收入排名
     * @return   arr
     */
    public function getRankList()
    {
        $userModel = D('Home/User');
        $field = 'user_id,head_pic,user_name, task_suc_money';
        $rankList = $userModel->getRankList('', $field);
        p($rankList);
        exit;
        $this->assign('randList', $rankList);
        $this->display();
    }

}