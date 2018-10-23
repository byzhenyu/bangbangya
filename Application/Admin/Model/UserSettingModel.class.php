<?php
/**
 * 学习设置模型
 */
namespace Admin\Model;

use Think\Model;

class UserSettingModel extends Model {
    protected $insertFields = array('words_number', 'spell_rate', 'intelligent_number', 'true_time', 'user_id');
    protected $updateFields = array('words_number', 'spell_rate', 'intelligent_number', 'true_time', 'user_id');
    protected $_validate = array(
        array('words_number', 'require', '选择单词数量', 1, 'regex', 3),
        array('spell_rate', 'require', '选择拼写速率', 1, 'regex', 3),
        array('intelligent_number', 'require', '选择智能学习数量', 1, 'regex', 3),
        array('true_time', 'require', '选择正确单词显示时间', 1, 'regex', 3),
    );

    /**
     * 获取用户学习设置列表
     * @param
     * @return mixed
     */
    public function getUserSettingList($where, $field = false){
        if(!$field) $field = 's.*,u.nickname';
        $count = $this->alias('s')->join('__USER__ as u on s.user_id = u.user_id')->where($where)->count();
        $page = get_web_page($count);
        $list = $this->alias('s')->join('__USER__ as u on s.user_id = u.user_id')->field($field)->where($where)->limit($page['limit'])->order('s.id desc')->select();
        foreach($list as &$val){
            $val['spell_rate'] .= 's';
            $val['true_time'] .= 's';
        }
        return array('info' => $list, 'page' => $page['page']);
    }

    /**
     * @desc 获取用户学习设置信息
     * @param $where
     * @param string $field
     * @return bool|mixed
     */
    public function getSettingInfo($where, $field = ''){
        $setting = $this->where($where)->field($field)->find();
        if(!$setting) return false;
        return $setting;
    }

    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){
    }
    //更新操作前的钩子操作
    protected function _before_update(&$data, $option){
    }

}