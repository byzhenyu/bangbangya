<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     消息查看类
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/16 0016 16:58
 * @CreateBy       PhpStorm
 */
namespace Home\Model;
use  Think\Model;
class PushModel extends Model{
    protected $insertFields = array('title','content1','content2','content3','content4','user_id','add_time');
    protected $selectFields = array('id','title','content1','content2','content3','content4','user_id','add_time');
    protected function _before_insert(&$data, $option){
        $data['add_time'] = NOW_TIME;
    }
    public function getPush($where, $field = null, $sort = 'add_time Desc'){
         if(is_null($field)){
             $field = $this->selectFields;
         }
         $count = $this->where($where)->count();
         $page = get_page($count,4);
         $list = $this->field($field)->where($where)->limit($page['limit'])->order($sort)->select();
        return array(
            'list'=>$list,
            'page'=>$page['page']
        );
    }
}