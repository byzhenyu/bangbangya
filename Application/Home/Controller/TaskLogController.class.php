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
     * @desc 获取接单信息
     * @param  $user_id
     * @return mixed
     */
    public function getTaskLog()
    {
        /*where 接任务条件查找  0待提交 1审核中 2不合格 3已完成 default全部 */
        $type = I('type', 5, 'intval');
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

        if (IS_POST) {
            $this->ajaxReturn(V(1, '接单记录', $taskLogInfo['list']));

        }
        $this->assign('type', $type);
        $this->assign('taskLogInfo', $taskLogInfo);
        $this->display();
    }
}