<?php
/**
 * 支付完成后的回调, 写入数据库
 */
namespace Common\Model;
use Think\Model;
class RechargeModel extends Model{
    /**
     * 支付成功后回调
     * @param $out_trade_no string 自家定单号
     * @param $total_amount int 支付的金额
     * @param $trade_no string 支付平台交易号
     * @param $pay_bank int 支付的银行  支付方式 1：支付宝 2：微信 3：银联支付 4：余额支付 5：好友代付
     * @return array
     */
    public function paySuccess($out_trade_no='', $total_amount=0, $trade_no='', $pay_bank= 1){
        // LL($out_trade_no .'|'. $total_amount .'|'. $trade_no .'|'. $pay_bank );
        if ($out_trade_no == '') return V(0, '参数错误, 需要自家定单号: $out_trade_no');
        if ($total_amount < 0) return V(0, '参数错误, 支付金额不正确: $total_amount');
        if ($trade_no == '') return V(0, '参数错误, 需要支付平台交易号: $trade_no');
        if (intval($pay_bank) == -1) return V(0, '参数错误, 支付方式不正确: $pay_bank');

        //根据订单号查看支付类型
        $pay_type = substr($out_trade_no, 0, 1);
        
        if ($pay_type == 'T') {//余额
            return $this->rechargeUserMoneyPay($out_trade_no, $total_amount, $trade_no);
        } else {//保证金
            return $this->rechargeShopMoneyPay($out_trade_no, $total_amount, $trade_no);
        }
    }

    /**
     * 订单成功支付处理
     */
    public function rechargeUserMoneyPay($out_trade_no, $total_amount, $trade_no) {
        $rechargeModel = M('recharge');
        $userModel = D('Home/User');
        $recharge =  $rechargeModel->where(array('order_sn'=>$out_trade_no))->find();
        if (!$recharge) {
            return V(0, '定单不存在, 未知原因!');
        }
        if ($recharge['pay_status'] != 0) {
            return V(0, '订单支付状态已改变, 不能再改变');
        }
        M()->startTrans();
        $rechargeRes =  $rechargeModel->where(array('order_sn' => $out_trade_no))->save(array('pay_status' => 1,'trade_no' => $trade_no));
        $userRes = $userModel-> where('user_id = '.$recharge['user_id'])->setInc('total_money',$recharge['recharge_money']);

        $invitation_uid  =  is_inviter($recharge['user_id']);
        if($invitation_uid  != 0){
            inviterBonus($recharge['user_id'], $invitation_uid ,$recharge['recharge_money']);
        }
        $accountRes = account_log($recharge['user_id'], $recharge['recharge_money'] ,0,'充值',$out_trade_no);
        if($rechargeRes === false || $userRes === false){
            M()->rollback();
            return V(0, '订单支付失败');
        }
        if ($accountRes ===false) {
            M()->rollback();
            return V(0, '订单日志失败');
        }
        M()->commit();
        return V(1 ,'支付成功');

    }
    //保证金支付
    public function rechargeShopMoneyPay($out_trade_no, $total_amount, $trade_no) {
        $rechargeModel = M('recharge');
        $shopModel = D('Shop');
        $rechargeInfo = $rechargeModel->where(array('order_sn'=>$out_trade_no))->find();
        if (empty($rechargeInfo)) {
            LL('订单有误','./log/a.txt');
            return V(0, '订单不存在, 未知原因!');
        }
        if ($rechargeInfo['pay_status'] != 0) {
            return V(0, '订单支付状态已改变, 不能再改变');
        }
        M()->startTrans();
        //修改支付状态
        $PayData['pay_status'] = 1;
        $PayData['trade_no'] = $trade_no;
        $payRes = $rechargeModel->where(array('order_sn'=>$out_trade_no))->save($PayData);
        $shopRes = $shopModel->where(array('user_id'=>$rechargeInfo['user_id']))->setInc('shop_accounts', $rechargeInfo['recharge_money']);
        if ($payRes === false || $shopRes ===false) {
            M()->rollback(); // 事务回滚
            return V(0, '充值失败');
        }
        $account_log = account_log($rechargeInfo['user_id'], $rechargeInfo['recharge_money'], 11, '保证金充值', $out_trade_no);
        if ($account_log === false) {
            M()->rollback(); // 事务回滚
            return V(0, '充值失败');
        }
            M()->commit();
        return V(1, '充值成功');

    }

}