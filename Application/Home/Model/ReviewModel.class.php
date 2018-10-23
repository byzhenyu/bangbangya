<?php
/**
 * 智能复习
 */
namespace Home\Model;

use Think\Model;

class ReviewModel extends Model {
    protected $selectFields = array('id', 'unit_id', 'word_id', 'word_id', 'result', 'add_time');

    /**
     * 插入智能复习
     * @param $unit_id int 单元id
     * @param $word_id int 单词id
     * @param $result int  结果 0错误 1正确
     * @param $group int 分组
     * @param $option
     */
    public function insertReview($unit_id, $word_id, $result, $group) {
        $where['unit_id'] = $unit_id;
        $where['word_id'] = $word_id;
        $where['user_id'] = UID;
        //先删除原单词测试
        $this->where($where)->delete();
        unset($where);
        $data = array();
        $data['user_id'] = UID;
        $data['unit_id'] = $unit_id;
        $data['word_id'] = $word_id;
        $data['result'] = $result;
        $data['group'] = $group;
        $data['add_time'] = NOW_TIME;
        return $this->add($data);
    }

    /**
     * 获取复习结果情况
     * @param $unit_id int 单元id
     */
    public function getReviewResult($unit_id) {
        $where['unit_id'] = $unit_id;
        $result_list = $this->field('id,result')->where($where)->select();
        $wrong_num = 0;
        $right_num = 0;
        $finish_num = 0;
        if (!empty($result_list)) {
            foreach ($result_list as $key => $value) {
                if ($value['result'] == 1) {
                    $right_num++;
                } else {
                    $wrong_num++;
                }
            }
            $finish_num = count($result_list);
        }
        
        return array('finish_num'=>$finish_num, 'right_num'=>$right_num, 'wrong_num'=>$wrong_num);
    }

    /**
     * 获取智能复习单词内容
     * @param $unit_id int 单元id
     * @param $has_learn_num int  本组已完成单词个数
     * @param $has_learn_num int  所有已完成单词个数
     * @param $option
     */
    public function getReview($unit_id, $has_learn_num, $all_learn_num) {
        $courseWordModel = D('Home/CourseWord');
        $wordModel = D('Home/Word');
        $userModel = D('Home/User');
        $review_number = $userModel->getLearnSetting();
        $review_number = $review_number['intelligent_number'];
        $where['unit_id'] = $unit_id;
        $word_num = $courseWordModel->where($where)->count('id');
        $word_list = $courseWordModel->where($where)->field('word_id')->limit($all_learn_num, 1)->select();
        
        unset($where);
        if (empty($word_list)) {
            return V(0, '本单元没有可复习的单词');
        }
        if ($all_learn_num >= $word_num) {
            return V(2, '本单元单词已经复习完了');
        }
        if (($has_learn_num / $review_number) == 1) {
            return V(3, '本组单词已复习完了，进入下一组');
        }
        $data['word_num'] = $word_num;
        $word_id = $word_list[0]['word_id'];
        $where['id'] = $word_id;
        $word_info = $wordModel->getWordInfo($where);
        unset($where);

        foreach ($word_info as $key => $value) {
            $data[$key] = $value;
        }
        //获取单词统计情况
        $test_result = $this->getReviewResult($unit_id);
        foreach ($test_result as $key => $value) {
            $data[$key] = $value;
        }
        //单词倒计时时间5s
        $data['word_time'] = 5;

        //获取随机词义
        $where['cw.unit_id'] = $unit_id;
        $word_chinese_list = $courseWordModel->getUnitWord($where);
        foreach ($word_chinese_list as $key => $value) {
            $word_chinese_data[$value['word_id']] = $value;
        }
        unset($word_chinese_data[$data['id']]);
        $chinese_rand_array = array_rand($word_chinese_data, 7); //随机取7条词义
        array_push($chinese_rand_array, $data['id']); //把正确答案追加到数组
        shuffle($chinese_rand_array); //重新排列数组
        foreach ($chinese_rand_array as $k => $v) {
            if ($v == $data['id']) {
                $chinese_list[$k]['word_id'] = $data['id'];
                $chinese_list[$k]['chinese'] = $data['chinese'];
                $chinese_list[$k]['answer_results'] = 1;
            } else {
                $chinese_list[$k]['word_id'] = $word_chinese_data[$v]['word_id'];
                $chinese_list[$k]['chinese'] = $word_chinese_data[$v]['chinese'];
                $chinese_list[$k]['answer_results'] = 0;
            }
        }
        $data['chinese_list'] = $chinese_list;
        return V(1, '获取单词信息', $data);
    }
}