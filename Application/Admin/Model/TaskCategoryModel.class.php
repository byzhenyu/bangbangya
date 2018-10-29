<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;

/**
 * 任务类型模型
 */
class TaskCategoryModel extends Model {
    protected $insertFields = array('id', 'category_name', 'category_img', 'status','category_explain');
    protected $updateFields = array('id', 'category_name', 'category_img', 'status','category_explain');
    protected $selectFields = array('id', 'category_name', 'category_img', 'status','category_explain');
    protected $_validate = array(
        array('category_name', 'require', '请输入分类名称 ', 1, 'regex', 3),
        array('category_name', '0,10', '您输入的分类名称过长，超过了10个字符数限制', 1, 'length', 3),
        array('category_img', 'require', '请上传分类图片 ', 1, 'regex', 3),
        array('category_explain', 'require', '请输出类别说明 ', 1, 'regex', 3),
    );
    /**
     * @desc 获取分类Id
     * @param $where where
     * @param $field field
     * @param $order order
     * @param $status    0删除  1 正常
     * @return $list  arr
     */
    public function getTaskCategoryList($where = [], $field = '', $order = '',$status = null) {
        if(is_null($status))
        {
             $where['status'] = 1;
        }
        $count = $this->where($where)->count();
        $page = get_page($count);
        $list = $this->field($field)->where($where)->limit($page['limit'])->order($order)->select();
        return array(
            'list' => $list,
            'page' => $page['page']
        );

    }
    /**
     * @desc 获取分类Id
     * @param $categoryList 分类数据
     * @return $categoryList['id'] array();
     */
    public function getIds($categoryList)
    {
         $ids = array();
         foreach ($categoryList as $key => $value) {
             $ids[$key] = $value['id'];
         }
         return $ids;
    }
}
