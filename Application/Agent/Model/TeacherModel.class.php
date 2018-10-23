<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 * 
 * @Description    商品模型类
 * @Author         songgy<1661745274@qq.com>
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/03/17
 * @CreateBy       sublime
 * @ModifiedBy     
 */
namespace Agent\Model;
use Think\Model;
class TeacherModel extends Model{

    protected $insertFields = array('agent_id', 'user_id', 'teacher_name', 'mobile', 'email', 'true_name','head_pic', 'school_id', 'school_name','period','points','student_id','reg_time','status','disabled');
    protected $updateFields = array('agent_id', 'user_id', 'teacher_name', 'mobile', 'email', 'true_name','head_pic', 'school_id', 'school_name','period','points','student_id','reg_time','status','disabled');
    protected $selectFields = array('id','agent_id', 'user_id', 'teacher_name','mobile', 'email', 'true_name','head_pic', 'school_id', 'school_name','period','points','student_id','reg_time','status','disabled');
    protected $_validate = array(
        array('teacher_name', 'require', '教师账号不能为空', 1, 'regex', 1),
        array('mobile','isMobile','不是有效的手机号码',1,'function', 1),
        array('true_name', 'require', '真实姓名不能为空', 1, 'regex', 3),
        array('school_id', 'require', '请选择学校', 1, 'regex', 3),
        array('period', 'require', '请选择所教学段', 1, 'regex', 3),
    );

    public function listTeacherByPage($where, $field = false, $order = 't.reg_time desc'){
        if(!$field) $field = 't.*,u.user_name';
        $number = $this->alias('t')->where($where)->count();
        $page = get_web_page($number);
        $list = $this->alias('t')->join('__USER__ u on u.user_id = t.user_id')->where($where)->field($field)->limit($page['limit'])->order($order)->select();
        return array(
            'info' => $list,
            'page' => $page['page']
        );
    }




    protected function _before_insert(&$data, $option){
        $data['agent_id'] = AGENT_ID;
        $data['reg_time'] = NOW_TIME;
        $data['school_name'] = $this->getSchoolName($data['school_id']);
    }

    protected function _before_update(&$data, $options){

        $data['school_name'] = $this->getSchoolName($data['school_id']);
    }

    private function getSchoolName($school_id) {
        $name = M('School')->where(array('id'=>$school_id))->getField('school_name');
        return $name;
    }

}
