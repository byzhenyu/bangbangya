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
 * 任务表控制器
 */
class ComplaintController extends CommonController {
    public function listComplaint(){
        $data = D('Admin/Task')->getTaskList();
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }
 }
