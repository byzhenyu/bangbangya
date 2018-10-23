<?php
/**
 * 课程单元控制器
 */
namespace Admin\Controller;
use Think\Controller;
class CourseUnitController extends CommonController {

    //单元新增/编辑
    public function editCourseUnit(){
        $id = I('id', 0, 'intval');
        $class_id = I('class_id', 0, 'intval');
        $model = D('Admin/CourseUnit');
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
        //课程分类列表
        $classList = M('CourseClass')->select();
        $courseUnit = M('CourseUnit')->find($id);
        $this->class = $classList;
        $this->info = $courseUnit;
        $this->class_id = $class_id;
        $this->display();
    }

    //显示课程单元列表
    public function listCourseUnit(){
        $keyword = I('keyword', '', 'trim');

        $model = D('Admin/CourseUnit');
        $class_id = I('class_id', 0, 'intval');
        $where['class_id'] = array('eq', $class_id);
        //关键字查询
        if($keyword){
            $where['unit'] = array('like', '%'. $keyword .'%');
        }
        $list = $model->getCourseUnitList($where);
        $this->keyword = $keyword;
        $this->class_id = $class_id;
        $this->assign('list', $list['info']);
        $this->assign('page', $list['page']);
        $this->display();
    }

    //删除操作
    public function del(){
        $this->_del('CourseUnit', 'id');
    }
}