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
    public function questionList($type = 0){
        if (is_null($type)) {
        	$where['type'] = 0;
        }else{
        	$where['type'] = $type;
        }
        $keyword = I('keyword', '');
        $HelpModel = D('Admin/Help');
        if ($keyword) {
            $where['title'] = array('like','%'.$keyword.'%');
        }
        $data = $HelpModel->getHelpList($where);     
        $this->assign('list', $data['Helplist']);
        $this->assign('page', $data['page']);
        $this->assign('type', $type);
        p($data);
        exit;
         /*判断模板引用*/
        if($type == 1) {
            $this->display('questionList');
        }else{
            $this->display();
        }       
    }
}