<?php
/**
 * 获取家庭作业单词情况
 */
namespace Home\Model;

use Think\Model;

class HomeworkResultModel extends Model {
    protected $selectFields = array('result_id', 'user_id', 'homework_id', 'word_id', 'words',
        'test_time', 'input_words', 'test_result','type_id');

    /**
     * 获取测试结果列表
     * @param $where array
     */
    public function getHomeworkResultList($where, $fields) {
        if(is_null($fields)){
            $fields = $this->selectFields;
        }
        $list = $this->where($where)->field($fields)->order('result_id desc')->select();
        return $list;
    }
}