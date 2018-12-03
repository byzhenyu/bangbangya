<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description    任务管理
 * @Author         
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;

/**
 * 任务表控制器
 */
class TaskController extends CommonController {
    public function listTask(){
        $keyword = I('keyword', '');
        if ($keyword) {
            $where['t.title'] = array('like','%'.$keyword.'%');
        }
        $category_id = I('category_id', 0, 'intval');
        if ($category_id > 0) {
            $where['t.category_id'] = array('eq', $category_id);
        }
        $catlist = M('TaskCategory')->where(array('status'=>1))->select();
        $this->assign('catlist', $catlist);
        $where['t.status'] = array('eq', 1);
        $this->assign('keyword', $keyword);
        $this->assign('category_id', $category_id);
        $data = D('Admin/Task')->getTaskList($where);
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    
    public function editTask(){
        $id = I('id');
        $taskModel = D('Admin/Task');
        if (IS_POST) {
            $data = I('post.', '');
            $price= I('price', 0, 'intval');
            $task_num= I('task_num', 0, 'intval');
            if($data['audit_status'] == 0){
                $this->ajaxReturn(V(0, '请您审核!'));
            }
            M()->startTrans();
            if ($taskModel->create($data, 5) !== false) {
                $res = $taskModel->save($data);
                if ($res === false) {
                    M()->rollback();
                    $this->ajaxReturn(V(0, '审核失败'));
                }
                if ($data['audit_status'] == 1) {
                    $userModel = M('User');
                    $total_money = $userModel->where(array('user_id'=>$data['user_id']))->getField('total_money');
                    $shopInfo = M('Shop')->where(array('user_id'=>$data['user_id']))->field('shop_type,partner_time')->find();
                    $orderFee = C('BASE_ORDER_FEE');
                    if ($shopInfo['partner_time'] > NOW_TIME && $shopInfo['shop_type'] > 0) {
                        $vipFee = M('VipLevel')->where(array('type'=>$shopInfo['shop_type'],'status'=>1))->getField('order_fee');
                        if ($vipFee) {
                            $orderFee = $vipFee;
                        }
                    }

                    $feeMoney = $price * $task_num * ($orderFee / 100);

                    if ($feeMoney > $total_money) {
                        M()->rollback();
                        $this->ajaxReturn(V(0, '用户余额不足'));
                    }

                    $res = $userModel->where(array('user_id'=>$data['user_id']))->setDec('total_money',$feeMoney);

                    if ($res === false) {
                        M()->rollback();
                        $this->ajaxReturn(V(0, '扣除手续费失败'));
                    }
                    account_log($data['user_id'], $feeMoney, 3, '任务手续费扣除', $id);
                    $pushRes = D('Common/Push')->push("任务处理结果", $data['user_id'], '任务审核通过', '任务名称: '.$id, '通知类型： 通知', $data['audit_info']);
                    if ($pushRes['status'] == 0) {
                        M()->rollback();
                        $this->ajaxReturn($pushRes);
                    }
                    //更新店铺订单数量
                    $saveData['task_count'] = array('exp', 'task_count+1');
                    $saveData['task_num'] = array('exp', 'task_num+'.$data['task_num']);
                    $numRes= D('Admin/Shop')->updateTaskNum(array('user_id'=>$data['user_id']),$saveData);
                    if (!$numRes) {
                        M()->rollback();
                        $this->ajaxReturn(V(0, '更新发单数量失败'));
                    }
                } else if ($data['audit_status'] == 2) {
                    $pushRes = D('Common/Push')->push("任务处理结果", $data['user_id'], '任务审核未通过', '任务名称: '.$id, '通知类型： 通知', $data['audit_info']);
                    if ($pushRes['status'] == 0) {
                        M()->rollback();
                        $this->ajaxReturn($pushRes);
                    }
                }
                M()->commit();
                //推送
                $this->ajaxReturn(V(1,'审核成功'));
            } else {
                $this->ajaxReturn(V(0,$taskModel->getError()));
            }
        }
        $info = $taskModel->getTaskDetail($id);

        $this->assign('id', $id);
        $this->assign('info', $info);
        $this->display();
    }

    public function taskDetail() {
        $id = I('id', 0, 'intval');
        $info = D('Admin/Task')->getTaskDetail($id);

        $this->assign('id', $id);
        $this->assign('info', $info);
        $this->display();
    }

    public function recycle() {
        $this->_recycle('task','id');
    }

}
        