<?php
/**
 * 学生课程控制器
 */
namespace Admin\Controller;
use Think\Controller;
class UserCourseController extends CommonController {

    /**
     * @desc 测试列表
     */
    public function listUserCourse(){
        $user_id = I('user_id', 0, 'intval');
        $class_id = I('class_id', 0, 'intval');
        $unit_id = I('unit_id', 0, 'intval');
        $where = array('c.user_id' => $user_id);
        if($class_id) $where['c.class_id'] = $class_id;
        if($unit_id) $where['c.unit_id'] = $unit_id;
        $model = D('Admin/UserCourse');
        $list = $model->listUserCourseStatistic($where);
        $courseClassList = D('Admin/CourseClass')->listCourseClass();
        $courseUnitList = D('Admin/CourseUnit')->listCourseUnit();
        $this->course_class = $courseClassList;
        $this->course_unit = $courseUnitList;
        $this->user_id = $user_id;
        $this->class_id = $class_id;
        $this->unit_id = $unit_id;
        $this->list = $list['info'];
        $this->page = $list['page'];
        $this->display();
    }

    /**
     * @desc 单元测试详情
     */
    public function listUserTestResultDetail(){
        $unit_id = I('unit_id');
        $keywords = I('keywords');
        $where = array('unit_id' => $unit_id);
        $model = D('Admin/UserTestResult');
        if($keywords) $where['words'] = array('like', '%'.$keywords.'%');
        $list = $model->userTestResultList($where);
        $this->unit_id = $unit_id;
        $this->keywords = $keywords;
        $this->list = $list['info'];
        $this->page = $list['page'];
        $this->display();
    }
}