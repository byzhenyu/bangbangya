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
        $where['t.status'] = array('eq', 1);
        $data = D('Admin/Task')->getTaskList($where);
        $this->assign('keyword', $keyword);
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    
    public function editTask(){
        $id = I('id');
        $taskModel = D('Admin/Task');
        if (IS_POST) {
            $data = I('post.', '');
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
                    $feeMoney = $data['total_price'] - ($data['price'] * $data['task_zong']);
                    if ($feeMoney > $total_money) {
                        M()->rollback();
                        $this->ajaxReturn(V(0, '用户余额不足'));
                    }
                    $res = $userModel->where(array('user_id'=>$data['user_id']))->setDec('total_money',$feeMoney);

                    if ($res === false) {
                        M()->rollback();
                        $this->ajaxReturn(V(0, '扣除手续费失败'));
                    }
                    $pushRes = D('Common/Push')->push("任务处理结果", $data['user_id'], '任务审核通过', '任务: '.$id, '代办', $data['audit_info']);
                    if ($pushRes['status'] == 0) {
                        M()->rollback();
                        $this->ajaxReturn($pushRes);
                    }

                } else if ($data['audit_status'] == 2) {
                    $pushRes = D('Common/Push')->push("任务处理结果", $data['user_id'], '任务审核未通过', '任务: '.$id, '代办', $data['audit_info']);
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
        