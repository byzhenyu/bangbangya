<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author         
 * @Date           2018/8/30
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;

/**
 * 单词词性控制器
 */
class WordGenderController extends CommonController {
    public function listWordGender(){
        $data = D('Admin/WordGender')->getWordGenderList();
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    
    public function editWordGender(){
        $id = I('id');
        $wordGenderModel = D('Admin/WordGender');
        
        if (IS_POST) {
            if ($wordGenderModel->create() === false) {
                $this->ajaxReturn(V(0, $wordGenderModel->getError()));
            }
            if ($id) {
                if ($wordGenderModel->save() !== false) {
                    $this->ajaxReturn(V(1, '编辑成功'));
                }
            } else {
                if ($wordGenderModel->add() !== false) {
                    $this->ajaxReturn(V(1, '添加成功'));
                }
            }
            $this->ajaxReturn(V(0, $wordGenderModel->getDbError()));
        }
        
        $info = $wordGenderModel->find($id);
        $this->assign('info', $info);
        $this->display();
    }
    
    public function del(){
        $this->_del('WordGender', 'id');
    }
}
        