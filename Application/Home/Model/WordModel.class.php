<?php
/**
 * 单词模型
 */
namespace Home\Model;

use Think\Model;

class WordModel extends Model {
    protected $selectFields = array('id', 'words', 'phonetic_alphabet', 'syllable', 'pronunciation',
        'chinese');
    
    public function getWordList($where, $field = null, $order = 'sort asc'){
        if ($field == null) {
            $field = $this->selectFields;
        }
        $list = $this->field($field)->where($where)->select();
        return $list;
    }

    public function getWordInfo($where, $field = null){
        if ($field == null) {
            $field = $this->selectFields;
        }
        $info = $this->field($field)->where($where)->find();
        return $info;
    }

    public function getWordExample($word_id) {
        $where['word_id'] = $word_id;
        $list = M('word_example')->field('example_sentence, example_chinese, example_pronunciation')->where($where)->select();
        return $list;
    }



}