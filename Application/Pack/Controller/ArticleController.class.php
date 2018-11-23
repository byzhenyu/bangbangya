<?php
/**
 * /**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     文章
 * @Author         (jicy 510434563)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/13
 * @CreateBy       PhpStorm
 */
namespace Mobile\Controller;
use Think\Controller;
class ArticleController extends Controller{

    //文章列表
    public function articleList(){
        $type  =  I('type', 0 ,'intval');
        if (is_null($type)) {
        	$where['type'] = 0;
        }else{
        	$where['type'] = $type;
        }
        $field = 'article_id, title';
        $data = $this->Help->getHelpList($where,$field);
        $this->assign('list', $data['Helplist']);
        $this->display();
    }
    /**
    * @desc 详情
    * @param  $id
    * @return mixed
    */
    public function articleDetail() {
         $category_id = I('category_id', 0, 'intval');
         $field = 'title, content, thumb_img, addtime';
         $where['article_cat_id'] = array('eq', $category_id);
         $info =  D('Home/Article')->getArticleDetail($where, $field);

         $this->assign('info', $info);
         $this->display();
    }
}