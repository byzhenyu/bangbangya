<?php
/**
 * 需要学习的单词
 */
namespace Home\Model;

use Think\Model;

class LearnWordModel extends Model {

    /**
     * 加入需要学习的单词
     * @param $unit_id int 单元id
     * @param $word_id int 单词id
     * @param $type_id int 类型
     */
    public function addLearnWord($unit_id, $word_id, $type_id) {
        $where['unit_id'] = $unit_id;
        $where['word_id'] = $word_id;
        $where['user_id'] = UID;
        $checkWord = $this->where($where)->count('id');
        if ($checkWord > 0) {
            $this->where($where)->setField('type_id', $type_id);
        } else {
            $data = array();
            $data['user_id'] = UID;
            $data['unit_id'] = $unit_id;
            $data['word_id'] = $word_id;
            $data['type_id'] = $type_id;
            $this->add($data);
        }
    }

    public function deleteLearnWord($unit_id, $word_id) {
        $where['unit_id'] = $unit_id;
        $where['word_id'] = $word_id;
        $where['user_id'] = UID;
        $this->where($where)->delete();        
    }

    /**
     * 更新单词学习状况
     * @param $unit_id int 单元id
     * @param $word_id int 单词id
     */
    public function updateWord($unit_id, $word_id, $type_id) {
        $update_field = array('is_read', 'is_memory', 'is_recall', 'is_hearing', 'is_writing');
        $where['unit_id'] = $unit_id;
        $where['word_id'] = $word_id;
        $where['user_id'] = UID;
        $this->where($where)->setField($update_field[$type_id], 1);
    }

    /**
     * 更新单词测试
     * @param $unit_id int 单元id
     * @param $word_id int 单词id
     */
    public function updateWordTest($unit_id, $word_id, $type_id, $result) {
        $update_field = array(1 => 'memory_test', 2 => 'recall_test');
        $where['unit_id'] = $unit_id;
        $where['word_id'] = $word_id;
        $where['user_id'] = UID;
        $data[$update_field[$type_id]] = $result;
        if ($result == 0) {
            $data['sort'] = array('exp','sort+1');
        }
        $this->where($where)->save($data);
    }

