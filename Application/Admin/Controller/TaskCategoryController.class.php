<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author         
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;

/**
 * 任务类型控制器
 */
class TaskCategoryController extends CommonController {
    public function listTaskCategory(){
        $keyword = I('keyword', '');
        $TaskCategoryModel = D('Admin/TaskCategory');
        if ($keyword) {
            $where['category_name'] = array('like','%'.$keyword.'%');
        }
        $data = $TaskCategoryModel->getTaskCategoryList($where);
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    
    public function editTaskCategory(){
        $id = I('id');
        $taskCategoryModel = D('Admin/TaskCategory');       
        if (IS_POST) {
            if ($taskCategoryModel->create() === false) {
                $this->ajaxReturn(V(0, $taskCategoryModel->getError()));
            }
            if ($id) {
                if ($taskCategoryModel->save() !== false) {
                    $this->ajaxReturn(V(1, '编辑成功'));
                }
            } else {
                if ($taskCategoryModel->add() !== false) {
                    $this->ajaxReturn(V(1, '添加成功'));
                }
            }
            $this->ajaxReturn(V(0, $taskCategoryModel->getDbError()));
        }
        $info = $taskCategoryModel->find($id);
        $this->assign('info', $info);
        $this->display();
    }

    public function recycle() {
        $this->_recycle('TaskCategory', 'id');
    }
    // 删除图片
    public function delFile(){
        $this->_delFile();  //调用父类的方法
    }

    // 上传图片
    public function uploadImg(){
        $this->_uploadImg();  //调用父类的方法
    }
}
        