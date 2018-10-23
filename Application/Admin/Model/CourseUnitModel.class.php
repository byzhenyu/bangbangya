<?php
/**
 * 课程单元模型
 */
namespace Admin\Model;

use Think\Model;

class CourseUnitModel extends Model {
    protected $insertFields = array('class_id', 'unit', 'sort');
    protected $updateFields = array('class_id', 'unit', 'sort', 'word_num', 'id');
    protected $selectFields = array('class_id', 'unit', 'sort', 'word_num', 'id');
    protected $_validate = array(
        array('class_id', 'require', '课程分类不能为空！', 1, 'regex', 3),
        array('unit', 'require', '单元名称不能为空！', 1, 'regex', 3),
        array('unit', 'checkCourseUnit', '单元名称不能超过30字！', 2, 'callback', 3),
    );

    /**
     * 验证单元名称长度
     */
    protected function checkCourseUnit($data) {
        $length = mb_strlen($data, 'utf-8');
        if ($length > 30) {
            return false;
        }
        return true;
    }
    /**
     * 获取单元列表
     * @param
     * @return mixed
     */
    public function getCourseUnitList($where, $field = false){
        if(is_null($field)){
            $field = $this->selectFields;
        }
        $count = $this->where($where)->count();
        $page = get_page($count);
        $list = $this->where($where)
            ->field($field)
            ->limit($page['limit'])
            ->order('id asc')
            ->select();
        return array(
            'info'=>$list,
            'page'=>$page['page']
        );
    }

    /**
     * @Desc 添加单词单词数量改变
     * @param $unit_id
     * @return bool
     */
    public function changeCourseUnitWords($unit_id){
        $res = $this->where(array('id' => $unit_id))->setInc('word_num');
        return $res;
    }

    /**
     * @desc 课程单元列表
     * @return mixed
     */
    public function listCourseUnit(){
        $res = $this->select();
        return $res;
    }

    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){
        $unit = $data['unit'];
        $find = $this->where(array('unit' => $unit))->find();
        if($find){
            $this->error = '单元名称已经存在！';
            return false;
        }
    }
    //更新操作前的钩子操作
    protected function _before_update(&$data, $option){
        $unit = $data['unit'];
        $find = $this->where(array('unit' => $unit, 'id' => array('neq', $data['id'])))->find();
        if($find){
            $this->error = '单元名称已经存在！';
            return false;
        }
    }

}