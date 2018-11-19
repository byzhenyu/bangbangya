<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     0 余额体现 1分红提现model
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/8 0008 8:31
 * @CreateBy       PhpStorm
 */

namespace Home\Model;
use Think\Model;
class UserAccountModel extends Model{
    protected $insertFields = array('id','title','category_id', 'mobile_type', 'end_time','price','task_num','total_price','link_url','validate_words','remark','audit_info');
    protected $_validate = array(
        array('user_id', 'require', '用户ID不能为空！', 1, 'regex', 3),
        array('type', 'require', '提现类型不能为空！', 1, 'regex', 3),
        array('money', 'require', '提现金额不能为空！', 2, 'callback', 3),
        array('brank_no', 'require', '提现账号不能为空！', 1, 'callback', 3),
        array('brank_user_name', 'require', '提现姓名不能为空！', 1, 'regex', 3),
    );
    /**
    * @desc 判断用户是否可以提现
    * @param $uid
    * @return mixed
    */
    public function is_draw($data) {
        $userInfo = D('Home/Shop')->where('user_id = '.$data['user_id'])->field('shop_type,partner_time')->find();
        $this->userAuth = $userInfo['shop_type'];

        if ($userInfo['shop_type']  > 0 && ($userInfo['partner_time'] > NOW_TIME)) { //合作商 每天三次
            $where['user_id'] = $data['user_id'];
            $where['add_time']  = array('gt',strtotime(date('Y-m-d',time())));
            $drawNum = $this->where($where)->count();
            if ($drawNum >= 3) {
                return false;
            }
        } else { //非合作商7天一次
            $where['user_id'] = $data['user_id'];
            $where['add_time']  = array('gt',strtotime('-7 days'));
            $drawNum = $this->where($where)->count();
            if($drawNum > 0){
                return false;
            }
        }
        return true;
    }
    public function withdraw($data){
         /*验证提现次数是否用完*/
         $is_draw = $this->is_draw($data);
         if(!$is_draw){
             return V('1','提现次数已经用光了!');
         }
         M()->startTrans();
        $userModel = D('Home/User');
        $userInfo = $userModel->field('alipay_num, total_money,bonus_money, alipay_name')->where('user_id = '.$data['user_id'])->find();
        if($data['type'] == 0) {
            if($userInfo['total_money'] < $data['money']){
                return V('2','余额不足!');
            }
            $changeDesc = '余额提现(待审核)';
            $type = 1;
            $saveData['total_money'] = array('exp', "total_money - ".$data['money']);
            $saveData['frozen_money'] = array('exp', "frozen_money +".$data['money']);
        }else{
            if($userInfo['bonus_money'] < $data['money']){
                return V('2','余额不足!');
            }
            $type = 8;
            $changeDesc = '分红提现(待审核)';
            $saveData['bonus_money'] = array('exp', "bonus_money - ".$data['money']);
            $saveData['frozen_money'] = array('exp', "frozen_money +".$data['money']);
        }
        $userRes  = $userModel ->where('user_id = '.$data['user_id'])->save($saveData);
        account_log($data['user_id'],$data['money'],$type, $changeDesc, '');
        /*根据权限 算出提现应到的钱数*/
        if($this->userAuth == 0){
             $serviceCharge  = C(BASE_WITHDRAW_FEE) / 100;
        }else{
             $vip = M('vip_level')->where('type = '.$this->userAuth)->getField('withdraw_fee');
             $serviceCharge  = $vip  / 100;
        }
        /*添加提现信息*/
        $insData['user_id'] = $data['user_id'];
        $insData['drawmoney'] = $data['money'];
        $insData['account_fee'] = $data['money'] * $serviceCharge;
        $insData['money'] = $data['money'] * (1 - $serviceCharge);
        $insData['brank_no'] = $userInfo['alipay_num'];
        $insData['brank_user_name'] = $userInfo['alipay_name'];
        $insData['type'] = $data['type'];
        $insRes = $this->add($insData);
        /*查看分红比例 以及添加分红*/
//        $inviter   =  is_inviter($data['user_id']);
//        if($inviter  != 0){
//            inviterBonus($data['user_id'], $inviter , $data['money'] , 1);
//        }
        if($userRes && $insRes){
              M()->commit();
            return V('0','提现成功!',$data['type']);
        }else{
              M()->rollback();
            return V('3','提现失败!');
        }
    }
    protected function _before_insert(&$data, $option){
        $data['add_time'] = NOW_TIME;
    }
}