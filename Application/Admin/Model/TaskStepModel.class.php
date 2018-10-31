<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;

/**
 * 任务类型模型
 */
class TaskStepModel extends Model {

    protected $selectFields = array('id', 'task_id', 'step_text', 'step_img','type','add_time');
    protected $_validate = array(
        array('task_id', 'require', '任务id不能为空 ', 1, 'regex', 3),
        array('type', array(1,2), '类型非法', 1, 'in', 3),
        array('step_text,step_img', 'checkInfo', '请上传图片或填写步骤说明', 1, 'callback', 3),
    );

    protected function checkInfo($data) {
        if (empty($data['step_text']) && empty($data['step_img'])) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * @desc 获取分类Id
     * @param $where where
     * @param $field field
     * @param $order order
     * @param $status    0删除  1 正常
     * @return $list  arr
     */
    public function getTaskStepList($where = [], $field = '', $order = 'add_time asc') {
        if ($field == '') {
            $field = $this->selectFields;
        }
        $list = $this->field($field)->where($where)->order($order)->select();
        if ($list) {
            return $list;
        } else {
            return array();
        }


    }

}
