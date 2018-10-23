<?php
/**
 * 听力闯关
 */
namespace Admin\Controller;
use Think\Controller;
class HearingPassController extends CommonController {



    //听力闯关统计列表
    public function listHearingPass(){
        $user_id = I('user_id', 0, 'intval');
        $class_id = I('class_id', 0, 'intval');
        $unit_id = I('unit_id', 0, 'intval');
        $where = array('user_id' => $user_id);
        if($class_id) $where['u.class_id'] = $class_id;
        if($unit_id) $where['h.unit_id'] = $unit_id;
        $model = D('Admin/HearingPass');
        $list = $model->getHearingPassList($where);
        $courseClassList = D('Admin/CourseClass')->listCourseClass();
        $courseUnitList = D('Admin/CourseUnit')->listCourseUnit();
        $this->class_id = $class_id;
        $this->unit_id = $unit_id;
        $this->user_id = $user_id;
        $this->course_class = $courseClassList;
        $this->course_unit = $courseUnitList;
        $this->list = $list['info'];
        $this->page = $list['page'];
        $this->display();
    }

    //单元下听力闯关记录
    public function listUnitWords(){
        $unit_id = I('unit_id', 0, 'intval');
        $keywords = I('keywords');
        $model = D('Admin/HearingPass');
        $where = array('h.unit_id' => $unit_id);
        if($keywords) $where['w.words'] = array('like', '%'.$keywords.'%');
        $list = $model->getUnitWordsList($where);
        $this->keywords = $keywords;
        $this->unit_id = $unit_id;
        $this->list = $list['info'];
        $this->page = $list['page'];
        $this->display();
    }
}