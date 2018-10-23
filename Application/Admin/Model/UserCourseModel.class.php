<?php
/**
 * 用户学习进度模型
 */
namespace Admin\Model;

use Think\Model;

class UserCourseModel extends Model {
    protected $insertFields = array('user_id', 'period_id', 'class_id', 'unit_id', 'learned_time', 'master_word_num');
    protected $updateFields = array('user_id', 'period_id', 'class_id', 'unit_id', 'learned_time', 'master_word_num', 'id');
    protected $_validate = array(
        array('unit_id', 'require', '单元不能为空！', 1, 'regex', 3),
    );

    /**
     * @desc 用户单元/单词熟识程度统计
     * @param $where
     * @param bool $field
     * @return mixed
     */
    public function userCourseInfo($where, $field = false){
        if(!$field) $field = 'learned_time,master_word_num';
        $res = $this->where($where)->field($field)->find();
        $unitWord = D('Admin/CourseWord')->getCourseWordsCount($where['unit_id']);//单元单词个数
        $start = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $end = mktime(23,59,59,date('m'),date('d'),date('Y'));
        $todayWhere = array('unit_id' => $where['unit_id'], 'test_result' => 1, 'user_id' => $where['user_id'], 'test_time' => array('between', array($start, $end)));
        $todayMaster = D('Admin/UserTestResult')->statisticWordsNum($todayWhere);
        $res['learned_time'] = ceil($res['learned_time']/60);
        $res['master_word_num'] = !$res['master_word_num'] ? 0 : $res['master_word_num'];
        $res['unit_word'] = $unitWord;
        $res['today_master'] = $todayMaster;
        $res = array_map('intval', $res);
        return $res;
    }

    /**
     * @desc 用户课程单元相关单词/听力测试/默写测试统计
     * @param $where array 检索条件
     * @param bool $field 字段
     * @param string $order 排序顺序
     * @return mixed
     */
    public function listUserCourseStatistic($where, $field = false, $order = ''){
        if(!$field) $field = 'c.*,s.class_name,u.unit';
        $number = $this->alias('c')->where($where)->count();
        $page = get_web_page($number);
        $list = $this->alias('c')->where($where)->join('__COURSE_CLASS__ as s on c.class_id = s.id')->join('__COURSE_UNIT__ as u on c.unit_id = u.id')->field($field)->order($order)->limit($page['limit'])->select();
        $unitWords = D('Admin/CourseWord');
        $testResult = D('Admin/UserTestResult');
        $start = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $end = mktime(23,59,59,date('m'),date('d'),date('Y'));
        $todayWhere = array('test_result' => 1, 'test_time' => array('between', array($start, $end)), 'user_id' => $where['c.user_id']);
        foreach($list as &$val){
            $val['unit_words'] = $unitWords->getCourseWordsCount($val['unit_id']);
            $todayWhere['unit_id'] = $val['unit_id'];
            $val['today_master'] = $testResult->statisticWordsNum($todayWhere);
        }
        unset($val);
        return array(
            'info' => $list,
            'page' => $page['page']
        );
    }

    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){

    }
    //更新操作前的钩子操作
    protected function _before_update(&$data, $option){
    }

}