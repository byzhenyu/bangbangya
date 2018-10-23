<?php
/**
 * 听力闯关模型
 */
namespace Admin\Model;

use Think\Model;

class HearingPassModel extends Model {
    protected $insertFields = array();
    protected $updateFields = array();
    protected $_validate = array(
    );

    /**
     * @desc 获取听力闯关统计列表
     * @param $where array 统计条件
     * @param $field string|bool 字段
     * @param string $order 排序顺序
     * @return array
     */
    public function getHearingPassList($where, $field = false, $order = ''){
        if(!$field) $field = 'h.unit_id,u.class_id,g.class_name,u.unit';
        $unitNumberList = $this->alias('h')->where($where)->field('unit_id')->group('unit_id')->select();
        $number = count($unitNumberList);
        $page = get_web_page($number);
        $unitList = $this->alias('h')->where($where)->field($field)->group('h.unit_id')->join('__COURSE_UNIT__ as u on h.unit_id = u.id')->join('__COURSE_CLASS__ as g on g.id = u.class_id')->limit($page['limit'])->order($order)->select();
        foreach($unitList as &$val){
            $unitWordsStatistic = $this->statisticUnitWords($val['unit_id']);
            $keys = array_keys($unitWordsStatistic);
            foreach($keys as &$k_val){
                $val[$k_val] = $unitWordsStatistic[$k_val];
            }
        }
        unset($val);
        return array(
            'info' => $unitList,
            'page' => $page['page']
        );
    }

    /**
     * @desc 返回单元下的统计字段
     * @param $unit_id int 单元id
     * @return array
     */
    private function statisticUnitWords($unit_id){
        $wh = array('unit_id' => $unit_id);
        $resList = $this->where($wh)->select();
        $true_num = 0;
        $wrong_num = 0;
        $level1 = 0;
        $level2 = 0;
        $level3 = 0;
        $accumulate_time = 0;
        foreach($resList as &$val){
            $str = 'level'.$val['pass_level'];
            $$str++;
            if($val['result']){
                $true_num ++ ;
            }
            else{
                $wrong_num ++ ;
            }
            $accumulate_time += $val['use_time'];
        }
        unset($val);
        //$accumulate_time = ceil($accumulate_time / 60);
        return array(
            'level1' => $level1,
            'level2' => $level2,
            'level3' => $level3,
            'true_num' => $true_num,
            'wrong_num' => $wrong_num,
            'accumulate_time' => $accumulate_time
        );
    }

    /**
     * @desc 获取单元下的听力闯关列表
     * @param $where array 检索条件
     * @param bool $field
     * @param string $order
     * @return array
     */
    public function getUnitWordsList($where, $field = false, $order = ''){
        if(!$field) $field = 'h.*,w.words,u.unit';
        $number = $this->alias('h')->join('__WORD__ as w on h.word_id = w.id')->join('__COURSE_UNIT__ as u on h.unit_id = u.id')->where($where)->count(1);
        $page = get_web_page($number);
        $list = $this->alias('h')->join('__WORD__ as w on h.word_id = w.id')->join('__COURSE_UNIT__ as u on h.unit_id = u.id')->where($where)->field($field)->limit($page['limit'])->order($order)->select();
        foreach($list as &$val){
            $val['add_time'] = time_format($val['add_time']);
        }
        unset($val);
        return array(
            'info' => $list,
            'page' => $page['page']
        );
    }
}