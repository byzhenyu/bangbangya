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
 * 单词例句控制器
 */
class WordExampleController extends CommonController {
    public function listWordExample(){
        $word_id = I('word_id');
        $where['word_id'] = $word_id;
        $field = 'we.*, w.words';
        $data = D('Admin/WordExample')->getWordExampleList($where, $field);
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    
    public function editWordExample(){
        $id = I('id');
        $wordExampleModel = D('Admin/WordExample');
        
        if (IS_POST) {
            if ($wordExampleModel->create() === false) {
                $this->ajaxReturn(V(0, $wordExampleModel->getError()));
            }
            if ($id) {
                if ($wordExampleModel->save() !== false) {
                    $this->ajaxReturn(V(1, '编辑成功'));
                }
            } else {
                if ($wordExampleModel->add() !== false) {
                    $this->ajaxReturn(V(1, '添加成功'));
                }
            }
            $this->ajaxReturn(V(0, $wordExampleModel->getDbError()));
        }
        
        $info = $wordExampleModel->find($id);
        $word_id = I('word_id');
        $words = D('Word')->where(['id' => $word_id])->getField('words');

        $this->assign('info', $info);
        $this->assign('words', $words);
        $this->display();
    }
    
    public function del(){
        $this->_del('WordExample', 'id');
    }
}
        