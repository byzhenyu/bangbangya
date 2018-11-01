<?php
/**
 * /**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     任务操作步骤 Model
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/31 0031 16:41
 * @CreateBy       PhpStorm
 */
namespace Home\Model;
use Think\Model;
class TaskStepModel extends  Model {
    protected $selectFields = array('id','type', 'task_id','step_img', 'step_text');
    protected $_validate = array(
        array('task_id', 'require', '任务Id不能为空！', 1, 'regex', 3)
    );
    public function getTaskStep($task_id = '',$field = null , $sort = 'add_time DESC' ){
        if($task_id == '') return false;
        if(is_null($field)){
            $field = $this->selectFields;
        }
        $taskStepInfo = $this->field($field)->where('task_id =  '.$task_id)->select();
        return $taskStepInfo;
    }
    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){
        $data['add_time'] = NOW_TIME;
    }
}