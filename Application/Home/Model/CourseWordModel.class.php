<?php
/**
 * 课程单元模型
 */
namespace Home\Model;

use Think\Model;

class CourseWordModel extends Model {
    protected $selectFields = array('id', 'class_id', 'unit_id', 'word_id', 'sort');
    
    /**
     * 获取单词个数
     * @param $where array
     * @param $option
     */
    public function getCourseWordInfo($where,$field=null) {
        if ($field == null) {
            $field = $this->selectFields;
        }
        $info = $this->field($field)->where($where)->find();
        return $info;
    }

    /**
     * 获取测评单词内容
     * @param $unit_id int 单元id
     * @param $count int  已完成单词个数
     * @param $use_time string 完成测试用时
     * @param $type_id int  类型 
     * @param $option
     */
    public function getTestWord($unit_id, $count, $use_time, $type_id) {

        $testResultModel = D('Home/UserTestResult');
        $wordModel = D('Home/Word');
        $testCountModel = D('Home/TestCount');
        $where['unit_id'] = $unit_id;
        $word_list = $this->where($where)->field('word_id')->select();
        unset($where);
        if (empty($word_list)) {
            return V(0, '本单元没有测试单词');
        }
        $has_test_count = $testResultModel->getTestCount($unit_id, $type_id);
        $count = $count == 0 ? $has_test_count : $count;
        $data['word_num'] = count($word_list);
        $word_id = $word_list[$count]['word_id'];
        $word_model = D('Home/Word');
        $where['id'] = $word_id;
        $word_info = $wordModel->getWordInfo($where);
        if (empty($word_info)) {
            $testCountModel->addTestCount($unit_id, count($word_list), $use_time, $type_id);
            $pass_score = C('THROUGH_PASS_SCORE'); //合格分数
            $last_data['test_score'] = $testResultModel->getPass($unit_id, count($word_list)); //测试成绩
            $last_data['is_qualified'] = 0;
            if ($last_data['test_score'] >= $pass_score) {
                $last_data['is_qualified'] = 1;
            }
            return V(2, '下面没有单词了！', $last_data);
        }
        foreach ($word_info as $key => $value) {
            $data[$key] = $value;
        }
        //获取单词统计情况
        $test_result = $testResultModel->getUnitTestResult($unit_id, $type_id);
        foreach ($test_result as $key => $value) {
            $data[$key] = $value;
        }
        $data['word_time'] = 15;
        return V(1, '获取单词信息', $data);
    }

    /**
     * 获取听力闯关单词内容
     * @param $unit_id int 单元id
     * @param $count int  已完成单词个数
     * @param $type_id int  类型 
     * @param $option
     */
    public function getHearingPassWord($unit_id, $count, $type_id) {
        $hearingPassModel = D('Home/HearingPass');
        $wordModel = D('Home/Word');
        $where['unit_id'] = $unit_id;
        $word_list = $this->where($where)->field('word_id')->select();
        unset($where);
        if (empty($word_list)) {
            return V(0, '本单元没有测试单词');
        }
        $data['word_num'] = count($word_list);
        $word_id = $word_list[$count]['word_id'];
        $where['id'] = $word_id;
        $word_info = $wordModel->getWordInfo($where);
        unset($where);
        if (empty($word_info)) {
            $pass_score = C('THROUGH_PASS_SCORE'); //合格分数
            $last_data['test_score'] = $hearingPassModel->getPass($unit_id, $type_id, count($word_list)); //测试成绩
            $last_data['is_qualified'] = 0;
            if ($last_data['test_score'] >= $pass_score) {
                $last_data['is_qualified'] = 1;
            }
            return V(2, '下面没有单词了！', $last_data);
        }
        foreach ($word_info as $key => $value) {
            $data[$key] = $value;
        }
        //获取单词统计情况
        $test_result = $hearingPassModel->getHearingPassResult($unit_id);
        foreach ($test_result as $key => $value) {
            $data[$key] = $value;
        }
        //获取单词倒计时时间
        $unit_through = C('UNIT_THROUGH');
        $unit_through_array = explode('|', $unit_through);
        $data['word_time'] = $unit_through_array[$type_id];

        //获取随机词义
        $where['cw.unit_id'] = $unit_id;
        $word_chinese_list = $this->getUnitWord($where);
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
        $data['right_time'] = 1;

        return V(1, '获取单词信息', $data);
    }

    //根据条件获取单元单词
    public function getUnitWord($where, $field, $order = 'cw.id') {
        if ($field == null) {
            $field = 'cw.word_id, w.words, w.phonetic_alphabet, w.pronunciation, w.syllable, w.chinese';
        }
        $list = $this->alias('cw')
            ->join('__WORD__ w ON cw.word_id = w.id','left')
            ->field($field)
            ->where($where)
            ->order($order)
            ->select();
        return $list;
    }

    /**
     * 单元词汇
     * @param $where
     * @param $field
     * @return mixed
     */
    public function getUnitWordByPage($where, $field, $order = 'cw.id asc') {
        if ($field == null) {
            $field = 'cw.word_id, w.words, w.phonetic_alphabet, w.pronunciation, w.syllable, w.chinese';
        }
        $count = $this->where($where)->count();
        $page = get_web_page($count, 10);
        $list = $this->alias('cw')
            ->join('__WORD__ w ON cw.word_id = w.id','left')
            ->field($field)
            ->where($where)
            ->limit($page['limit'])
            ->order($order)
            ->select();

        return array(
            'list'=>$list,
            'page'=>$page['page']
        );
    }

}