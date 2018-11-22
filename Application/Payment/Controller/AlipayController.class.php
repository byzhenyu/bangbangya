<?php
/**
 * 支付相关
 * by zhoaojiping liuniukeji.com <QQ: 17620286>
 */
namespace Payment\Controller;
use Common\Controller\CommonController;

class AlipayController extends CommonController {

    // alipay 定单支付
    public function alipay(){
        $data['user_id'] = UID;
        $data['recharge_money'] = I('recharge_money',0 , 'intval');
        $data['order_sn'] = makeOrderSn($data['user_id']);
        M('recharge')->add($data);
        $data['body'] = '网页充值';
        $data['subject'] = '网页充值';
        $data['out_trade_no'] =  $data['order_sn'];
        $data['total_amount'] = '0.01';
        require_once("./Plugins/AliPay/AliPay.php");
        $alipay = new \AliPay();
        echo '页面跳转中, 请稍后...';
        echo $alipay->AliPayWeb($data);
    }

    // 定单支付回调
    public function alipayNotify() {
        require_once("./Plugins/AliPay/AliPay.php");
        $alipay = new \AliPay();
        //验证是否是支付宝发送
        $flag = $alipay->AliPayNotifyCheck();
        if ($flag) {
            p($_POST);
            if ($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
//                $out_trade_no = trim($_POST['out_trade_no']); //商户订单号
//                $total_amount = trim($_POST['total_amount']); //支付的金额
//                $trade_no = trim($_POST['trade_no']); //商户订单号
//                //成功后的业务逻辑处理
//                $result = D('Common/PayReturn')->paySuccess($out_trade_no, $total_amount, $trade_no, 1);
//                if ($result['status'] == 1) {
//                    echo "success"; //  告诉支付宝支付成功 请不要修改或删除
//                    die;
//                } else {
//                    LL($result);
//                }
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
}