    /**
     * 获单词内容
     * @param $unit_id int 单元id
     * @param $all_learn_num int  所有已完成单词个数
     * @param $word_num int  单词总数
     * @param $type_id int  类型 0跟读 1记忆 2回忆 3听写 4默写
     * @param $word_id int  单词id
     * @param $option
     */
    public function getLearn($unit_id, $all_learn_num, $word_num, $type_id, $word_id, $up_num) {
        $where_field = array('is_read', 'is_memory', 'is_recall', 'is_hearing', 'is_writing');
        $wordModel = D('Home/word');
        $userModel = D('Home/User');
        $user_setting = $userModel->getLearnSetting();
        $words_number = $user_setting['words_number']; //分组个数
        $spell_rate = $user_setting['spell_rate']; //单词拼写速率
        $true_time = $user_setting['true_time']; //显示正确单词时间

        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        $where[$where_field[$type_id]] = 0;
        $order = 'id asc';
        $limit = 0;
        if ($type_id == 0 && $up_num != 0) {
            $where['is_read'] = 1;
            $order = 'id desc';
            $up_num_limit = $up_num - 1;
            $limit = ''.$up_num_limit.', 1';
        }
        $word_info = $this->where($where)->field('word_id,memory_tags,recall_tags')->limit($limit)->order($order)->select();
        if (!empty($word_info)) {
            $word_info['word_id'] = $word_info[0]['word_id'];
            $word_info['memory_tags'] = $word_info[0]['memory_tags'];
            $word_info['recall_tags'] = $word_info[0]['recall_tags'];
        }
        unset($where);
        if ($all_learn_num >= $word_num) {
            return V(2, '本次单词学习结束，进入下一环节');
        }
    
        if (($all_learn_num / $words_number) == 1) {
            return V(3, '本次单词学习结束，进入下一环节');
        }
        $data['word_num'] = $word_num;
        $data['learn_num'] = $all_learn_num + 1;
        $data['word_time'] = 5;
        $data['tag_is_selected'] = 0;
        if ($type_id == 1) {
            $data['tag_is_selected'] = $word_info['memory_tags'];
        }
        elseif ($type_id == 2) {
            $data['tag_is_selected'] = $word_info['recall_tags'];
        }

        $word_id = $word_info['word_id'];
        $where['id'] = $word_id;
        $word_info = $wordModel->getWordInfo($where);
        if ($type_id == 3 || $type_id == 4) {
            $word_length = strlen($word_info['words']);
            $data['word_time'] = $word_length * $spell_rate;
        }
        $data['true_time'] = $true_time;
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

    /**
     * 获单词测评
     * @param $unit_id int 单元id
     * @param $option
     */
    public function getLearnTest($unit_id, $all_learn_num, $word_num, $type_id, $is_continue) {
        $test_where_field = array(1 => 'memory_test', 2 => 'recall_test');
        $where_field = array(1 => 'is_memory', 2 => 'is_recall');
        $wordModel = D('Home/word');
        $courseWordModel = D('Home/CourseWord');
        $userModel = D('Home/User');
        $user_setting = $userModel->getLearnSetting();
        $words_number = $user_setting['words_number']; //分组个数

        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        $where[$test_where_field[$type_id]] = 0;
        //$where[$where_field[$type_id]] = 1;
        $word_info = $this->where($where)->field('word_id')->order('sort asc')->find();

        unset($where);
        if ($all_learn_num >= $word_num) {
            return V(2, '本单元单词测试结束');
        }
        if (($all_learn_num / $words_number) == 1 && $is_continue == 0) {
            return V(3, '本组单词测试结束');
        }
        $data['word_num'] = $word_num;
        $data['learn_num'] = $all_learn_num + 1;

        $word_id = $word_info['word_id'];
        $where['id'] = $word_id;
        $word_info = $wordModel->getWordInfo($where);
        unset($where);
        foreach ($word_info as $key => $value) {
            $data[$key] = $value;
        }
        //获取随机词义
        $where['cw.unit_id'] = $unit_id;
        $word_chinese_list = $courseWordModel->getUnitWord($where);
        foreach ($word_chinese_list as $key => $value) {
            $word_chinese_data[$value['word_id']] = $value;
        }
        unset($word_chinese_data[$data['id']]);
        $chinese_rand_array = array_rand($word_chinese_data, 3); //随机取7条词义
        array_push($chinese_rand_array, $data['id']); //把正确答案追加到数组
        shuffle($chinese_rand_array); //重新排列数组
        foreach ($chinese_rand_array as $k => $v) {
            if ($v == $data['id']) {
                $chinese_list[$k]['word_id'] = $data['id'];
                $chinese_list[$k]['words'] = $data['words'];
                $chinese_list[$k]['chinese'] = $data['chinese'];
                $chinese_list[$k]['answer_results'] = 1;
            } else {
                $chinese_list[$k]['word_id'] = $word_chinese_data[$v]['word_id'];
                $chinese_list[$k]['words'] = $word_chinese_data[$v]['words'];
                $chinese_list[$k]['chinese'] = $word_chinese_data[$v]['chinese'];
                $chinese_list[$k]['answer_results'] = 0;
            }
        }
        $data['chinese_list'] = $chinese_list;
  
        return V(1, '获取单词信息', $data);
    }
    
    //获取单元下面的单词数量
    public function getLearnUnitWordCount($where) {
        $where['user_id'] = UID;
        $count = $this->where($where)->count('id');
        return $count;
    }


    //验证是否已测试
    public function checkIsTest($unit_id) {
        $where['unit_id'] = $unit_id;
        $word_num = $this->getLearnUnitWordCount($where);
        if ($word_num == 0) {
            return V(0, '请先完成学前测试');
        } 
        return $word_num;
    }


}