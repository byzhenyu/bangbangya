<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description    粉丝管理
 * @Author         
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;
class FansController extends CommonController {

    public function listFans() {
        $user_id = I('user_id', 0, 'intval');
        $where['f.status'] = array('eq',1);
        $where['u.status'] = array('eq', 1);
        $where['f.fans_user_id'] = array('eq', $user_id);
        $data = D('Admin/Fans')->getFansList($where);

        $this->assign('list', $data['info']);
        $this->assign('page', $data['page']);
        $this->display();
    }

    public function recycle() {
        $this->_recycle('vip_level','id');
    }

    public function del() {
        $this->_del('vip_level', 'id');
    }
}
        