<?php
/**
 * 测试结果统计
 */
namespace Home\Model;

use Think\Model;

class TestCountModel extends Model {
    protected $selectFields = array('id', 'user_id', 'unit_id', 'word_all_num', 'right_num', 'wrong_num',
    	'score', 'use_time', 'write_time', 'test_time', 'is_pass');
    protected $insertFields = array('user_id', 'unit_id', 'word_all_num', 'right_num', 'wrong_num',
        'score', 'use_time', 'write_time', 'test_time', 'is_pass');
   
    /**
     * 获取测试列表和单元名称
     */
    public function getTestListAndUnitName($where) {
        $field = 'u.class_id, u.unit, t.unit_id, t.right_num, t.wrong_num, t.score, t.use_time, t.test_time, t.is_pass, t.type_id';
        $count = $this->alias('t')
            ->join('__COURSE_UNIT__ as u on t.unit_id = u.id')
            ->where($where)
            ->count('t.id');
        $page = get_web_page($number);
        $list = $this->alias('t')
            ->join('__COURSE_UNIT__ as u on t.unit_id = u.id')
            ->field($field)
            ->where($where)
            ->limit($page['limit'])
            ->select();
        return array(
            'list' => $list, 
            'page' => $page['page']
        );
    }

    /**
     * 获取测试统计
     */
    public function getTestCountInfo($where, $fields) {
        if(is_null($fields)){
            $fields = $this->selectFields;
        }
        $info = $this->where($where)->field($fields)->find();
        return $info;
    }

    /**
     * 记录测评统计
     * @param $unit_id int 单元id
     * @param $word_all_num 单元单词总数
     * @param $use_time int  用的单词时间，单位s
     * @param $type_id int  类型 
     * @param $option
     */
    public function addTestCount($unit_id, $word_all_num, $use_time, $type_id) {
        $testResultModel = D('Home/UserTestResult');
        $test_result = $testResultModel->getUnitTestResult($unit_id, $type_id); //获取测评结果
        $test_score = $testResultModel->getPass($unit_id, $word_all_num); //获得测试成绩
        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        $where['type_id'] = $type_id;
        //先删除原单词测试统计
        $this->where($where)->delete();
        unset($where);
        $data = array();
        $data['user_id'] = UID;
        $data['unit_id'] = $unit_id;
        $data['word_all_num'] = $word_all_num;
        $data['right_num'] = $test_result['right_num'];
        $data['wrong_num'] = $test_result['wrong_num'];
        $data['score'] = $test_score;
        $data['use_time'] = $use_time;
        $data['type_id'] = $type_id;
        $data['test_time'] = NOW_TIME;
        $pass_score = C('THROUGH_PASS_SCORE'); //合格分数
        if ($test_score >= $pass_score) {
            $data['is_pass'] = 1;
        }
        $this->add($data);
    }
    
    /**
     * 验证是否进行了某种测试
     */
    public function checkIsTest($unit_id, $type_id = 0) {
        $where['user_id'] = UID;
        $where['unit_id'] = $unit_id;
        $where['type_id'] = $type_id;
        $count = $this->where($where)->count();
        return $count;
    }

    /**
     * 判断单元单词是否已完成
     * @param $unit_id int 单元id
     */
    public function checkUnitHasLearnd($unit_id) {
        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        $info = $this->getTestCountInfo($where, 'wrong_num');
        if (empty($info) || $info['wrong_num'] != 0) {
            return 0; //未学完
        }
        return 1; //已学完
    }
}