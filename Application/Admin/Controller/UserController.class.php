<?php
/**
 * 学生管理类
 */
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController {
    /**
     * 列表
     */
    public function listUsers(){
        $keyword = I('keyword', '');
        $userModel = D('Admin/User');
        if ($keyword) {
            $where['u.nick_name|u.user_name'] = array('like','%'.$keyword.'%');
        }
        //查询所有的学生
        $field = 'user_id, nick_name,total_money, register_time, disabled';
        $data = $userModel->getUsersListByPage($where, $field);
        $this->userslist = $data['userslist'];
        $this->page = $data['page'];
        $this->display();
    }

    /**
     * 编辑
     */
    public function editUsers() {
        $user_id = I('user_id',0,'intval');
        if (IS_POST) {

        }
        $this->assign('user_id', $user_id);
        $this->display();
    }

    /**
     * 详情
     */
    public function userDetail() {
        $user_id = I('user_id', 0, 'intval');
        $where['user_id'] = $user_id;
        $userModel = D('Admin/User');
        $userInfo = $userModel->getUserInfo($where);
        $this->userInfo = $userInfo;
        $this->display();
    }

    /**
     *禁用方法
     */
    public function changeDisabled() {
        $user_id = I('user_id', 0, 'intval');
        $updateInfo = D('Admin/User')->changeDisabled($user_id);
        $this->ajaxReturn($updateInfo);
    }


    public function recycle() {
        $this->_recycle('User');
    }

    // 删除图片
    public function delFile() {
        $this->_delFile();
    }

    // 上传图片
    public function uploadImg() {
        $this->_uploadImg();
    }
}