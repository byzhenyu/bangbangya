<?php
/**
 * 错题集
 */
namespace Home\Model;

use Think\Model;

class WrongWordsModel extends Model {
    protected $selectFields = array('id', 'class_id', 'unit_id', 'word_id', 'add_time');
    protected $insertFields = array('class_id', 'unit_id', 'word_id', 'add_time');

    /**
     * 插入测试结果
     * @param $unit_id int 单元id
     * @param $word_id int 单词id
     * @param $write_word string 输入的单词
     * @param $use_time int  用的单词时间，单位s
     * @param $type_id int  类型 0单元测试 1听写 2默写
     * @param $option
     */
    public function insertWrongWords($class_id, $unit_id, $word_id) {
        $where['user_id'] = UID;
        $where['unit_id'] = $unit_id;
        $where['word_id'] = $word_id;
        $this->where($where)->delete();
        $data = array();
        $data['user_id'] = UID;
        $data['class_id'] = $class_id;
        $data['unit_id'] = $unit_id;
        $data['word_id'] = $word_id;
        $data['add_time'] = NOW_TIME;
        $this->add($data);
    }

    /**
     *
     */
    public function getWrongWords($where,$field='', $order='s.add_time desc') {
        if (!$field) {
            $field = 's.id, w.words, w.phonetic_alphabet, w.syllable, w.pronunciation, w.chinese';
        }
        $count = $this->alias('s')->where($where)->count();
        $page = get_web_page($count);
        $data= $this->alias('s')
            ->join('__WORD__ w on s.word_id = w.id')
            ->where($where)
            ->field($field)
            ->limit($page['limit'])
            ->order($order)
            ->select();

        return $data;
    }

    /**
     * 获取错题集标题数量
     * @param $where
     */
    public function getItemNum($where) {
        $count0 = $this->where($where)->count();
        $begin1 =strtotime('-1 days');
        $end1 = NOW_TIME;
        $map['add_time'] = array('between', array($begin1, $end1));
        $count1 = $this->where($where)->where($map)->count();
        $begin2 = strtotime('-7 days');
        $end2 = NOW_TIME;
        $map2['add_time'] = array('between', array($begin2, $end2));
        $count2 = $this->where($where)->where($map2)->count();
        $begin3 = strtotime('-15 days');
        $end3 = NOW_TIME;
        $map3['add_time'] = array('between', array($begin3, $end3));
        $count3 = $this->where($where)->where($map3)->count();

        return array($count0,$count1,$count2,$count3);
    }
}