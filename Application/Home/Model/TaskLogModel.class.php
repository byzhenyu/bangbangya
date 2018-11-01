<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     接单任务 Model
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/1 0001 13:19
 * @CreateBy       PhpStorm
 */
namespace Home\Model;
use Think\Model;
class TaskLogModel extends  Model{
    protected $insertFields = array('user_id','task_id', 'task_name', 'valid_time','valid_info','valid_img','valid_text','valid_status','status','finish_time','add_time');
    protected $updateFields = array('task_id', 'task_name', 'valid_time','valid_info','valid_img','valid_text','valid_status','status','finish_time','add_time');
    protected $selectFields = array('task_id', 'task_name', 'valid_status');
    protected $_validate = array(
        array('valid_time', 'require', '任务有效日期不能为空', 1, 'regex', 3),
        array('valid_info','require','验证信息不能为空',1,'regex', 3)
    );
    public function getTaskLog($where = [], $field = '', $sort = 'add_time DESC'){
        $where['l.status'] = 1;
        $count = $this->alias('l')
                 ->join('__TASK__ as t on t.id = l.task_id', 'LEFT')
                 ->join('__TASK_CATEGORY__ as c on c.id = t.category_id')
                 ->field($field)
                 ->where($where)
                 ->count();
        $page = get_page($count,10);
        $list = $this->alias('l')
                ->join('__TASK__ as t on t.id = l.task_id', 'LEFT')
                ->join('__TASK_CATEGORY__ as c on c.id = t.category_id')
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
    /**
    * @desc 接单任务详情
    * @param  $where
    * @param  $field
    * @return mixed
    */
    public function  getTaskLogDetail($where = [], $field = null, $sort = ''){
        if(is_null($field)) $field = '';
         $taskInfo  = $this->alias('l')
                      ->join('__TASK__ as t on t.id = l.task_id', 'LEFT')
                      ->join('__TASK_CATEGORY__ as c on c.id = t.category_id')
                      ->field($field)
                      ->where($where)
                      ->find();
         return $taskInfo;
    }
    protected function _before_insert(&$data, $option){
        $data['valid_time'] = NOW_TIME  + 1200;
        $data['valid_status'] = 0;
        $data['status'] = 1;
        $data['add_time'] = NOW_TIME;
    }
    protected function _before_update(&$data, $option){
        $data['finish_time'] = NOW_TIME;
    }
}