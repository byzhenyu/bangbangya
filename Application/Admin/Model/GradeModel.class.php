<?php
/**
 */

namespace Admin\Model;


use Think\Model;

class GradeModel extends Model
{
    protected $selectFields = array();

    protected $_validate = array(

    );

    /**
     * @desc 班级详情
     * @param $where
     * @param bool $field
     * @return mixed
     */
    public function getGradeInfo($where, $field = false){
        if(!$field) $field = '*';
        $res = $this->where($where)->field($field)->find();
        return $res;
    }
}