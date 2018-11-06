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
          $where['u.user_id'] = UID;
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
    /**
    * @desc 查看我的合作商等级
    * @param uid
    * @return mixed
    */
    public function getVip(){
         $shop_type = I('shop_type');
         $this->assign('shop_type', $shop_type);
         $this->display();
    }
    /**
    * @desc 绑定支付宝
    * @param
    * @return mixed
    */
    public function bindAlipay(){
         $user_id = I('user_id', 0, 'intval');
         if(IS_POST){
             $data = I('post.', 3);
             $this->ajaxReturn(V(1, '绑定成功',$data));
             $result = $this->user->save($data);
              if($result){
                  $this->ajaxReturn(V(1, '绑定成功'));
              }else{
                  $this->ajaxReturn(V(0, $this->user->getError()));
              }
         }
         $this->assign('user_id',$user_id);
         $this->display();
    }
}