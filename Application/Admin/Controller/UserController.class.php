<?php
/**
 * 学生管理类
 */
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController {
    //账号列表
    public function listUsers(){
        $keyword = I('keyword', '');
        $userModel = D('Admin/User');
        if ($keyword) {
            $where['u.mobile|u.user_name'] = array('like','%'.$keyword.'%');
        }
        //查询所有的学生
        $field = 'user_id, user_name, register_time, expire_time, is_used, disabled';
        $data = $userModel->getUsersListByPage($where, $field);
        $this->userslist = $data['userslist'];
        $this->page = $data['page'];
        $this->display();
    }

    //学生列表
    public function student(){
        $keyword = I('keyword', '');
        $userModel = D('Admin/User');
        if ($keyword) {
            $where['u.mobile|u.user_name'] = array('like','%'.$keyword.'%');
        }
        $where['u.is_used'] = 1;
        $where['u.user_type'] = 0;
        $field = 'u.user_id, u.user_name, u.used_time, u.expire_time, u.truename, u.school_name, u.disabled, t.teacher_name';
        //查询所有的学生
        $data = $userModel->getUsersList($where, $field);
        $this->userslist = $data['userslist'];
        $this->page = $data['page'];
        $this->display();
    }

    /**
     * 学生详情
     */
    public function userDetail(){
        $user_id = I('user_id', 0, 'intval');
        $where['user_id'] = $user_id;
        $userModel = D('Admin/User');
        $userInfo = $userModel->getUserInfo($where);
        $this->userInfo = $userInfo;
        $this->display();
    }

    /**
     *学生启用，禁用方法
     */
    public function changeDisabled(){
        $user_id = I('user_id', 0, 'intval');
        $updateInfo = D('Admin/User')->changeDisabled($user_id);
        $this->ajaxReturn($updateInfo);
    }

    public function del() {
        $id = I('id', 0);
        $result = V(0, '删除失败, 未知错误');
        if($id != 0){
            $where['user_id'] = array('in', $id);
            $data['status'] = 0;
            if( M('User')->data($data)->where($where)->save() !== false){
                $result = V(1, '删除成功');
            }
        }
        $this->ajaxReturn($result);
    }
}