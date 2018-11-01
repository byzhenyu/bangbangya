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
             $TaskLogInfo = $TaskLogModel->getTaskLogDetail($where, $field);
         }else{
             $TaskLogInfo = [];
         }
         if (IS_POST) {
             $data = json_decode(I('data', '', 'strip_tags'),true);
             $data['user_id'] = UID;
             if ($id > 0 ) {
                 if ($TaskLogModel->save() !== false) {
                     $this->ajaxReturn(V(1, '修改成功'));
                 }
             } else {
                 if($TaskLogModel->add($data) !== false)
                 {
                     $this->ajaxReturn(V(1, '接单成功'));
                 }
             }
             $this->ajaxReturn(V(0, $TaskLogInfo->getDbError()));
         }
         P($TaskLogInfo);
         exit;
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
          $field = 'l.task_id, l.task_name, l.valid_status, t.price, c.category_name, c.category_img';
          // p($where);
          // exit;
          $taskLogModel = D('Home/TaskLog');
          $taskLogInfo = $taskLogModel->getTaskLog($where,$field);
          p($taskLogInfo);
          exit;
          $this->assign('taskLogInfo', $taskLogInfo);
          $this->display();
     }
}

