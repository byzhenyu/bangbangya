<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description
 * @Author   
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           ${DATE} ${TIME}
 * @CreateBy       ${PRODUCT_NAME}
 */

namespace Home\Controller;
use Common\Controller\CommonController;
use Common\Controller\UserCommonController;
class UserController extends UserCommonController {
    public function _initialize() {
        $this->user = D("Home/User");
    }
    /**
     * @desc  邀请码邀请
     * @param  uid  code
     * @param  code
     * @return mixed
     */
    public function Invitation(){
        if(IS_POST){
            $invitation_code = I('invitation_code');
            $invitUserID  =  $this->user->decode($invitation_code);
            $invitUserInfo = $this->user->where('user_id ='.$invitUserID)->setInc('invitation_num');
            if(!$invitUserInfo)
            {
                $this->ajaxReturn(V(0, '您填写的邀请码有误!'));
            }else{
                $this->user->where('user_id = '.UID)->save(array('invitation_uid' => $invitUserID));
                $this->ajaxReturn(V(1, '填写成功'));
            }
        }
        $this->display();
    }
    /**
     * @desc 用户中心
     * @param uid
     * @return mixed
     */
    public function personalCenter(){
        $login  = I('login', 0, 'intval');
        $login  = I('id_band', 0, 'intval');
        $user_id = UID;
        $where['u.user_id'] = $user_id;
        $field = 'u.head_pic, u.nick_name,u.alipay_num,u.alipay_name,u.invitation_uid,u.register_time, u.total_money,u.bonus_money,s.shop_type, u.task_suc_money,u.user_id, s.shop_accounts,s.take_task';
        $userList = $this->user->getUserInfo($where, $field);
        if($userList['register_time']  + 3 > NOW_TIME){
            $userList['register_time'] = 1;
        }else{
            $userList['register_time'] = 0;
        }
        $this->assign('userList',$userList);
        $this->assign('login',$login);
        $this->assign('id_band',$id_band);
        $this->display();
    }
    public function userList()
    {
        $this->display();
    }

    /**
     * 好友邀请
     * @return [type] [description]
     */
    public function friendQequest()
    {
        $userInfo = $this->user->field('head_pic, nick_name, invitation_code')->find();
        $this->assign('userInfo', $userInfo);
        $this->display();
    }

    /**
     * 任务总收入排名
     * @return   arr
     */
    public function getRankList()
    {
        $where['task_suc_money']  = array('neq', 0);
        $field = 'user_id, head_pic, nick_name, task_suc_money';
        $rankList = $this->user->getRankList($where, $field);
        $this->assign('randList', $rankList);
        $this->display();
    }

}