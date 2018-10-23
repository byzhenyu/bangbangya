<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author         
 * @Date           2018/8/30
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;
 
/**
 * 单词词性模型
 */
class WordGenderModel extends Model {
    protected $insertFields = array('id', 'name', 'zh_name', 'shortname', 'sort');
    protected $updateFields = array('id', 'name', 'zh_name', 'shortname', 'sort');

    protected $_validate = array(
        array('name', 'require', '请输入 名称 ', 1, 'regex', 3),
        array('name', '0,255', '您输入的 名称 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('zh_name', 'require', '请输入 中文名称 ', 1, 'regex', 3),
        array('zh_name', '0,255', '您输入的 中文名称 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('shortname', 'require', '请输入 简称 ', 1, 'regex', 3),
        array('shortname', '0,255', '您输入的 简称 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('sort', 'require', '请输入 排序 ', 1, 'regex', 3),

    );

    public function getWordGenderList($where = [], $field = '', $order = '') {
        $count = $this->where($where)->count();
        $page = get_page($count);
        $list = $this->field($field)->where($where)->limit($page['limit'])->order($order)->select();
        return array(
            'list' => $list,
            'page' => $page['page']
        );
    }
    
}
        