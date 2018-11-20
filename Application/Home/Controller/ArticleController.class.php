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

class ArticleController extends CommonController {

    /**
     * @desc 文章详情
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
    //用户协议
    public function userAgreement() {

        $field = 'title, content, thumb_img, addtime';
        $where['article_cat_id'] = array('eq', 4);
        $info =  D('Home/Article')->getArticleDetail($where, $field);

        $this->assign('info', $info);
        $this->display('articleDetail');
    }
}