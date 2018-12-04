<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description     粉丝管理
 * @Author          jicy 510434563
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;

class FansModel extends Model {


    /**
     * 粉丝列表
     * @param array $where
     * @param string $field
     * @param string $order
     * @return array
     */
    public function getFansList($where = [], $field = '', $order = 'f.add_time desc') {
        if ($field == '') {
            $field = 'f.*,u.nick_name';
        }
        $count = $this->alias('f')->where($where)->count();
        $page = get_page($count);
        $list = $this->alias('f')
            ->join('__USER__ u on f.user_id = u.user_id')
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

}
