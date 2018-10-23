<?php
/**
 * 课程控制器
 */
namespace Admin\Controller;
use Think\Controller;
class CourseClassController extends CommonController {

    //课程新增/编辑
    public function editCourseClass(){
        $id = I('id', 0, 'intval');
        $model = D('Admin/CourseClass');
        if(IS_POST){
            $data = I('post.');
            if ($id > 0){
                if($model->create($data, 2)){
                    if ($model->save() !== false) {
                        $this->ajaxReturn(V(1, '修改成功!'));
                    }
                }
            } else {
                if($model->create($data, 1)){
                    if ($model->add() !== false) {
                        $this->ajaxReturn(V(1, '保存成功!'));
                    }
                }
            }

            $this->ajaxReturn(V(0, $model->getError()));
        }
        //学短分段列表
        $periodList = M('CoursePeriod')->select();
        $courseClass = M('CourseClass')->find($id);
        $this->period = $periodList;
        $this->assign('info', $courseClass);
        $this->display();
    }

    //显示课程分类列表
    public function listCourseClass(){
        $keyword = I('keyword', '', 'trim');
        $model = D('Admin/CourseClass');
        $where = array();
        //关键字查询
        if($keyword){
            $where['class_name'] = array('like', '%'. $keyword .'%');
        }
        $list = $model->getCourseClassListByPage($where);
        $period_list = D('CoursePeriod')->getPeriodById();
        $this->assign('period_list', $period_list);
        $this->assign('list', $list['info']);
        $this->assign('page', $list['page']);
        $this->display();
    }

    //删除操作
    public function del(){
        $this->_del('CourseClass', 'id');
    }
}