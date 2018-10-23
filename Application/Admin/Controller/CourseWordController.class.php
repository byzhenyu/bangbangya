<?php
/**
 * 单元单词控制器
 */
namespace Admin\Controller;
use Think\Controller;
class CourseWordController extends CommonController {

    //单词新增/编辑
    public function editCourseWord(){
        $id = I('id', 0, 'intval');
        $unit_id = I('unit_id', 0, 'intval');
        $model = D('Admin/CourseWord');
        if(IS_POST){
            $data = I('post.');

            if ($id > 0){
                if($model->create($data)){
                    if ($model->save() !== false) {
                        $this->ajaxReturn(V(1, '修改成功!'));
                    }
                }
            } else {
                if($model->create($data, 1)){
                    if ($model->add() !== false) {
                        $courseUnit = D('CourseUnit');
                        $courseUnit->changeCourseUnitWords($data['unit_id']);
                        $this->ajaxReturn(V(1, '保存成功!'));
                    }
                }
            }

            $this->ajaxReturn(V(0, $model->getError()));
        }
        //单词信息
        $info = M('CourseWord')->find($id);
        if (!empty($info)){
            $wordArr = M('Word')->field('words')->find($info['word_id']);
            $info['words'] = $wordArr['words'];
        }
        $this->id= $id;
        $this->unit_id = $unit_id;
        $this->info = $info;
        $this->display();
    }

    //显示课程单元列表
    public function listCourseWord(){
        $keyword = I('keyword', '', 'trim');
        $unit_id = I('unit_id', 0, 'intval');
        $model = D('Admin/CourseWord');
        $where['w.unit_id'] = array('eq' ,$unit_id);
        //关键字查询
        if($keyword){
            $where['r.word'] = array('like', '%'. $keyword .'%');
        }
        $list = $model->getCourseWordList($where);

        $this->keyword = $keyword;
        $this->unit_id = $unit_id;
        $this->assign('list', $list['info']);
        $this->assign('page', $list['page']);
        $this->display();
    }

    public function add_more_words(){
        $unit_id = I('unit_id', 0, 'intval');
        if(IS_POST){
            $excelController = A('Excel');
            $upload = $excelController->uploadCourseWord();
            $this->ajaxReturn($upload);
        }
        $this->unit_id = $unit_id;
        $this->display('add_more_words');
    }

    //删除操作
    public function del(){
        $this->_del('CourseWord', 'id');
    }

    public function uploadField(){
        $this->_uploadField();
    }
}