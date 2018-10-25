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
    protected $insertFields = array('id', 'category_name', 'category_img', 'status');
    protected $updateFields = array('id', 'category_name', 'category_img', 'status');

    protected $_validate = array(
        array('category_name', 'require', '请输入  ', 1, 'regex', 3),
        array('category_name', '0,50', '您输入的  过长，超过了 50 个字符数限制', 1, 'length', 3),
        array('category_img', 'require', '请输入 分类图片 ', 1, 'regex', 3),
        array('category_img', '0,255', '您输入的 分类图片 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('status', 'require', '请输入 1正常 0删除 ', 1, 'regex', 3),

    );

    public function getTaskCategoryList($where = [], $field = '', $order = '') {
        $count = $this->where($where)->count();
        $page = get_page($count);
        $list = $this->field($field)->where($where)->limit($page['limit'])->order($order)->select();
        return array(
            'list' => $list,
            'page' => $page['page']
        );
    }
    
}
        