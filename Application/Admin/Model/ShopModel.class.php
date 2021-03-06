<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author
 * @Date           2018/10/24
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;

/**
 * 店铺模型
 */
class ShopModel extends Model {
    protected $insertFields = array('id', 'user_id', 'shop_name', 'shop_img', 'shop_accounts', 'top_time', 'shop_type', 'partner_time', 'add_time');
    protected $updateFields = array('id', 'user_id', 'shop_name', 'shop_img', 'shop_accounts', 'top_time', 'shop_type', 'partner_time', 'add_time');
    protected $selectFields = array('id', 'user_id', 'shop_name', 'shop_img', 'shop_accounts', 'top_time', 'shop_type', 'partner_time', 'add_time');
    public function getShopList($where = [], $field = '', $order = '') {
        $count = $this->where($where)->count();
        
        $page = get_page($count);
        $list = $this->field($field)
               ->where($where)
               ->limit($page['limit'])
               ->order($order)
               ->select();
        return array(
            'list' => $list,
            'page' => $page['page']
        );
    }

    //店铺详情
    public function getShopDetail($where) {
        $info = $this->alias('s')
                ->join('__USER__ as u on u.user_id  = s.user_id','LEFT')
                ->where($where)
                ->find();
        return $info;
    }

    //更新订单数量
    public function updateTaskNum($where=[], $data=[]) {
        $res = $this->where($where)->save($data);

        if ($res ===false) {
            return false;
        } else {
            return true;
        }
    }

}
