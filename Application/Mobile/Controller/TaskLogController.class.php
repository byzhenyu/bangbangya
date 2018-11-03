<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description      接单控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/1 0001 13:12
 * @CreateBy       PhpStorm
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class TaskLogController extends CommonController {
    public function _initialize() {
        $this->TaskLogModel = D("Home/TaskLog");
    }
     /**
     * @desc  接单
     * @param
     * @return mixed
     */
     public function takeTask(){
         $id =  I('id', 0, 'intval');
         $TaskLogModel = D('Home/TaskLog');
         if($id >0) {
             $where['l.id'] = $id;
             $TaskLogInfo = $TaskLogModel->getTaskLogDetail($where);
         }else{
             $TaskLogInfo = [];
         }
         if (IS_POST) {
             $data = json_decode(I('data', '', 'strip_tags'),true);
             $data['user_id'] = UID;
             if ($id > 0 ) {
                 if ($TaskLogModel->save() !== false) {
                     $this->ajaxReturn(V(2, '修改成功'));
                 }
             } else {
                 /*判断是否重新激活订单*/
                  $result  = activateTask(UID, $id);
                  if($result){
                      $this->ajaxReturn(V(1, '订单重新激活成功'));
                  }
                 /*判断任务数量*/
                 $task_num = taskNum($data['task_id']);
                 if(!$task_num){
                     $this->ajaxReturn(V(3, '任务数量已经空了!请稍后再试!'));
                 }
                 if($TaskLogModel->add($data) !== false)
                 {
                     $this->ajaxReturn(V(1, '接单成功'));
                 }
             }
             $this->ajaxReturn(V(0, $TaskLogInfo->getDbError()));
         }
         P($TaskLogInfo);
         $this->assign('$TaskLogInfo',$TaskLogInfo);
         $this->display();
     }
     /**
     * @desc 获取接单信息
     * @param  $user_id
     * @return mixed
     */
     public function getTaskLog()
     {
          /*where 接任务条件查找  0待提交 1待审核 2不合格 3已完成 default全部 */
          $type = I('type', 4, 'intval');
          switch ($type) {
             case '0':
                 $where['l.valid_status'] = 0;
                 break;
             case '1':
                 $where['l.valid_status'] = 1;
                 break;
             case '2':
                 $where['l.valid_status'] = 2;
                 break;
             case '3':
                 $where['l.valid_status'] = 3;
                 break;
             default:
                 break;
          }
          $where['l.user_id'] = UID;
          $field = 'l.task_id, l.task_name,l.valid_time, l.valid_status, t.price, c.category_name, c.category_img';
          $taskLogModel = D('Home/TaskLog');
          $taskLogInfo = $taskLogModel->getTaskLog($where,$field);
          p($taskLogInfo);
//          exit;
          $this->assign('taskLogInfo', $taskLogInfo);
          $this->display();
     }
     /**
     * @desc 丢弃任务
     * @param $task_id
     * @return mixed
     */
     public  function delTaskLog()
     {
         $id = I('id', 0, 'intval');
         $result  = $this->TaskLogModel->delTaskLog($id);
         if($result)
         {
             $this->ajaxReturn(V(1,'上传验证成功'));
         }else{
             $this->ajaxReturn(V(0, $this->TaskLogModel->getDbError()));
         }
     }
    /**
     * @desc 我的任务-上传验证
     * @param $task_id
     * @return mixed
     */
     public function taskVerify()
    {
        $data = json_decode(I('data', '', 'strip_tags'),true);
        if(IS_POST)
        {
            $taskLogRes  = $this->TaskLogModel->save($data);
            $taskRes = $this->TaskLogModel->changeTaskStatus($data['id'], 1);
        }
        if($taskRes && $taskLogRes) {
            $this->ajaxReturn(V(1,'上传验证成功'));
        }else{
            $this->ajaxReturn(V(0, $this->TaskLogModel->getDbError()));
        }
     }
}

