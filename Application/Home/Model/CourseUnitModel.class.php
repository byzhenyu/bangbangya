<?php
/**
 * 课程单元模型
 */
namespace Home\Model;

use Think\Model;

class CourseUnitModel extends Model {
    protected $selectFields = array('id','class_id', 'unit','word_num');
    /**
     * 获取课程单元列表
     * @param
     * @return mixed
     */
    public function getCourseUnitLists($where,$user_id,$field = false, $order = 'id desc'){
        if(is_null($field)) $field = $this->selectFields;
        $list = $this->where($where)->field('id,class_id,unit,word_num')->order($order)->select();
        $UserTestResultModel = D('Home/UserTestResult');
        foreach($list as &$v){
            $score = $UserTestResultModel->getUserPass($v['id'],$v['word_num'],$user_id);//百分比
            $v['test_score'] = $score.'%';
        }
        return $list;
    }
}