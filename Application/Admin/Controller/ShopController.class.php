<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author         
 * @Date           2018/10/24
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;

/**
 * 店铺控制器
 */
class ShopController extends CommonController {

    public function shopList()
    {
        $keyword = I('keyword', '');
        $ShopModel = D('Admin/Shop');
        if ($keyword) {
            $where['shop_name'] = array('like','%'.$keyword.'%');
        }
        $data = $ShopModel->getShopList($where);
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    public function shopDetail() {
        $user_id = I('user_id', 0, 'intval');
        $where['user_id'] = $user_id;
        $info = D('Shop')->getShopDetail($where);

        $this->assign('info', $info);
        $this->display();
    }
    public function del() {
        $this->_del('Shop', 'id');
    }
}
        