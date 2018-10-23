<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description    教师管理
 * @Author         (jicy QQ:510434563)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           ${DATE} ${TIME}
 * @CreateBy       ${PRODUCT_NAME}
 */
namespace Agent\Controller;
use Common\Controller\AgentCommonController;
use Home\Model\UserModel;
use Think\Verify;

class TeacherController extends AgentCommonController{

    /**
     * 教师
     */
    public function listTeacher() {

        $keyword = I('keyword', '', 'trim');
        $where['t.agent_id'] = array('eq', AGENT_ID);
        $school_id = I('school_id', 0, 'intval');
        if ($keyword) {
            $where['teacher_name'] = array('like','%'.$keyword.'%');
            $this->keyword = $keyword;
        }
        if ($school_id > 0) {
            $where['t.school_id'] = array('eq', $school_id);
            $this->school_id = $school_id;
        }
        $where['t.status'] = 1;
        $teacherInfo = D('Teacher')->listTeacherByPage($where);
        $schoolInfo = M('School')->where(array('status'=>1))->field('id,school_name')->select();
        $this->assign('schoolInfo',$schoolInfo);

        $this->info = $teacherInfo['info'];
        $this->page = $teacherInfo['page'];
        $this->display();
    }

    /**
     * 添加修改教师
     */
    public function editTeacher() {

        // 获取用户的id
        $teacher_id = I('id', 0, 'intval');
        $usersModel = D('Admin/User');
        $teacherModel = D('Agent/Teacher');
        if(IS_POST){
            $data = I('post.');
           // p($data);die();
            if ($teacher_id > 0){
                $data['user_id'] = M('Teacher')->where(array('id' => $teacher_id))->getField('user_id');
                if($usersModel->create($data, 12)){
                    $save = $usersModel->save();
                    if ($save !== false) {
                        $data['teacher_name'] =$data['true_name'] = $data['truename'];
                        $riderCreate = $teacherModel->create($data);
                        if ($riderCreate === false) {
                            $this->ajaxReturn(V(0, $teacherModel->getError()));
                        }
                        $riderSave = $teacherModel->save($data);
                        if($riderSave === false){
                            $this->ajaxReturn(V(0, '修改失败'));
                        }

                        $this->ajaxReturn(V(1, '保存成功'));
                    }
                    else{
                        $this->ajaxReturn(V(0, '操作错误'));
                    }
                }
                else{
                    $this->ajaxReturn(V(0, $usersModel->getError()));
                }
            } else {
                M()->startTrans();
                $data['user_type'] = 1;
                if($usersModel->create($data, 1)){
                    $user_id = $usersModel->add();
                    if ($user_id !== false) {
                        $data['user_id'] = $user_id;
                        $data['teacher_name'] =$data['true_name'] = $data['truename'];

                        $teacherCreate = $teacherModel->create($data);
                        if ($teacherCreate === false) {
                            M()->rollback();
                            $this->ajaxReturn(V(0, $teacherModel->getError()));
                        }
                        $teacherAdd = $teacherModel->add();
                        if($teacherAdd === false){
                            M()->rollback();
                            $this->ajaxReturn(V(0, '保存失败'));
                        }
                        M()->commit();
                        $this->ajaxReturn(V(1, '保存成功'));

                    }
                }
                else{
                    $this->ajaxReturn(V(0, $usersModel->getError()));
                }
            }
        }
        // 查询用户的基本信息
        $agent_info = D('Teacher')->where(array('id'=>$teacher_id))->find();

        if (!empty($agent_info)) {
            $this->user_info = D('User')->where('user_id = '.$agent_info['user_id'])->find();
        }
        //获取学校
        $schoolInfo = M('School')->where(array('status'=>1))->field('id,school_name')->select();
        //获取学段
        $periodInfo = M('CoursePeriod')->select();

        $this->assign('schoolInfo', $schoolInfo);
        $this->assign('periodInfo', $periodInfo);
        $this->assign('agent_info', $agent_info);
        $this->display();
    }

    public function recycle() {
        $id = I('id', 0);
        $result = V(0, '删除失败, 未知错误');
        if($id != 0){
            $where['user_id'] = array('in', $id);
            $data['status'] = 0;
            M()->startTrans();
            $userResult = M('User')->where($where)->save($data);
            $riderResult = M('Teacher')->where($where)->save($data);
            if($userResult !== false && $riderResult !== false){
                M()->commit();
                $result = V(1, '删除成功');
            }
            else{
                M()->rollback();
                $result = V(0, '删除失败');
            }
        }
        $this->ajaxReturn($result);
    }

    // 改变可用状态
    public function changeDisabled() {
        $user_id = I('user_id', 0, 'intval');

        $result = V(0,'修改失败');
        $disabled = M('User')->where(array('user_id'=>$user_id))->getField('disabled');
        if ($user_id != 0) {
            $where['user_id'] = array('eq', $user_id);
            $data['user_id'] = $user_id;
            $data['disabled'] = $disabled ? 0 : 1;

            M()->startTrans();
            $userResult = M('User')->where($where)->save($data);
            $riderResult = M('Teacher')->where($where)->save($data);
            if($userResult !== false && $riderResult !== false){
                M()->commit();
                $result = V(1, '修改成功');
            } else {
                M()->rollback();
                $result = V(0, '修改失败');
            }
        }
        $this->ajaxReturn($result);

    }

    // 删除图片
    public function delFile(){
        $this->_delFile();  //调用父类的方法
    }

    // 上传图片
    public function uploadImg(){
        $this->_uploadImg();  //调用父类的方法
    }

}