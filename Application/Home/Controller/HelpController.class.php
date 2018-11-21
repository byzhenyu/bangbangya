<?php
/**
 * /**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     常见问题 使用帮助 控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/29 0031 09:41
 * @CreateBy       PhpStorm
 */
namespace Home\Controller;
use Common\Controller\UserCommonController;
class HelpController extends UserCommonController {
    public function _initialize() {
        $this->Help = D("Home/Help");
    }
    /**
    * @ 常见问题列表
    * @param  $[keyword] [<关键字>]
    * @param  $[type] [<类型 0 常见问题  1 使用帮助>]
    * @return  array()
    */
    public function questionList(){
        $type  =  I('type', 0 ,'intval');
        if (is_null($type)) {
        	$where['type'] = 0;
        }else{
        	$where['type'] = $type;
        }
        $field = 'id, title';
        $list = $this->Help->getHelpListNot($where,$field);
        $this->assign('list', $list);
        $this->display();
    }
    /**
    * @desc 问题详情
    * @param  $id
    * @return mixed
    */
    public function getQuestionDetail()
    {
         $id = I('id', 0, 'intval');
         $field = 'title, content, add_time';
         $questionInfo =  $this->Help-> getQuestionDetail($id, $field);
         $this->assign('questionInfo', $questionInfo);
         $this->display();
    }
}