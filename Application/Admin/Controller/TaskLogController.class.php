<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description    接单管理
 * @Author         
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;
class TaskLogController extends CommonController {

    public function listTaskLog(){
        $keyword = I('keyword', '');
        if ($keyword) {
            $where['log.title'] = array('like','%'.$keyword.'%');
        }
        $field = 'log.*,u.nick_name';
        $data = D('Admin/TaskLog')->getTaskLogList($where,$field);

        $this->assign('list', $data['info']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    
    public function editTaskLog(){
        $id = I('id');
        $taskModel = D('Admin/TaskLog');
        if (IS_POST) {
            $data = I('post.', '');
            if($taskModel->create($data,5) !==false) {

                $res = $taskModel->save($data);

                if ($res ===false) {
                    $this->ajaxReturn(V(0, '审核失败'));
                }

                //推送
                $this->ajaxReturn(V(1,'审核成功'));
            } else {
                $this->ajaxReturn(V(0,$taskModel->getError()));
            }
        }
        $info = $taskModel->getTaskLogDetail($id);
        //p($info);
        $this->assign('id', $id);
        $this->assign('info', $info);
        $this->display();
    }

    public function taskLogDetail() {
        $id = I('id', 0, 'intval');
        $info = D('Admin/Task')->getTaskDetail($id);

        $this->assign('id', $id);
        $this->assign('info', $info);
        $this->display();
    }
    //排行
    public function getTaskRank() {
        $datemin = I('datemin', 0, 'strtotime');
        $datemax = I('datemax', 0, 'strtotime');
        //条件查询
        $where = array();
        if (!empty($datemin) && !empty($datemax)) {
            $where['add_time'] = array('between', array($datemin, $datemax));
        } elseif (!empty($datemin)) {
            $where['add_time'] = array('egt', $datemin);
        } elseif (!empty($datemax)) {
            $where['add_time'] = array('elt', $datemax);
        }

        $data = D('TaskLog')->getTaskRankByTime($where);

        $this->assign('list', $data['info']);
        $this->assign('page', $data['page']);
        $this->assign('count', $data['count']);
        $this->display();
    }
    public function recycle() {
        $this->_recycle('task_log','id');
    }

    public function del() {
        $this->_del('task_log', 'id');
    }
}
        