<?php
/**
 * /**  任务分类类
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description    任务分类控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/31 0031 15:16
 * @CreateBy       PhpStorm
 */
namespace Pack\Controller;
use Common\Controller\CommonController;
class TaskCategoryController extends CommonController{
      /**
      * @desc  类型说明
      * @return array
      */
      public  function taskCategoryExplain(){
            $field = 'category_name, category_explain';
            $categoryModel = D('Home/TaskCategory');
            $categoryInfo =  $categoryModel ->gettaskCategoryExplain('', $field);
            p($categoryInfo);
            $this->assign('category', $categoryInfo);
            $this->display();
      }
}