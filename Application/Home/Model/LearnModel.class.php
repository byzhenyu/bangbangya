<?php
/**
 * 智能复习
 */
namespace Home\Model;

use Think\Model;

class LearnModel extends Model {
    protected $selectFields = array('id', 'unit_id', 'word_id', 'word_id', 'group', 'type_id', 'add_time');

    /**
     * 插入学习
     * @param $unit_id int 单元id
     * @param $word_id int 单词id
     * @param $option
     */
    public function insertLearn($unit_id, $word_id, $type_id) {
        $where['unit_id'] = $unit_id;
        $where['word_id'] = $word_id;
        $where['user_id'] = UID;
        
    }

    /**
     * 获单词内容
     * @param $unit_id int 单元id
     * @param $all_learn_num int  所有已完成单词个数
     * @param $option
     */
    public function getLearn($unit_id, $all_learn_num, $word_num, $type_id) {
        $learnWordModel = D('Home/LearnWord');
        $wordModel = D('Home/word');
        $userModel = D('Home/User');
        $words_number = $userModel->getLearnSetting();
        $words_number = $words_number['words_number']; //分组个数

        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        $where['is_read'] = 0;
        $word_list = $learnWordModel->where($where)->field('word_id')->limit($all_learn_num, 1)->select();
        unset($where);

        if ($all_learn_num >= $word_num) {
            return V(2, '本单元单词结束，进入下一环节');
        }
        if (($all_learn_num / $words_number) == 1) {
            return V(3, '本组单词结束，进入下一环节');
        }
        $data['word_num'] = $word_num;
        $data['learn_num'] = $all_learn_num + 1;
        $word_id = $word_list[0]['word_id'];
        $where['id'] = $word_id;
        $word_info = $wordModel->getWordInfo($where);
        unset($where);
        foreach ($word_info as $key => $value) {
            $data[$key] = $value;
        }
        if ($type_id == 0) {
            //获取单词例句
            $word_example = $wordModel->getWordExample($word_id);
            if (!empty($word_example)) {
                $data['example_list'] = $word_example;
            }
        }
        return V(1, '获取单词信息', $data);
    }
    
}