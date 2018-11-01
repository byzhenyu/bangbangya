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
namespace Mobile\Controller;
use Think\Controller;
class HelpController extends Controller
{
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
        $HelpModel = D('Home/Help');
        $field = 'id, title';
        $data = $HelpModel->getHelpList($where,$field);
        $this->assign('list', $data['Helplist']);
        $this->assign('page', $data['page']);
        $this->assign('type', $type);
        p($data);
        exit;
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
         $helpModel = D('Home/Help');
         $field = 'title, content';
         $questionInfo =  $helpModel -> getQuestionDetail($id, $field);
         P($questionInfo);
         $this->assign('questionInfo', $questionInfo);
         $this->display();
    }
}