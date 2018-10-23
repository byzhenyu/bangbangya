<?php
namespace Admin\Controller;

use Think\Controller;

/**
 * 代码控制器
 */
class CodeController extends Controller {

    public function index() {
        $table_name = I('table_name');
        $model = I('model');
        $view = I('view');
        $controller = I('controller');

        $info = D('Code')->buildCode($table_name, $model, $view, $controller);
        $this->assign('info', $info);
        $this->display();
    }
}