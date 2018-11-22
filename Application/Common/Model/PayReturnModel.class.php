<?php
/**
 * 支付完成后的回调, 写入数据库
 */
namespace Common\Model;
use Think\Model;
class PayReturnModel extends Model{
    protected $tableName = 'Order'; 

    /**
     * 支付成功后回调
     * @param $out_trade_no string 自家定单号
     * @param $total_amount int 支付的金额
     * @param $trade_no string 支付平台交易号
     * @param $pay_bank int 支付的银行  支付方式 1：支付宝 2：微信 3：银联支付 4：余额支付 5：好友代付
     * @return array
     */
    public function paySuccess($out_trade_no='', $total_amount=0, $trade_no='', $pay_bank=-1){
        // LL($out_trade_no .'|'. $total_amount .'|'. $trade_no .'|'. $pay_bank );
        if ($out_trade_no == '') return V(0, '参数错误, 需要自家定单号: $out_trade_no');
        if ($total_amount < 0) return V(0, '参数错误, 支付金额不正确: $total_amount');
        if ($trade_no == '') return V(0, '参数错误, 需要支付平台交易号: $trade_no');
        if (intval($pay_bank) == -1) return V(0, '参数错误, 支付方式不正确: $pay_bank');

        //根据订单号查看支付类型
        $pay_type = substr($out_trade_no, 0, 1);
        
        if ($pay_type == 'S' || $pay_type == 'M') { //订单支付
            return $this->orderPay($out_trade_no, $total_amount, $trade_no, $pay_bank);
        }
        elseif ($pay_type == 'C') { //余额充值
            return $this->rechargePay($out_trade_no, $total_amount, $trade_no, $pay_bank);
        }
        elseif ($pay_type == 'J') { //机械出租支付
            return $this->depositPay($out_trade_no, $total_amount, $trade_no, $pay_bank);
        }
        elseif ($pay_type == 'D') { //物流需求支付
            return $this->demandPay($out_trade_no, $total_amount, $trade_no, $pay_bank);
        }
        elseif ($pay_type == 'R') { //白条还款
            return $this->repaymentPay($out_trade_no, $total_amount, $trade_no, $pay_bank);
        }
    }

    /**
     * 订单成功支付处理
     */
    public function orderPay($out_trade_no, $total_amount, $trade_no, $pay_bank) {
        $order = $this->where('order_sn="'. $out_trade_no .'"')->find();
        if (!$order) {
            return V(0, '定单不存在, 未知原因!');
        }
        $real_pay_amount = $order['real_pay_amount'] - $order['white_pay_amount'];
        // if ((int)$real_pay_amount != (int)yuan_to_fen($total_amount)) { // 金额不一致
        //     return V(0, '支付的金额不正确, 请务非法操作');
        // }
        if ($order['pay_status'] != 0) {
            return V(0, '定单支付状态已改变, 不能再改变');
        }

        M()->startTrans(); // 开启事务
        $userWhiteLogModel = D('Home/UserWhiteLog');
        if ($order['parent_order_sn'] == '0') { // 支付的为主定单
            // 把主定单下面的所有子定单查询出来
            $subOrder = $this->where('parent_order_sn="'. $out_trade_no .'"')->select();
            foreach ($subOrder as $key => $value) {
                // 更新所有子定单状态
                $sonRes = $this->_saveOrderInfo($subOrder[$key]['order_sn'], $trade_no, $pay_bank);
                if ($sonRes === false) {
                    M()->rollback(); // 事务回滚
                }
                // 下面减少库存
                $sonStorge = $this->decreaseGoodsNumber($subOrder[$key]['order_sn']);
                if ($sonStorge === false) {
                    M()->rollback(); // 事务回滚
                }
                if ($value['white_pay_amount'] != 0 && $value['order_status'] != 2) {
                    //处理白条支付
                    $whiteRes = $this->_saveUserWhite($value['white_pay_amount'], $value['user_id']);
                    if ($whiteRes !== false) {
                        $userWhiteLogModel->addWhiteLog($value['white_pay_amount'], 1, $value['order_sn'], '白条消费', $value['user_id']);
                    } else {
                        M()->rollback(); // 事务回滚
                    }
                }

                //写入订单日志
                order_log($subOrder[$key]['order_sn'], '', '付款成功', 2);
            }
            
            // 更新主定单状态
            $mainRes = $this->_saveOrderInfo($out_trade_no);
            if ($mainRes === false) {
                M()->rollback(); // 事务回滚
            }

        } else { // 如果支付的是子定单
            $subOrder = $order;
            $sonRes = $this->_saveOrderInfo($out_trade_no,  $trade_no, $pay_bank);
            if ($sonRes === false) {
                M()->rollback(); // 事务回滚
            }

            // 下面减少库存
            $sonStorge = $this->decreaseGoodsNumber($out_trade_no);
            if ($sonStorge === false) {
                M()->rollback(); // 事务回滚
            }

            if ($order['white_pay_amount'] != 0 && $order['order_status'] != 2) {
                //处理白条支付
                $whiteRes = $this->_saveUserWhite($order['white_pay_amount'], $order['user_id']);
                if ($whiteRes !== false) {
                    $userWhiteLogModel->addWhiteLog($order['white_pay_amount'], 1, $out_trade_no, '白条消费', $order['user_id']);
                } else {
                    M()->rollback(); // 事务回滚
                }
            }

            // 查询还有没有未支付的子妹定单
            $map['pay_status'] = 0;
            $map['parent_order_sn'] = $subOrder['parent_order_sn'];
            $otherSubOrder = $this->where($map)->find();
            if ($otherSubOrder) {
                // 如果还有其它未支付的子定单, 变更主定单的状态, 为部分支付
                $mainRes = $this->_saveOrderInfo($subOrder['parent_order_sn'],'', 0,2);
            } else {
                // 如果没有其它子妹定单未支付, 变更主定单的状态, 为已支付
                $mainRes = $this->_saveOrderInfo($subOrder['parent_order_sn']);
            }
            if ($mainRes === false) {
                M()->rollback(); // 事务回滚
            }

            //写入订单日志
            order_log($out_trade_no, '', '付款成功', 2);
        }

        //余额支付操作
        if ($pay_bank == 4) {
            //减少会员余额
            $userRes = D('Home/User')->changeUserMoney($total_amount, 2);
            if ($userRes === false) {
                M()->rollback(); // 事务回滚
            }
            //记录会员余额变动
            if ($order['parent_order_sn'] == '0') { // 支付的为主定单
                $subOrder = $this->where('parent_order_sn="'. $out_trade_no .'"')->select();
                foreach ($subOrder as $key => $value) {
                    account_log(UID, $value['real_pay_amount']-$value['white_pay_amount'], 2, '购物消费：'.$value['order_sn'], $value['order_sn']);
                }
            } else {
                account_log(UID, yuan_to_fen($total_amount), 2, '购物消费：'.$out_trade_no, $out_trade_no);
            }
        }

        //如果为代付，插入代付记录表
        if (UID != $order['user_id']) {
            $pay_money = $order['real_pay_amount'] - $order['white_pay_amount'];
            $pay_result = D('Home/UserPay')->insertPayLog($pay_money, $pay_bank, $out_trade_no, $order['user_id']);
            if ($pay_result === false) {
                M()->rollback(); // 事务回滚
            }
        }
        
        M()->commit(); // 事务提交
        
        return V(1, '定单支付成功, 状态已更新!');
    }

