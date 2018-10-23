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
 * 单词词库模型
 */
class WordModel extends Model {
    protected $insertFields = array('id', 'words', 'phonetic_alphabet', 'syllable', 'pronunciation', 'chinese', 'words_type', 'sort');
    protected $updateFields = array('id', 'words', 'phonetic_alphabet', 'syllable', 'pronunciation', 'chinese', 'words_type', 'sort');

    protected $_validate = array(
        array('words', 'require', '请输入 单词 ', 1, 'regex', 3),
        array('words', '0,100', '您输入的 单词 过长，超过了 100 个字符数限制', 1, 'length', 3),
        array('phonetic_alphabet', 'require', '请输入 音标 ', 1, 'regex', 3),
        array('phonetic_alphabet', '0,100', '您输入的 音标 过长，超过了 100 个字符数限制', 1, 'length', 3),
        array('syllable', 'require', '请输入 音节 ', 1, 'regex', 3),
        array('syllable', '0,50', '您输入的 音节 过长，超过了 50 个字符数限制', 1, 'length', 3),
        // array('pronunciation', 'require', '请输入 单词读音（mp3附件格式） ', 1, 'regex', 3),
        // array('pronunciation', '0,255', '您输入的 单词读音（mp3附件格式） 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('chinese', 'require', '请输入 中文词义 ', 1, 'regex', 3),
        array('chinese', '0,255', '您输入的 中文词义 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('sort', 'require', '请输入 排序 ', 1, 'regex', 3),

    );

    public function getWordList($where = [], $field = '', $order = '') {
        $count = $this->where($where)->count();
        $page = get_page($count);
        $list = $this->field($field)->where($where)->limit($page['limit'])->order($order)->select();
        return array(
            'list' => $list,
            'page' => $page['page'],
        );
    }

}
