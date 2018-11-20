<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/20 0020 16:44
 * @CreateBy       PhpStorm
 */

namespace Home\Controller;
use Common\Controller\CommonController;
use Common\Controller\UserCommonController;

class PayController extends UserCommonController{
    public function _initialize()
    {
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
//        $this->ajaxReturn(V(1, '充值5555成功',$data));
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
        $where['change_type'] = array('IN','1,3,5,6,7,9,10,12');
        $expendRecord = getAccount($where);
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
            p($shopInfo);
            $this->display();
        }else{
            $this->redirect('User/personalCenter/id_band/1');
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
            $userRes = $this->user->where('alipay_num ='.$data['alipay_num'])->find();
            if($userRes){
                $this->ajaxReturn(V(0, '绑定失败,已有账号绑定该支付宝!'));
            }
            $result = $this->user->where('user_id = '.UID)->save($data);
            if($result){
                $this->ajaxReturn(V(1, '绑定成功',$data['user_id']));
            }else{
                $this->ajaxReturn(V(0, $this->user->getError()));
            }
        }
        $where['user_id'] = $user_id;
        $is_bind = is_bind($where);
        $alipay = $this->user->field('alipay_num, alipay_name')->where('user_id ='.$user_id)->find();
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
        $bonus_money = $this->user->where('user_id = '.$user_id)->getField('bonus_money');
        $where['user_id'] = $user_id;
        $where['change_type']  = 2;
        $pmoney = getAccount($where);
        $where['change_type']  = 8;
        $tmoney = getAccount($where);
        $this->assign('bonus_money',$bonus_money);
        $this->assign('tmoney',$tmoney);
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
        account_log(UID, $shop_accounts, 12 , '解冻保证金(待审核)',UID);
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
        $insData['account_fee'] = 0.01;
        $insData['money'] = $shop_accounts * 0.99;
        $insData['brank_no'] = $userInfo['alipay_num'];
        $insData['brank_user_name'] = $userInfo['alipay_name'];
        $insData['type'] = 2;
        $insRes = D('Home/UserAccount')->add($insData);
        if($shopRes && $insRes){
            M()->commit();
            $this->ajaxReturn(V(1, '解冻成功'));
        }else{
            M()->rollback();
            $this->ajaxReturn(V(2, '解冻失败'));
        }
    }
}