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
//        $p =  $this->mobileWebPay();
//        p($p);
//        die;
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
        $type = I('type', 0, 'intval');
        if ($type == 1) {
            $where['change_type'] = array('IN','1,3,5,6,7,9,10,12');

        } else {
            $where['change_type'] = 0;
        }

        $record = D('Home/AccountLog')->getAccountLog($where);

        if (IS_POST) {
            foreach ($record as $k=>$v) {
                $record[$k]['change_time'] = time_format($v['change_time']);
                $record[$k]['user_money'] = fen_to_yuan($v['user_money']);
            }
            $this->ajaxReturn(V(1,'资金变动记录', $record));
        }
        $total_money = $userModel->getUserField($where, 'total_money');
        $this->assign('total_money', $total_money);
        $this->assign('payRecord',$record);

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
          /*判断是余额提现  还是分红提现  type 0 余额提现 1分红提现*/
          $type = I('type', 0, 'intval');
          $where['user_id'] = UID;
          $is_bind = is_bind($where);
          if($is_bind){
              $field = 'bonus_money, u.total_money, s.shop_type, s.partner_time';
              $shopInfo = D('Home/Shop')->getShopInfo(UID,'',$field);
              if($shopInfo['partner_time'] < NOW_TIME){
                  $shopInfo['shop_type'] = 0;
              }
              $shopInfo['type'] = $type;
              if(IS_POST){
                  $data = I('post.', 2);
                  $data['money'] = yuan_to_fen($data['money']);
                  $data['user_id'] = UID;
                  $userAccountModel = D('Home/UserAccount');
                  $drawRes = $userAccountModel ->withdraw($data);
                  $this->ajaxReturn($drawRes);
              }
              $this->assign('shopInfo',$shopInfo);
                  $this->display();
          }else{
              $this->display('Pay/bindAlipay');
          }
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
            $userRes = $this->user->where(array('alipay_num'=>$data['alipay_num']))->find();
            if ($userRes) {
                $this->ajaxReturn(V(0, '绑定失败,已有账号绑定该支付宝!'));
            }
            $result = $this->user->where(array('user_id'=>UID))->save($data);
            if($result){
                $this->ajaxReturn(V(1, '绑定成功',$data['user_id']));
            }else{
                $this->ajaxReturn(V(0, $this->user->getError()));
            }
        }
        $where['user_id'] = $user_id;
        $is_bind = is_bind($where);
        $alipay = $this->user->field('alipay_num, alipay_name')->where(array('user_id'=>UID))->find();
        $this->assign('alipay',$alipay);
        $this->assign('is_bind',$is_bind);
        $this->display();
       }
    /**
     * @desc 收入分红
     * @param UID
     * @return mixed
     */
        public function incomeDividends(){
            $user_id = UID;
            $p = I('p', 0, 'intval');
            $type = I('type', 0, 'intval');

            $where['user_id'] = $user_id;
            if ($type == 0) {
                $where['change_type']  = 2;
            } else {
                $where['change_type']  = 8;
            }
            $pmoney = D('Home/AccountLog')->getAccountLog($where);
            if (IS_POST) {

                if ($pmoney) {
                    foreach ($pmoney as $k=>$v) {
                        $pmoney[$k]['change_time'] = time_format($v['change_time']);
                        $pmoney[$k]['user_money'] = fen_to_yuan($v['user_money']);
                    }
                    $this->ajaxReturn(V(1, '加载成功',$pmoney));
                } else {
                    $this->ajaxReturn(V(1, '加载完毕'));
                }
            }
        $bonus_money = $this->user->where(array('user_id'=>UID))->getField('bonus_money');
        $this->assign('bonus_money',$bonus_money);
        $this->assign('pmoney',$pmoney);
        $this->display();
      }
      /**
      * @desc 保证金解冻
      * @param  shop_accounts 保证金额
      * @return mixed
      */
      public function unfreeze(){
          $shop_accounts = I('shop_accounts', 0 , 'intval');
          M()->startTrans();
          $shopRes = D('Home/Shop')->where('user_id  = '.UID)->setDec('shop_accounts',$shop_accounts);
          if(!$shopRes){
              $this->ajaxReturn(V(2, '保证金不足'));
          }
          $userInfo = D('Home/User')->field('alipay_num, alipay_name')->where('user_id = '.UID)->find();
          if($userInfo['alipay_num'] == ''  || $userInfo ['alipay_name'] == ''){
              $this->ajaxReturn(V(3, '请绑定支付宝解冻保证金!'));
          }
          /*添加提现信息*/
          $insData['user_id'] = UID;
          $insData['drawmoney'] = $shop_accounts;
          $insData['account_fee'] = 0.01 * $shop_accounts;
          $insData['money'] = $shop_accounts * 0.99;
          $insData['brank_no'] = $userInfo['alipay_num'];
          $insData['brank_user_name'] = $userInfo['alipay_name'];
          $insData['type'] = 2;
          $insRes = D('Home/UserAccount')->add($insData);
          account_log(UID, $shop_accounts, 12 , '解冻保证金(待审核)',$insRes);
          if($shopRes && $insRes){
              M()->commit();
              $this->ajaxReturn(V(1, '解冻成功'));
          }else{
              M()->rollback();
              $this->ajaxReturn(V(2, '解冻失败'));
          }
      }
}