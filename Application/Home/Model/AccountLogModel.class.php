<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     资金变动记录表
 * @Author         (jicy)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/8 0008 8:31
 * @CreateBy       PhpStorm
 */

namespace Home\Model;
use Think\Model;
class AccountLogModel extends Model {
    protected $selectFields = array('log_id','user_id','user_money', 'change_time', 'change_desc','change_type','order_sn');

    public function getAccountLog($where= [], $field='', $order='log_id desc') {
        if (!$field) {
            $field = $this->selectFields;
        }
        $count = $this->where($where)->count();
        $page = get_page($count, 10);
        $info = $this->where($where)->field($field)->order($order)->limit($page['limit'])->select();

        return $info;
    }
}