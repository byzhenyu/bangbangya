<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     用户资金明细  Model
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/12/6 0006 13:52
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;
use Think\Model;
class AccountLogModel extends Model{
    protected $selectFields = array('log_id', 'user_id', 'user_money', 'change_time', 'change_desc', 'change_type');
    /**
    * @desc 用户资金明细
    * @param  user_id
    * @return mixed
    */
    public function userMoneyDetail($user_id = 0, $filed = null , $sort = 'change_time Desc'){
        if($user_id == 0){
            return V('0', '参数错误!');
        }
        if(is_null($filed)){
            $field = $this->selectFields;
        }
        $where['user_id'] = $user_id;
        $count = $this->where($where)->count();
        $page = get_page($count);
        $list = $this->field($field)->where($where)->limit($page['limit'])->order($sort)->select();
        return array(
           'list'=> $list,
           'page'=> $page['page']
        );
    }
}