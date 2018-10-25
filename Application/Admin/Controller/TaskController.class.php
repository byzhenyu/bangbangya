<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
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
        $data = D('Admin/Task')->getTaskList();
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    
    public function editTask(){
        $id = I('id');
        $taskModel = D('Admin/Task');
        
        if (IS_POST) {
            if ($taskModel->create() === false) {
                $this->ajaxReturn(V(0, $taskModel->getError()));
            }
            if ($id) {
                if ($taskModel->save() !== false) {
                    $this->ajaxReturn(V(1, '编辑成功'));
                }
            } else {
                if ($taskModel->add() !== false) {
                    $this->ajaxReturn(V(1, '添加成功'));
                }
            }
            $this->ajaxReturn(V(0, $taskModel->getDbError()));
        }
        
        $info = $taskModel->find($id);
        $this->assign('info', $info);
        $this->display();
    }

    public function recycle()
    {
        $this->_recycle('Task','id');
    }

    public function del(){
        $this->_del('Task', 'id');
    }
}
        