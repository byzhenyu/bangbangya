<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description    教师管理
 * @Author         (jicy QQ:510434563)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/08/28
 * @CreateBy       ${PRODUCT_NAME}
 */

namespace Admin\Model;


use Think\Model;

class TeacherModel extends Model
{
    protected $insertFields = array( 'agent_id', 'teacher_name', 'mobile','email','true_name','head_pic','school_id','school_name','period','points','reg_time','status','disabled');
    protected $updateFields = array('agent_id', 'teacher_name', 'mobile','email','true_name','head_pic','school_id','school_name','period','points','reg_time','disabled','status');
    protected $selectFields = array('id','agent_id', 'teacher_name', 'mobile','email','true_name','head_pic','school_id','school_name','period','points','reg_time','disabled','status');
    protected $_validate = array(


    );


    /**
     * 分页数据
     * @param $where
     * @return array
     */
    public function getTeacherByPage($where, $field = null, $order = 'id desc'){

        if(is_null($field)){
            $field = $this->selectFields;
        }

        $count = $this->where($where)->count();
        $page = get_page($count);

        $info = $this->field($field)->where($where)->limit($page['limit'])->order($order)->select();
        foreach ($info as $key=>$val){
            $user_name = M('User')->where(array('id'=>$val['user_id']))->getField('user_name');
            $info[$key]['login_name'] = $user_name;
        }
        return array(
            'info' => $info,
            'page' => $page['page'],
        );
    }
    /**
     * 详情
     * @param $where
     * @return array
     */
    public function getTeacherDetail($where,$field='') {
        if (!$field) {
            $field = '*';
        }
        $info = $this
            ->field($field)
            ->where($where)
            ->find();
        $info['login_name'] = M('User')->where(array('id'=>$info['user_id']))->getField('user_name');
        return $info;
    }
}