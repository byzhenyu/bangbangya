<?php
/**
 * 学习设置控制器
 */
namespace Admin\Controller;
use Think\Controller;
class UserSettingController extends CommonController {

    //显示学生设置
    public function listUserSetting(){
        $user_id = I('user_id', 0, 'intval');
        $keyword = I('keyword', '', 'trim');
        $model = D('Admin/UserSetting');
        if($keyword){
            $where['u.nickname'] = array('like', '%'.$keyword.'%');
        }
        if($user_id) $where['u.user_id'] = $user_id;
        $list = $model->getUserSettingList($where);
        $this->keyword = $keyword;
        $this->list = $list['info'];
        $this->page = $list['page'];
        $this->display();
    }

    public function del(){
        $this->_del('UserSetting', 'id');
    }
}