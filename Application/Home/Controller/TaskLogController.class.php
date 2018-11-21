<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description
 * @Author   
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           ${DATE} ${TIME}
 * @CreateBy       ${PRODUCT_NAME}
 */

namespace Home\Controller;
use Common\Controller\CommonController;
use Common\Controller\UserCommonController;
class TaskLogController extends UserCommonController{

    /**
     * @desc 我的任务
     */
    public function getTaskLog(){
        $type = I('type', 5, 'intval');
        $this->type = $type;
        $this->display();
    }

    /**
     * @desc 获取接单信息
     * @param  $user_id
     * @return mixed
     */
    public function ajax_getTaskLog(){
        /*where 接任务条件查找  0待提交 1审核中 2不合格 3已完成 default全部 */
        $type = I('type', 5, 'intval');
        $this->type = $type;
        switch ($type) {
            case 0:
            case 1:
            case 2:
            case 3:
                $where['l.valid_status'] = array('eq', $type);
                break;
            default:
                $where['l.valid_status'] = array('neq', 4);
                break;
        }
        $where['l.user_id'] = UID;
        $field = 'l.id, l.task_id, l.task_name,l.valid_time, l.valid_status, t.price, c.category_name, c.category_img';
        $taskLogModel = D('Home/TaskLog');
        $taskLogInfo = $taskLogModel->getTaskLog($where,$field);
        $this->list = $taskLogInfo['list'];
        $this->display();
    }

    /**
     * @desc 丢弃任务
     * @param $id  taskLog 主键
     * @return mixed
     */
    public  function delTaskLog(){
        $id = I('id', 0, 'intval');
        $taskLogModel = D('Home/TaskLog');
        $result  = $taskLogModel->delTaskLog($id);
        if($result)
        {
            $this->ajaxReturn(V(1,'删除成功'));
        }
        else{
            $this->ajaxReturn(V(0, '删除失败'));
        }
    }

    /**
     * @desc  任务不合格详情
     * @param  tasklog_id
     * @return mixed
     */
    public function taskLogFail(){
        $taskLog_id = I('id', 0, 'intval');
        $taskLogModel = D('Home/TaskLog');
        $chatModel = D('Home/Chat');
        $taskLogInfo = $taskLogModel->field('id, user_id, task_id, valid_pic')->where('id = '.$taskLog_id)->find();
        $taskLogInfo['userChat']  = $chatModel ->field('content')->where('user_id  = '.$taskLogInfo['user_id'].'  and task_log_id =  '.$taskLogInfo['id'])->select();
        $taskLogInfo['taskChat']  = $chatModel ->field('content')->where('task_user_id = '.$taskLogInfo['user_id'].'  and task_log_id =  '.$taskLogInfo['id'])->select();
        if(strpos($taskLogInfo['valid_pic'], ',')  !== false){
            $taskLogInfo['valid_pic']   =   explode(',',$taskLogInfo['valid_pic']);
        } else {
            $taskLogInfo['valid_pic']   =    array($taskLogInfo['valid_pic']);
        }
        $this->assign('taskLogInfo',$taskLogInfo);
        $this->display();
    }

    /**
     * @desc  放弃任务
     * @param tasklog_id
     * @return mixed
     */
    public function giveUpTask(){
        $taskLog_id = I('id', 0, 'intval');
        $task_id = I('task_id', 0, 'intval');
        $taskModel = D('Home/Task');
        $taskLogModel = D('Home/TaskLog');
        M()->startTrans();
        $taskLogRes = $taskLogModel->where('id = '.$taskLog_id)->save(array('valid_status' => 4));
        /*放弃任务 释放单子 数*/
        $taskRes = $taskModel->where('id = '.$task_id)->setInc('task_num');
        if($taskLogRes && $taskRes){
            M()->commit();
            $this->ajaxReturn(V(1, '成功'));
        }else{
            M()->rollback();
            $this->ajaxReturn(V(0, '失败'));
        }
    }

    /**
     *  任务详情
     */
    public function taskLogDetail() {
        $id = I('id', 0, 'intval');
        $info = D('Home/TaskLog')->getTaskLogDetail(array('l.id'=>$id));

        $this->assign('taskDetail', $info);
        $this->display();
    }

    /**
     * 重做
     * 返回新logid
     */
    public function reDoLog() {
        $log_id = I('log_id', 0 , 'intval');
        $new_id = D('Home/TaskLog')->reDoTaskLog($log_id);
        if ($new_id === false) {
            $this->ajaxReturn(V(0, '操作失败'));
        }
        else {
            $this->ajaxReturn(V(1, '操作成功',$new_id));
        }

    }
}