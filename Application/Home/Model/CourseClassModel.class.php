<?php
/**
 * 课程分类列表
 */
namespace Home\Model;
use Think\Model;
class CourseClassModel extends Model {
    protected $selectFields = array('id', 'period_id', 'class_name');

    /**
     * 获取课程分类列表+课程单元列表
     * @return mixed
     */
    public function getCourseClassLists($where,$user_id, $field, $order = 'id desc'){
        if(is_null($field)) $field = $this->selectFields;
        $list = $this->where($where)->field($field)->order($order)->select();
        if(empty($list)) return array();
        $CourseUnitModel = D('Home/CourseUnit');
        foreach($list as &$v){
            $where1['class_id'] = $v['id'];
            $v['course_unit'] = $CourseUnitModel->getCourseUnitLists($where1,$user_id);
        }
        return $list;
    }
}