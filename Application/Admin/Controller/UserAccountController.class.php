<?php
/**
 * 用户提现管理类
 */
namespace Admin\Controller;
use Think\Controller;
class UserAccountController extends CommonController {
    /**
     * 用户提现列表
     */
    public function listAccount(){
        //查询
        $keyword  = I('keyword', '', 'trim');
        if ($keyword) {
            $where['u.nick_name'] = array('like','%'.$keyword.'%');
            $this->keyword = $keyword;
        }

        //查询列表
//        $where['ua.type'] = 1;
        $where['ua.status'] = 1;
        $field = 'ua.*, u.nick_name, u.alipay_num, u.alipay_name,u.bonus_money,u.total_money';
        $list = D('Admin/UserAccount')->getUserAccountList($where, $field);
        $this->list = $list['list'];
        $this->page = $list['page'];
        $this->display();
    }

    /**
     * 用户提现详情
     */
    public function AccountInfo(){
        $id = I('id', 0, 'intval');
        $where['ua.id'] = $id;
        $userModel = D('Admin/UserAccount');
        $field = 'ua.*, u.nick_name';
        $userInfo = $userModel->getAccountInfo($where,$field);
        $this->userInfo = $userInfo;
        $this->display();
    }

    /**
     * 提现审核页面/修改用户提现审核状态
     */
    public function State(){
        $id = I('id', 0, 'intval');
        $state = I('state',0);
        $UserAccountModel = D('Admin/UserAccount');

        if (IS_POST) {
            $pushModel = D('Common/push');
            if ($id > 0) {
                $data =I('post.','');
                $data['admin_user'] = session('admin_name');
                $data['admin_time'] = NOW_TIME;

                if($UserAccountModel->create($data)){
                    M()->startTrans();// 开启事务
                    $result = $UserAccountModel->save();
                    if ($result === false) {
                        M()->rollback(); // 事务回滚
                        $this->ajaxReturn(V(0, '操作失败'));
                    }
                    $where['id'] = $id;
                    $accountInfo = $UserAccountModel->getUserAccountDetail($where, 'id, user_id, money, admin_note, account_fee, drawmoney,type');
                    $user_where['user_id'] = array('eq', $accountInfo['user_id']);
                    if ($state == 1) { //完成审核

                        if ($accountInfo['type'] == 2) { //保证金

                            $acc_where['change_type'] = 12;
                            $acc_where['order_sn'] = array('eq', $accountInfo['id']);
                            $change_desc = "解冻保证金已通过:手续费:".(fen_to_yuan($accountInfo['account_fee']));
                            $logRes = M('AccountLog')->where($acc_where)->setField('change_desc', $change_desc);
                            if ($logRes === false) {
                                M()->rollback(); // 事务回滚
                                $this->ajaxReturn(V(0, '修改日志操作失败'));
                            }

                        } else { //余额和分红提现
                            //减少会员冻结余额
                            $setUserMoney = D('Admin/User')->where($user_where)->setDec('frozen_money', $accountInfo['drawmoney']);
                            if ($setUserMoney === false) {
                                M()->rollback(); // 事务回滚
                                $this->ajaxReturn(V(0, '操作失败'));
                            }
                            if ($accountInfo['type'] == 0) {
                                //修改count日志
                                $change_desc = '余额提现已到账:手续费'.(fen_to_yuan($accountInfo['account_fee']));
                                $change_type = 1;
                                $acc_where['change_type'] = array('eq', 1);
                                $acc_where['order_sn'] = array('eq', $accountInfo['id']);
                            } elseif ($accountInfo['type'] == 1) {
                                $change_desc = '分红提现已到账：手续费'.(fen_to_yuan($accountInfo['account_fee']));
                                $change_type = 8;
                                $acc_where['change_type'] = 8;
                                $acc_where['order_sn'] = array('eq', $accountInfo['id']);
                            }
                            $logRes = M('AccountLog')->where($acc_where)->setField('change_desc', $change_desc);
                            $pushRes = $pushModel->push('提现审核通知',$accountInfo['user_id'], '提现成功', $change_desc, '通知类型：审核通知',$accountInfo['admin_note']);
                            if ($logRes === false) {
                                M()->rollback(); // 事务回滚
                                $this->ajaxReturn(V(0, '修改日志操作失败'));
                            }

                            //分红
                            $invi_uid = is_inviter($accountInfo['user_id']);
                            if ($invi_uid) {
                                inviterBonus($accountInfo['user_id'],$invi_uid,$accountInfo['drawmoney'],1);
                            };
                        }


                    } else if ($state == 2) { //驳回
                        if ($accountInfo['type'] == 2) { //保证金驳回
                            $res = M('Shop')->where(array('user_id'=>$accountInfo['user_id']))->setInc('shop_accounts', $accountInfo['drawmoney']);
                            if ($res === false) {
                                M()->rollback();
                                $this->ajaxReturn(V(0, '操作失败'));
                            }
                            $acc_where['change_type'] = 12;
                            $acc_where['order_sn'] = array('eq', $accountInfo['id']);
                            $change_desc = "解冻保证金(被拒绝)";
                            $logRes = M('AccountLog')->where($acc_where)->setField('change_desc', $change_desc);
                            if ($logRes === false) {
                                M()->rollback(); // 事务回滚
                                $this->ajaxReturn(V(0, '修改日志操作失败'));
                            }

                        } else {
                            //减少会员冻结余额
                            $setUserForzenMoney = D('Admin/User')->where($user_where)->setDec('frozen_money', $accountInfo['drawmoney']);
                            if ($setUserForzenMoney === false) {
                                M()->rollback(); // 事务回滚
                                $this->ajaxReturn(V(0, '操作失败'));
                            }
                            if ($accountInfo['type'] == 1) { //分红提现
                                $saveData['total_money'] = array('exp', "total_money+".$accountInfo['drawmoney']);
                                $saveData['bonus_money'] = array('exp', "bonus_money+".$accountInfo['drawmoney']);
                                $change_desc = '分红提现（未通过，已退回）';
                                $change_type = 8;
                                $acc_where['change_type'] = 8;
                                $acc_where['order_sn'] = array('eq', $accountInfo['id']);

                            } elseif ($accountInfo['type'] == 0) { //余额
                                $saveData['total_money'] = array('exp', "total_money+".$accountInfo['drawmoney']);
                                $saveData['task_money'] = array('exp', "task_money+".$accountInfo['drawmoney']);
                                $change_desc = '余额提现（未通过，已退回）';
                                $change_type = 1;
                                $acc_where['change_type'] = 1;
                                $acc_where['order_sn'] = array('eq', $accountInfo['id']);


                            }
                            $pushRes = $pushModel->push('提现审核通知',$accountInfo['user_id'], '提现失败', '提现金额'.fen_to_yuan($accountInfo['drawmoney']), '通知类型：审核通知',$accountInfo['admin_note']);
                            //增加会员余额
                            $setUserMoney = D('Admin/User')->where($user_where)->save($saveData);

                            if ($setUserMoney === false) {
                                M()->rollback(); // 事务回滚
                                $this->ajaxReturn(V(0, '操作失败'));
                            }

                            $logRes = M('AccountLog')->where($acc_where)->setField('change_desc', $change_desc);
                            if ($logRes === false) {
                                M()->rollback(); // 事务回滚
                                $this->ajaxReturn(V(0, '修改日志操作失败'));
                            }

                        }

                    }

                    M()->commit(); // 事务提交
                    $this->ajaxReturn(V(1, '操作成功', $id));
                } else {
                    $this->ajaxReturn(V(0, $UserAccountModel->getError()));
                }

            }
        } else {
            $this->id = $id;
            $this->state = $state;
            $this->display();
        }

    }
    /**
     * 删除方法
     */
    public function del() {
        $id = I('id', 0);
        $result = V(0, '删除失败, 未知错误');
        if($id != 0){
            $accountModel = M('UserAccount');
            $where['id'] = array('in', $id);
            $states= $accountModel->where($where)->getField('state',true);

            if (in_array(0,$states)) {
                $result = V(0, '有待处理申请，不能删除');
            } else {
                $data['status'] = 0;
                if( $accountModel->data($data)->where($where)->save() !== false){
                    $result = V(1, '删除成功');
                }
            }
        }
        $this->ajaxReturn($result);
    }
}