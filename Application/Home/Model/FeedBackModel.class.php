<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     意见反馈类
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/8 0008 18:43
 * @CreateBy       PhpStorm
 */
namespace Home\Model;
use Think\Model;
class FeedBackModel extends Model{
    protected $insertFields = array('comment', 'user_id', 'mobile', 'create_time');
    protected $_validate = array(

        array('comment', 'require', '反馈信息不能为空！', 1, 'regex', 3),
        array('comment', '1,255', '反馈信息长度在1-255字符之间', 1, 'length', 3)
    );
    protected function _before_insert(&$data, $option){
        $data['create_time'] = NOW_TIME;
        $data['user_id'] = UID;
    }

}