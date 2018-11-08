<?php
/**
 * /**  
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description    用户控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/31 0031 15:16
 * @CreateBy       PhpStorm
 */

namespace Mobile\Controller;
use Common\Controller\CommonController;
class UserController extends CommonController {
    public function _initialize() {
        $this->user = D("Home/User");
    }
    /**
    * @desc 用户中心
    * @param uid
    * @return mixed
    */
    public function personalCenter(){
          $user_id = UID;
          $where['u.user_id'] = $user_id;
          $field = 'u.head_pic, u.nick_name, u.total_money,u.bonus_money,s.shop_type, u.task_suc_money,u.user_id, s.shop_accounts,s.take_task';
          $userList = $this->user->getUserInfo($where, $field);
          $this->assign('userList',$userList);
          $this->display();
    }
    /**
    * @desc 保证金
    * @param UID
    * @return mixed
    */
    public function securityDeposit(){
        $shop_accounts = I('shop_accounts');
        /*是否解冻*/
        if($shop_accounts > 0){
            $unfreeze = 1;
        }else{
            $unfreeze = 0;
        }
        $this->assign('unfreeze',$unfreeze);
        $this->assign('shop_accounts',$shop_accounts);
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
        $field = 'user_id,head_pic,user_name, task_suc_money';
        $rankList = $this->user->getRankList('', $field);
        p($rankList);
        exit;
        $this->assign('randList', $rankList);
        $this->display();
    }
    /**
    * @desc 合作商查看
    * @param  $user_id
    * @param  $shop_type
    * @return mixed
    */
     public  function partners(){
         $vip = getVip();
         $user_id = UID;
         $where['u.user_id'] = $user_id;
         $field = 's.shop_type, s.partner_time ';
         $shopInfo  = $this->user->getUserInfo($where, $field);
         if($shopInfo['shop_type']  == 0){
                  $shop_type = 0;
         }elseif($shopInfo['partner_time']  < NOW_TIME){
                  $shop_type = 0;
         }else{
                  $shop_type = $shopInfo['shop_type'];
         }
         $this->assign('shop_type', $shop_type);
         $this->assign('vip', $vip);
         $this->display();
      }
}