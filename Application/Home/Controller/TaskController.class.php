<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description
 * @Author   
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           ${DATE} ${TIME}
 * @CreateBy       ${PRODUCT_NAME}
 */

namespace Home\Controller;
use Common\Controller\CommonController;
use Common\Controller\UserCommonController;

class TaskController extends UserCommonController{

       public function listTask()
       {
       	   $this->display();
       }
       //添加/修改任务
       public function addTask() {
           $this->display();
       }
       //我的发布
       public function myTask() {
            $this->display();
       }
}