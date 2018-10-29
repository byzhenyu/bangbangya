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
            $data = I('post.');
            $newDate  = D('Admin/Task')->timeToTimestamp($data); /*处理任务添加时间转换为时间戳*/
            if ($taskModel->create($newDate) === false) {
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
        $info = $taskModel->getTaskDetail($id);
        $categoryList = D('Admin/task_category')->field('id, category_name, category_img')->where('status = 1')->select();
        /*insert into categoryid  to set input selected*/
        $cat_ids = $info['category_id'];
        // $cat_ids = array();
        // p($categoryList);
        $this->catListStr = json_encode($categoryList);
        $this->cat_ids = json_encode($cat_ids);
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
        