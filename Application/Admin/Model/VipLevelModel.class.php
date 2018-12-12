<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description     合作商管理
 * @Author          jicy 510434563
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;

class VipLevelModel extends Model {
    protected $insertFields = array('vip_name', 'vip_price', 'vip_type', 'order_fee', 'withdraw_fee', 'add_time', 'type', 'status');
    protected $updateFields = array('id','vip_name', 'vip_price', 'vip_type', 'order_fee', 'withdraw_fee', 'add_time', 'type', 'status');
    protected $selectFields = array('id', 'vip_name', 'vip_price', 'vip_type', 'order_fee', 'withdraw_fee', 'add_time', 'type', 'status');
    protected $_validate = array(
        array('type', array(0,1,2,3,4), '合作商级别非法', 1, 'in', 3),
        array('vip_price', 'require', '请输入购买金额', 1, 'regex', 3),
        array('order_fee', 'require', '请输入交易手续费比例', 1, 'regex', 3),
        array('withdraw_fee', 'require', '请输入提现手续费比例', 1, 'regex', 3),


    );

    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){

        $data['add_time'] = NOW_TIME;

    }
    //修改操作前的钩子操作
    protected function _before_update(&$data, $option){

    }

    /**
     * vip管理
     * @param array $where
     * @param string $field
     * @param string $order
     * @return array
     */
    public function getVipLevelList($where = [], $field = '', $order = 'add_time desc') {
        if ($field == '') {
            $field = $this->selectFields;
        }
        $count = $this->where($where)->count();
        $page = get_page($count);
        $list = $this
            ->field($field)
            ->where($where)
            ->limit($page['limit'])
            ->order($order)
            ->select();

        return array(
            'info'=>$list,
            'page'=>$page['page']
        );

    }

    public function getVipLevelDetail($id = 0) {
        $info = $this->where(array('id'=>$id))->find();
        return $info;
    }
    //获取vip手续费
    public function getVipLevelFee($where=[]) {
        $info = $this->where($where)->find();
        return $info;
    }

}
