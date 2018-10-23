<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author
 * @Date           2018/9/6
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;

/**
 * 用户测试结果控制器
 */
class UserTestResultController extends CommonController {
    public function listRank() {
        $data = D('Admin/UserTestResult')->getRankList();
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }

    public function listUserTestResult() {
        $user_id = I('user_id');
        $where['u.user_id'] = $user_id;

        $keyword = I('keyword');
        if ($keyword) {
            $where['utr.words'] = ['like', "%$keyword%"];
        }

        $selected_result = I('selected_result');
        if ($selected_result !== '') {
            $where['utr.test_result'] = $selected_result;
        }

        $field = 'utr.*, u.user_name, cc.class_name, cu.unit';
        $order = 'utr.test_time desc';
        $data = D('Admin/UserTestResult')->getUserTestResultList($where, $field, $order);

        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }

    public function del() {
        $this->_del('UserTestResult', 'result_id');
    }
}
