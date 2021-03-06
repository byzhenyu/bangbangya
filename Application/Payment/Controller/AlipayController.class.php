<?php
/**
 * 支付相关
 * by zhoaojiping liuniukeji.com <QQ: 17620286>
 */
namespace Payment\Controller;
use Common\Controller\CommonController;

class AlipayController extends CommonController {

    // alipay 定单支付  type  0 余额充值 1 保证金充值
    public function alipay(){
        $type =  I('type', 0, 'intval');
        $data['user_id'] = UID;
        $recharge_money = I('recharge_money', 0);
        $data['recharge_money'] = yuan_to_fen($recharge_money);
        $order_sn = makeOrderSn($data['user_id']);
        if($type == 0){
            $data['order_sn'] = 'T'.$order_sn;
            $data['recharge_type'] = 0;
        }else{
            $data['order_sn'] = 'B'.$order_sn;
            $data['recharge_type'] = 1;
        }
        $data['add_time'] = NOW_TIME;
        M('recharge')->add($data);
        $data['body'] = C('APP_NAME').'网页充值';
        $data['subject'] = C('APP_NAME').'网页充值';
        $data['out_trade_no'] =  $data['order_sn'];
        $data['total_amount'] = $recharge_money;
        header("Content-type: text/html; charset=utf-8");
        require_once("./Plugins/AliPay/AliPay.php");
        $aliData = C('AliPay');
        $aliData['returnUrl'] =  'http://bby.host5.liuniukeji.net/index.php/Home/Pay/myWallet';
        $alipay = new \AliPay($aliData);
        echo '页面跳转中, 请稍后...';
        echo $alipay->AliPayWeb($data);
    }
    //移动web支付 示例
    public function mobileWebPay() {
        $type =  I('type', 0, 'intval');
        $data['user_id'] = UID;
        $recharge_money = I('recharge_money', 0);

        $data['recharge_money'] = yuan_to_fen($recharge_money);
        $order_sn = makeOrderSn($data['user_id']);
        if($type == 0){
            $data['order_sn'] = 'T'.$order_sn;
            $data['recharge_type'] = 0;
        }else{
            $data['order_sn'] = 'B'.$order_sn;
            $data['recharge_type'] = 1;
        }
        $data['add_time'] = NOW_TIME;
        M('recharge')->add($data);
        $data['body'] = C('APP_NAME').'H5充值';
        $data['subject'] = C('APP_NAME').'H5充值';
        $data['out_trade_no'] =  $data['order_sn'];
        $data['total_amount'] =  $recharge_money;
        $aliData = C('AliPay');
        $aliData['returnUrl'] =  'http://bby.host5.liuniukeji.net/index.php/Mobile/User/personalCenter';
        require_once("./Plugins/AliPay/AliPay.php");
        $alipay =new \AliPay($aliData);
        echo '页面跳转中, 请稍后...';
        echo $alipay->AliPayMobileWeb($data);

    }
    //原生支付
    public function appPay() {
        $type =  I('type', 0, 'intval');
        $data['user_id'] = UID;
        $recharge_money = I('recharge_money', 0);
        $data['recharge_money'] = yuan_to_fen($recharge_money);
        $order_sn = makeOrderSn($data['user_id']);
        if ($type == 0) {
            $data['order_sn'] = 'T'.$order_sn;
            $data['recharge_type'] = 0;
        } else {
            $data['order_sn'] = 'B'.$order_sn;
            $data['recharge_type'] = 1;
        }
        $data['add_time'] = NOW_TIME;
        M('recharge')->add($data);
        $data['body'] = C('APP_NAME').'充值';
        $data['subject'] = C('APP_NAME').'充值';
        $data['out_trade_no'] =  $data['order_sn'];
        $data['total_amount'] = $recharge_money;
        require_once("./Plugins/AliPay/AliPay.php");
        $alipay = new \AliPay();
        $result = $alipay->AliPayApp($data);
     
        $this->ajaxReturn(V(1, '成功', $result));
    }
    // 定单支付回调
    public function alipayNotify() {
        require_once("./Plugins/AliPay/AliPay.php");
        $alipay = new \AliPay();
        //p($_POST);
        //验证是否是支付宝发送
        $flag = $alipay->AliPayNotifyCheck();
        
        LL($_POST,'./log/log2.txt');
        if ($flag) {
            if ($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                $out_trade_no = trim($_POST['out_trade_no']); //商户订单号
                $total_amount = trim($_POST['total_amount']); //支付的金额
                $trade_no = trim($_POST['trade_no']); //商户订单号
                LL($total_amount,'./log/log1.txt');
                //成功后的业务逻辑处理
                $result = D('Common/Recharge')->paySuccess($out_trade_no, $total_amount, $trade_no, 1);
                if ($result['status'] == 1) {
                    echo "success"; //  告诉支付宝支付成功 请不要修改或删除
                    die;
                } else {
                    LL($result);
                }
            }
        }
        echo "fail"; //验证失败
        die;
    }

    //提现转账 示例
    public function withdraw() {
        $data['out_biz_no']='2018071913145';//订单号
        $data['payee_account'] ='510434563@qq.com';//收款支付宝账号
        $data['payee_real_name'] ='汲长玉';//收款支付宝账号真实姓名
        $data['amount'] ='0.1';//金额
        $data['payer_show_name']='六牛科技转账';
        $data['remark'] = '备注';
        require_once("Plugins/AliPay/AliPay.php");
        $alipay = new \AliPay();
        $result = $alipay->AliPayWithdraw($data);
        p($result);
    }

    public function aa() {
        $out_trade_no = trim($_POST['out_trade_no']); //商户订单号
        $total_amount = trim($_POST['total_amount']); //支付的金额
        $trade_no = trim($_POST['trade_no']); //商户订单号
        $result = D('Common/Recharge')->paySuccess($out_trade_no, $total_amount, $trade_no, 1);
        p($result);
    }
}