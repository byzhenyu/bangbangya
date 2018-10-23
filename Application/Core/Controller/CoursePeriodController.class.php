<?php
namespace Core\Controller;
use Think\Controller;

class CoursePeriodController extends Controller {
    /**
     * @desc 获取学段列表
     */
    public function getCoursePeriod(){
        $list = M('CoursePeriod')->field('id,period_name')->select();
        $this->ajaxReturn($list);
    }

    /**
     * @desc 获取学段下的课程列表
     */
    public function getCourseClass(){
        $period_id = I('period_id',0, 'intval');
        $class = M('CourseClass')->where(array('period_id' => $period_id))->field('id,class_name')->select();
        $this->ajaxReturn($class);
    }

    /**
     * @desc 获取课程下的单元列表
     */
    public function getCourseUnit(){
        $class_id = I('class_id', 0, 'intval');
        $unit = M('CourseUnit')->where(array('class_id' => $class_id))->field('id,unit')->select();
        $this->ajaxReturn($unit);
    }
}