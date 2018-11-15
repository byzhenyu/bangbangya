<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     任务信息交流留言板
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/13 0013 16:15
 * @CreateBy       PhpStorm
 */

namespace Home\Model;
use  Think\Model;
class ChatModel extends  Model{
    protected $insertFields = array('user_id', 'task_user_id', 'task_id', 'content');
    protected $updateFields = array('id','user_id', 'task_user_id', 'task_id', 'content','status','sort');
    protected $selectFields = array('id','user_id', 'task_user_id', 'task_id', 'content','status','sort');
    protected $_validate = array(
        array('user_id', 'require', '用户id不能为空 ', 1, 'regex', 3),
        array('task_user_id', 'require', '发布任务用户id不能为空 ', 1, 'regex', 3),
        array('task_id', 'require', '任务id不能为空 ', 1, 'regex', 3),
        array('content', '0,255', '您输入的内容过长，超过了 255 个字符数限制', 1, 'length', 3),
    );
    protected function _before_insert(&$data, $option){
        $data['add_time'] = NOW_TIME;
    }
    public function getChat($where = [] , $field = null, $sort = 'add_time Desc'){
           $list = $this->field($field)->where($where)->order($sort)->select();
           return $list;
    }
}