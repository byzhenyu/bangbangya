<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     投诉||申诉的model
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/14 0014 14:46
 * @CreateBy       PhpStorm
 */
namespace Home\Model;
use  Think\Model;
class ComplaintModel extends  Model{
    protected $insertFields = array('task_id', 'user_id', 'be_user_id', 'information','type','price');
    protected $_validate = array(
        array('task_id', 'require', '任务ID不能为空 ', 1, 'regex', 3),
        array('user_id', 'require', '用户ID不能为空 ', 1, 'regex', 3),
        array('be_user_id', 'require', '被投诉|申诉ID不能为空 ', 1, 'regex', 3),
        array('information', '0,255', '您输入的内容过长，超过了 255 个字符数限制', 1, 'length', 3),
    );
    protected function _before_insert(&$data, $option){
        $data['add_time'] = NOW_TIME;
    }
}