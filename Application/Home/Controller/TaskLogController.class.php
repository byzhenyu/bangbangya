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
            $this->ajaxReturn(V(0, $this->TaskLogModel->getDbError()));
        }
    }
}