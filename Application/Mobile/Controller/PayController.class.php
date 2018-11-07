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
     * @desc 我的钱包
     * @param uid
     * @return mixed
     */
    public function myWallet(){
        $where['change_type'] = 0;
        $payRecord = getAccount($where);
        $where['change_type'] = array('IN','1,3,6');
        $expendRecord = getAccount($where);
        $this->assign('userInfo',$this->userInfo);
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
          $this->display();
      }
      /**
      * @desc  用户提现
      * @param  user_id
      * @return mixed
      */
      public  function withdraw()
      {
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
            $result = $this->user->where('user_id = '.$data['user_id'])->save($data);
            if($result){
                $this->ajaxReturn(V(1, '绑定成功',$data['user_id']));
            }else{
                $this->ajaxReturn(V(0, $this->user->getError()));
            }
        }
        $alipay = $this->user->field('alipay_num, alipay_name')->where('user_id ='.$user_id)->find();
        $this->assign('user_id',$user_id);
        $this->assign('alipay',$alipay);
        $this->display();
       }
    /**
     * @desc 收入分红
     * @param UID
     * @return mixed
     */
       public function incomeDividends(){
        $user_id = I('user_id', 0, 'intval');
        $bonus_money = $this->user->where('user_id = '.$user_id)->getField('bonus_money');
        $this->assign('bonus_money',$bonus_money);
        $this->assign('user_id',$user_id);
        $this->display();
      }
      /**
      * @desc  提现
      * @param  $user_id
      * @param  money
      * @return mixed
      */
}