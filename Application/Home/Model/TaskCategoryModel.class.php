<?php
/**
 * /**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description    任务分类
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/31 0031 15:22
 * @CreateBy       PhpStorm
 */
namespace Home\Model;
use Think\Model;
class TaskCategoryModel extends Model{
    protected $selectFields = array('id','category_name','category_img', 'category_explain');
    /**
     * 任务类型
     * @param $where
     * @return array
     */
    public function getTaskCategory($where = [], $field = null, $order = 'id ASC') {
        if(is_null($field)){
             $field = $this->selectFields;
        }
        $where = array('status' => 1);
        $list = $this->field($field)
            ->where($where)
            ->order($order)
            ->select();
        return $list;
    }
    /**
    * @desc 任务类型说明
    * @param  $where
    * @return array
    */
    public function gettaskCategoryExplain($where = [], $field = '' , $sort = ' id ASC ')
    {
        if(is_null($field)){
            $field = $this->selectFields;
        }
        $list = $this->field($field)
            ->where($where)
            ->order($sort)
            ->select();
        return $list;
    }
}