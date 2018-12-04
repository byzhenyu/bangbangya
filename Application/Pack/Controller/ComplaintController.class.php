<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     投诉 申诉 控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/14 0014 14:44
 * @CreateBy       PhpStorm
 */
namespace Pack\Controller;
use Common\Controller\CommonController;
class ComplaintController extends CommonController{
    public function _initialize()
    {
        $this->Complaint = D("Home/Complaint");
    }
    /**
    * @desc  投诉 添加
    * @param  $data   array('task_id','user_id','be_user_id','information','type','price')
    * @return mixed
    */
    public function addComplaint(){
         $data = I('post.');
         $taskLogModel = D('Home/TaskLog');
         $taskLogInfo = $taskLogModel->field('task_id, user_id, task_price')->where('id = '.$data['tid'])->find();
         unset($data['tid']);
         $complaintInfo = $this->Complaint->where('user_id ='.UID.' and  be_user_id = '.$taskLogInfo['user_id'].' and task_id = '.$taskLogInfo['task_id'].' and audit_status = 0')->find();
         if(!$complaintInfo){
             $data['user_id'] = UID;
             $data['be_user_id'] = $taskLogInfo['user_id'];
             $data['task_id'] = $taskLogInfo['task_id'];
             $data['price'] = $taskLogInfo['task_price'];
             $res = $this->Complaint->add($data);
         }else{
             $this->ajaxReturn(V(2, '您已经投诉,请勿重复提交!'));
         }
         if($res){
             $this->ajaxReturn(V(1, '投诉成功!'));
         }else{
             $this->ajaxReturn(V(0, $this->Complaint->getError()));
         }
    }
    /**
     * @desc  申诉添加
     * @param  taskLog_id
     * @param  task_id
     * @return mixed
     */
    public function appeal(){
        $data = I('post.', 4);
        $taskModel = D('Home/Task');
        $taskInfo = $taskModel->field('user_id, price')->where('id = '.$data['task_id'])->find();
        $complaintInfo = $this->Complaint->where('user_id ='.$data['user_id'].' and  be_user_id = '.$taskInfo['user_id'].' and task_id = '.$data['task_id'])->find();
        if(!$complaintInfo){
            $data['be_user_id'] = $taskInfo['user_id'];
            $data['price'] = $taskInfo['price'];
            $res = $this->Complaint->add($data);
        }else{
            $this->ajaxReturn(V(2, '您已经申诉过了,请勿重复提交!'));
        }
        if($res){
            $this->ajaxReturn(V(1, '申诉成功!'));
        }else{
            $this->ajaxReturn(V(0, $this->Complaint->getError()));
        }
    }

}