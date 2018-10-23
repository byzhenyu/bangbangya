<?php
/**
 * 单元闯关记录表
 */
namespace Home\Model;

use Think\Model;

class HearingPassModel extends Model {
    protected $selectFields = array('id', 'user_id', 'unit_id', 'word_id', 'words', 'use_time',
    	'pass_level', 'add_time');
    protected $insertFields = array('user_id', 'unit_id', 'word_id', 'words', 'use_time',
        'pass_level', 'add_time');
   
    
    /**
     * 插入单元闯关接口
     * @param $unit_id int 单元id
     * @param $word_id int 单词id
     * @param $use_time int  用的单词时间，单位s
     * @param $result int  结果 0错误 1正确
     * @param $type_id int  类型 0新手 1高手 2学霸
     * @param $option
     */
    public function insertHearingPass($unit_id, $word_id, $use_time, $result, $type_id) {
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
    	$data['use_time'] = $use_time;
        $data['pass_level'] = $type_id;
    	$data['add_time'] = NOW_TIME;
    	$this->add($data);
    }

    /**
     * 获取听力闯关结果情况
     * @param $unit_id int 单元id
     */
    public function getHearingPassResult($unit_id) {
        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        $result_list = $this->field('id, use_time, result')->where($where)->select();
        $use_time = 0;
        $wrong_num = 0;
        $right_num = 0;
        $finish_num = 0;
        if (!empty($result_list)) {
            foreach ($result_list as $key => $value) {
                $use_time += $value['use_time'];
                if ($value['result'] == 1) {
                    $right_num++;
                } else {
                    $wrong_num++;
                }
            }
            $finish_num = count($result_list);
        }
        
        return array('finish_num'=>$finish_num, 'use_time'=>$use_time, 'right_num'=>$right_num, 'wrong_num'=>$wrong_num);
    }


    /**
     * 获取听力通过率/得分
     * @param $unit_id int 单元id
     * @param $type_id int 类型 0新手 1高手 2学霸
     * @param $all_word int 本单元单词总数
     */
    public function getPass($unit_id, $type_id, $all_word) {
        $where['user_id'] = UID;
        $where['unit_id'] = $unit_id;
        $where['pass_level'] = $type_id;
        $where['result'] = 1;
        $right_num = $this->where($where)->count();
        $score = ($right_num / $all_word) * 100;
        return round($score);
    }
}