<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author
 * @Date           2018/9/6
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;

/**
 * 单词纠错表控制器
 */
class WordCorrectionController extends CommonController {
    public function listWordCorrection() {
        $selected_type = I('selected_type');
        if ($selected_type) {
            $where['wc.type'] = $selected_type;
        }
        $keyword = I('keyword');
        if ($keyword) {
            $where['w.words'] = ['like', "%$keyword%"];
        }
        $field = 'wc.*, u.user_name, w.words';
        $order = 'add_time desc';
        $data = D('Admin/WordCorrection')->getWordCorrectionList($where, $field, $order);
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }

    public function del() {
        $this->_del('WordCorrection', 'id');
    }
}
