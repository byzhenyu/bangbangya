<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     充值控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/6 0006 13:05
 * @CreateBy       PhpStorm
 */

namespace Mobile\Controller;
use Common\Controller\CommonController;
class PayController  extends CommonController{
    public function _initialize() {
        $this->user = D("Home/User");
    }
    /**
    * @desc 充值
    * @param  $user_id
    * @param  $money
    * @return mixed
    */
    public function  alipay(){
        $data['user_id'] = UID;
        $data['recharge_money'] = I('recharge_money',0 , 'intval');
        $data['order_sn'] = makeOrderSn($data['user_id']);
        $result = M('recharge')->add($data);
        /*支付宝回调用*/
        $userModel = D('Home/User');
        account_log($data['user_id'],$data['recharge_money'],0,'充值',$data['order_sn']);
        $userModel -> where('user_id = '.$data['user_id'])->setInc('total_money',$data['recharge_money']);
        $invitation_uid  =  is_inviter($data['user_id']);
        if($invitation_uid  != 0){
            inviterBonus($data['user_id'], $invitation_uid ,$data['recharge_money']);
        }
        /*end */
        $this->ajaxReturn(V(1, '充值成功',$data['user_id']));
    }
    /**
     * @desc 我的钱包
     * @param uid
     * @return mixed
     */
    public function myWallet(){
        $userModel = D('Home/User');
        $where['user_id'] = UID;
        $total_money = $userModel->getUserField($where, 'total_money');
        $where['change_type'] = 0;
        $payRecord = getAccount($where);
        $where['change_type'] = array('IN','1,3,6');
        $expendRecord = getAccount($where);
        $this->assign('user_id', $where['user_id']);
        $this->assign('total_money', $total_money);
        $this->assign('payRecord',$payRecord);
        $this->assign('expendRecord',$expendRecord);
        $this->display();
    }
      /**
      * @desc 充值页面
      * @param
      * @return mixed
      */
      public function topUpsPage(){
          $user_id = UID;
          $this->display();

      }
      /**
      * @desc  用户提现
      * @param  user_id
      * @return mixed
      */
      public  function withdraw()
      {
          $user_id = I('user_id', 0 , 'intval');
          $this->assign('user_id', $user_id);
          $this->display();
      }
    /**
     * @desc 绑定支付宝
     * @param
     * @return mixed
     */
      public function bindAlipay(){
        $user_id = UID;
        if(IS_POST){
            $data = I('post.', 2);
            $result = $this->user->where('user_id = '.$data['user_id'])->save($data);
            if($result){
                $this->ajaxReturn(V(1, '绑定成功',$data['user_id']));
            }else{
                $this->ajaxReturn(V(0, $this->user->getError()));
            }
        }
        $alipay = $this->user->field('alipay_num, alipay_name')->where('user_id ='.$user_id)->find();
        $this->assign('alipay',$alipay);
        $this->display();
       }
    /**
     * @desc 收入分红
     * @param UID
     * @return mixed
     */
       public function incomeDividends(){
        $user_id = UID;
        $bonus_money = $this->user->where('user_id = '.$user_id)->getField('bonus_money');
        $this->assign('bonus_money',$bonus_money);
        $this->display();
      }
      /**
      * @desc  提现
      * @param  $user_id
      * @param  money
      * @return mixed
      */
}