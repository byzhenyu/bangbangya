<?php
/**
 * 学段控制器
 */
namespace Admin\Controller;
use Think\Controller;
class CoursePeriodController extends CommonController {

    //学段添加/编辑
    public function editCoursePeriod(){
        $id = I('id', 0, 'intval');
        $model = D('Admin/CoursePeriod');
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
        $coursePeriod = M('CoursePeriod')->find($id);
        $this->assign('info', $coursePeriod);
        $this->display();
    }

    //显示学段分类列表
    public function listCoursePeriod(){
        $keyword = I('keyword', '', 'trim');

        $model = D('Admin/CoursePeriod');
        $where = array();
        //关键字查询
        if($keyword){
            $where['period_name'] = array('like', '%'. $keyword .'%');
        }
        $list = $model->getCoursePeriodList($where);


        $this->keyword = $keyword;
        $this->assign('list', $list['info']);
        $this->assign('page', $list['page']);
        $this->display();
    }

    //删除操作
    public function del(){
        $this->_del('CoursePeriod', 'id');
    }
}