<?php
/**
 * 课程分类列表
 */
namespace Admin\Model;

use Think\Model;

class CourseClassModel extends Model {
    protected $insertFields = array('class_name', 'period_id');
    protected $updateFields = array('class_name', 'period_id', 'id');
    protected $selectFields = array('class_name', 'period_id', 'id');
    protected $_validate = array(
        array('period_id', 'require', '学段分类不能为空！', 1, 'regex', 3),
        array('class_name', 'require', '课程分类不能为空！', 1, 'regex', 3),
        array('class_name', 'checkCourseClass', '课程分类不能超过30字！', 2, 'callback', 3),
    );

    /**
     * 验证课程分类长度
     */
    protected function checkCourseClass($data) {
        $length = mb_strlen($data, 'utf-8');
        if ($length > 30) {
            return false;
        }
        return true;
    }

    /**
     * 获取课程分类列表分页
     * @return mixed
     */
    public function getCourseClassListByPage($where, $field = false){
        if(is_null($fields)){
            $fields = $this->selectFields;
        }
        $count = $this->where($where)->count();
        $page = get_web_page($count);
        $list = $this->where($where)->limit($page['limit'])->field($field)->order('id desc')->select();
        return array(
            'info' => $list,
            'page' => $page['page']
        );
    }

    /**
     * 获取课程分类列表
     * @return mixed
     */
    public function getCourseClassList($where, $field, $order = 'id desc'){
        if(is_null($fields)){
            $fields = $this->selectFields;
        }
        $list = $this->where($where)->field($field)->order($order)->select();
        return $list;
    }

    /**
     * @desc 课程列表
     * @return mixed
     */
    public function listCourseClass(){
        $res = $this->select();
        return $res;
    }

    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){
        $class_name = $data['class_name'];
        $find = $this->where(array('class_name' => $class_name))->find();
        if($find){
            $this->error = '课程分类已经存在！';
            return false;
        }
    }
    //更新操作前的钩子操作
    protected function _before_update(&$data, $option){
        $class_name = $data['class_name'];
        $find = $this->where(array('class_name' => $class_name, 'id' => array('neq', $data['id'])))->find();
        if($find){
            $this->error = '课程分类已经存在！';
            return false;
        }
    }

    public function getCourseClassInfo($where, $field, $order = 'id desc') {
        if (!$field) {
            $field = $this->selectFields;
        }
        $info = $this->field($field)->where($where)->order($order)->find();
        return $info;
    }
}