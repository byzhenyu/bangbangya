<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     教师管理
 * @Author         (jicy QQ:510434563)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/08/28
 * @CreateBy       ${PRODUCT_NAME}
 */

namespace Admin\Controller;


class TeacherController extends CommonController
{
    /**
     * 学校
     */
    public function listTeacher(){
        $school_id = I('school_id',0,'intval');
        $agent_id = I('agent_id',0,'intval');
        $keyword = I('keyword', '', 'trim');
        $where = array();
        if ($school_id > 0) {
            $where['school_id'] = array('eq', $school_id);
            $this->school_id = $school_id;
        }
        if ($agent_id > 0) {
            $where['agent_id'] = array('eq', $agent_id);
            $this->agent_id = $agent_id;
        }
        if ($keyword) {
            $where['school_name'] = array('like','%'.$keyword.'%');
        }
        $schoolInfo = M('School')->where(array('status'=>1))->field('id,school_name')->select();
        $agentInfo = M('Agent')->where(array('status'=>1))->field('id,agent_name')->select();
        $teacherInfo = D('Admin/Teacher')->getTeacherByPage($where);

        $this->assign('agent_list', $agentInfo);
        $this->assign('school_list',$schoolInfo);
        $this->info = $teacherInfo['info'];
        $this->page = $teacherInfo['page'];
        $this->keyword = $keyword;
        $this->display();
    }

    /**
     * 编辑
     */
    public function editTeacher() {
        $id = I('id', 0, 'intval');
        $model = D('Admin/Teacher');
        if (IS_POST) {
            $data = I('post.', '');
            if ($model->create($data)!== false) {
                if ($id > 0) {
                    $res = $model->save();
                } else {
                    $res = $model->add();
                }
                if ($res ===false) {
                    $this->ajaxReturn(V(0,'操作失败'));
                } else {
                    $this->ajaxReturn(V(1,'操作成功'));
                }
            } else {
                $this->ajaxReturn(V(0, $model->getError()));
            }
        }
        $info = M('Teacher')->where(array('id'=>$id))->find();
        $this->info = $info;
        $this->display();
    }
    //详情
    public function detailTeacher() {
        $id = I('id',0,'intval');
        $where['id'] = array('eq', $id);
        $info = D('Admin/Teacher')->getTeacherDetail($where);
        $this->info = $info;
        $this->display();
    }
    // 删除方法
//    public function del(){
//        $this->_del('Teacher', 'id');  //调用父类的方法
//    }

    public function recycle() {
        $this->_recycle('Teacher','id');
    }
    // 改变可用状态
    public function changeDisabled(){
        $this->_changeDisabled('Teacher');  //调用父类的方法
    }
}