    /**
     * 余额充值成功支付处理
     */
    public function rechargePay($out_trade_no, $total_amount, $trade_no, $pay_bank) {
        $trade_no_array = explode('-', $out_trade_no);
        $user_id = $trade_no_array[1];
        $result = D('Common/PayRecharge')->paySuccess($total_amount, $user_id, $trade_no, $pay_bank);
        return $result;
    }

    /**
     * 私有, 写入定单信息
     * @param string $out_trade_no 全部以order_sn为原则
     * @param string $trade_no  交易号
     * @param int $pay_bank 支付银行编号
     * @param int $pay_status 支付状态: 0为未支付, 1为已支付, 2为部分支付(对主定单来说)
     * @return null
     */
    private function _saveOrderInfo($out_trade_no, $trade_no = '', $pay_bank = 0, $pay_status=1){
        $saveInfo['order_status'] = 2; // 定单状态
        $saveInfo['pay_time'] = NOW_TIME; // 支付时间
        $saveInfo['pay_status'] = $pay_status;  // 支付状态
        $saveInfo['pay_type'] = 1; // 支付类型 1在线支付
        $saveInfo['pay_bank'] = $pay_bank;
        $saveInfo['trade_no'] = $trade_no;
        $res = $this->where('order_sn="'. $out_trade_no .'"')->save($saveInfo);
        return $res;
    }

    /**
     * 处理订单白条
     * @param string $white_money 使用白条金额
     * @param int $user_id 会员id
     * @return null
     */
    private function _saveUserWhite($white_money, $user_id){
        $userModel = D('Home/User');
        $where['user_id'] = $user_id;
        $user_info = $userModel->getUserInfo($where, 'white_money,white_frozen_money,white_used_money');
        if ($user_info['white_frozen_money'] >= $white_money) {
            $data['white_frozen_money'] = $user_info['white_frozen_money'] - $white_money;
        }
        $data['white_used_money'] = $user_info['white_used_money'] + $white_money;
        $res = $userModel->where($where)->save($data);
        
        return $res;
    }

    /**
     * 减少库存
     * @param $order_sn 要减库存的定单SN
     */
    public function decreaseGoodsNumber($order_sn){
        $where['order_sn'] = $order_sn;
        $goods_spec_model = D('Home/GoodsSpec');
        $orderGoodsData = M('OrderGoods')->field('goods_id, goods_spec_key, buy_number')->where($where)->select();
        
        $tempBool = true;
        foreach ($orderGoodsData as $key => $value){
            $tempResult = $goods_spec_model->change_goods_storage($value['goods_id'], $value['goods_spec_key'], $value['buy_number'], 2);
            if(false === $tempResult) $tempBool = false;
        }
        return $tempBool;
    }

    /**
     * 机械出租定金支付
     */
    public function depositPay($out_trade_no, $total_amount, $trade_no, $pay_bank) {
        $result = D('Common/PayMachine')->depositPaySuccess($out_trade_no, $total_amount, $trade_no, $pay_bank);
        return $result;
    }

    /**
     * 物流需求支付
     */
    public function demandPay($out_trade_no, $total_amount, $trade_no, $pay_bank) {
        $trade_no_array = explode('-', $out_trade_no);
        $user_id = $trade_no_array[1];
        $result = D('Common/PayDemand')->demandPaySuccess($out_trade_no, $total_amount, $trade_no, $pay_bank);
        return $result;
    }

    /**
     * 白条还款
     */
    public function repaymentPay($out_trade_no, $total_amount, $trade_no, $pay_bank) {
        $trade_no_array = explode('-', $out_trade_no);
        $user_id = $trade_no_array[1];
        $result = D('Common/PayRepayment')->repaymentSuccess($out_trade_no, $total_amount, $trade_no, $pay_bank, $user_id);
        return $result;
    }

}