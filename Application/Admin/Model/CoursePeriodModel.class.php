<?php
/**
 * 学段分类管理类
 */
namespace Admin\Model;

use Think\Model;

class CoursePeriodModel extends Model {
    protected $insertFields = array('period_name');
    protected $updateFields = array('period_name', 'id');
    protected $selectFields = array('period_name', 'id');
    protected $_validate = array(
        array('period_name', 'require', '学段名称不能为空！', 1, 'regex', 3),
        array('period_name', 'checkCoursePeriod', '学段名称不能超过30字！', 2, 'callback', 3),
    );

    /**
     * 验证学段名称长度
     */
    protected function checkCoursePeriod($data) {
        $length = mb_strlen($data, 'utf-8');
        if ($length > 30) {
            return false;
        }
        return true;
    }
    /**
     * 获取学段列表
     * @param
     * @return mixed
     */
    public function getCoursePeriodList($where){
        $count = $this->where($where)->count();
        $page = get_web_page($count);
        $list = $this->where($where)->limit($page['limit'])->order('id desc')->select();

        return array('info' => $list, 'page' => $page['page']);
    }

    public function getCoursePeriodInfo($where, $fields = null){
        if(is_null($fields)){
            $fields = $this->selectFields;
        }
        $info = $this->field($fields)->where($where)->find();
        return $info;
    }

    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){
        $period_name = $data['period_name'];
        $find = $this->where(array('period_name' => $period_name))->find();
        if($find){
            $this->error = '学段名称已经存在！';
            return false;
        }
    }

    //更新操作前的钩子操作
    protected function _before_update(&$data, $option){
        $period_name = $data['period_name'];
        $find = $this->where(array('period_name' => $period_name, 'id' => array('neq', $data['id'])))->find();
        if($find){
            $this->error = '学段名称已经存在！';
            return false;
        }
    }

    //根据键值获取学段
    public function getPeriodById() {
        return $this->getField('id, period_name', true);
    }

}