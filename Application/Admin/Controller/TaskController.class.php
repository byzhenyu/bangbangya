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

            if($taskModel->create($data, 5) !== false) {

                $res = $taskModel->save();

                if ($res === false) {
                    $this->ajaxReturn(V(0, '审核失败'));
                }

                //推送
                $this->ajaxReturn(V(1,'审核成功'));
            } else {
                $this->ajaxReturn(V(0,$taskModel->getError()));
            }
        }
        $info = $taskModel->getTaskDetail($id);
        p($info);
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

    public function del() {
        $this->_del('task', 'id');
    }
}
        