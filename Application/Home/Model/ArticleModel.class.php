<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description    文章管理
 * @Author         (jicy QQ:510434563)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           ${DATE} ${TIME}
 * @CreateBy       ${PRODUCT_NAME}
 */
namespace Home\Model;
use  Think\Model;
class ArticleModel extends Model {
    protected $selectFields = array('article_id','title', 'type', 'content', 'sort', 'thumb_img', 'add_time');

    //文章列表
    public function getArticleList($where=[],$field='',$order = 'sort asc, id desc') {
        if ($field == '') {
            $field = $this->selectFields;
        }
        $count = $this->where($where)->count();
        $page = get_web_page($count);
        $info = $this->where($where)
            ->field($field)
            ->limit($page['limit'])
            ->order($order)
            ->select();

        return array(
            'info' => $info,
            'page' =>$page['page']
        );
    }
    //详情
    public function getArticleDetail($where=[],$field='') {

        if ($field == '') {
            $field = $this->selectFields;
        }
        $info = $this->where($where)->field($field)->find();
        return $info;
    }
}