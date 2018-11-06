<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description     充值记录表
 * @Author         jicy 510434563
 * @Date           2018/11/6
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;
 
/**
 * 充值记录表模型
 */
class RechargeModel extends Model {
    protected $insertFields = array('id', 'user_id', 'recharge_money', 'recharge_type', 'add_time', 'pay_status', 'order_sn', 'trade_no', 'status');
    protected $updateFields = array('id', 'user_id', 'recharge_money', 'recharge_type', 'add_time', 'pay_status', 'order_sn', 'trade_no', 'status');

    protected $_validate = array(


    );

    public function getRechargeList($where = [], $field = '', $order = 'r.add_time desc') {
        if (!empty($field)) {
            $field = 'r.*,u.nick_name';
        }
        $count = $this->alias('r')->where($where)->count();
        $page = get_page($count);
        $list = $this->alias('r')
            ->join('__USER__ u on r.user_id = u.user_id')
            ->field($field)
            ->where($where)
            ->limit($page['limit'])
            ->order($order)
            ->select();
        return array(
            'list' => $list,
            'page' => $page['page']
        );
    }
    
}
        