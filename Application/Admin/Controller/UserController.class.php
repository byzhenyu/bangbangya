<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description    用户管理
 * @Author         (jicy QQ:510434563)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           ${DATE} ${TIME}
 * @CreateBy       ${PRODUCT_NAME}
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
            $where['nick_name|user_id'] = array('like','%'.$keyword.'%');
        }

        $field = 'user_id, nick_name,total_money, register_time, disabled, invitation_code';
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

    //分销记录
    public function getInvitationList() {
        $keyword = I('keyword', '');
        if ($keyword) {
            $where['nick_name'] = array('like','%'.$keyword.'%');
        }
        $where['invitation_num'] = array('gt', 0);
        $data = D('Admin/User')->getUsersListByPage($where);
        $this->assign('userslist', $data['userslist']);
        $this->assign('page', $data['page']);
        $this->assign('keyword', $keyword);
        $this->display();
    }

    public function invitationDetail() {
        $user_id = I('user_id', 0, 'intval');
        $where['invitation_uid'] = array('eq', $user_id);
        $data = D('Admin/User')->getUsersListByPage($where);
        $this->assign('userslist', $data['userslist']);
        $this->assign('page', $data['page']);
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
        $this->_recycle('user');
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