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
 * 单词例句模型
 */
class WordExampleModel extends Model {
    protected $insertFields = array('id', 'word_id', 'example_sentence', 'example_chinese', 'example_pronunciation', 'sort');
    protected $updateFields = array('id', 'word_id', 'example_sentence', 'example_chinese', 'example_pronunciation', 'sort');

    protected $_validate = array(
        array('word_id', 'require', '请输入 单词id ', 1, 'regex', 3),
        array('example_sentence', 'require', '请输入 例句 ', 1, 'regex', 3),
        array('example_sentence', '0,1000', '您输入的 例句 过长，超过了 1000 个字符数限制', 1, 'length', 3),
        array('example_chinese', 'require', '请输入 例句中文翻译 ', 1, 'regex', 3),
        array('example_chinese', '0,1000', '您输入的 例句中文翻译 过长，超过了 1000 个字符数限制', 1, 'length', 3),
        array('example_pronunciation', 'require', '请输入 例句读音（mp3附件格式） ', 1, 'regex', 3),
        array('example_pronunciation', '0,255', '您输入的 例句读音（mp3附件格式） 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('sort', 'require', '请输入 排序 ', 1, 'regex', 3),

    );

    public function getWordExampleList($where = [], $field = '', $order = '') {
        $alias = 'we';
        $join = 'left join ln_word w on we.word_id = w.id';

        $count = $this->alias($alias)->join($join)->where($where)->count();
        $page = get_page($count);
        $list = $this->alias($alias)->field($field)->join($join)->where($where)->limit($page['limit'])->order($order)->select();
        return array(
            'list' => $list,
            'page' => $page['page']
        );
    }
    
}
        