<?php
/**
 * @Description    教师模型
 * @Author         <527459901@qq.com>
 * @Date           2018/10/09
 */
namespace Home\Model;
use Think\Model;
class TeacherModel extends Model
{
    protected $selectFields = array('id','user_id','agent_id', 'teacher_name', 'mobile','period','points','reg_time','disabled','status');

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
     * @desc 教师详情
     * @param $where
     * @param bool $field
     * @return array
     */
    public function getTeacherInfo($where, $field = false){
        if(!$field) $field = $this->selectFields;
        $res = $this->where($where)->field($field)->find();
        return $res;
    }

    /**
     * @desc 获取教师指定字段
     * @param $where
     * @param $data
     * @return array
     */
    public function getTeacherField($where,$data){
        $field = $this->where($where)->getField($data);
        return $field;
    }

    /**
     * @desc 修改教师指定字段
     * @param $where
     * @param $data
     * @return array
     */
    public function setTeacher($where,$data){
        $save = $this->where($where)->save($data);
        return $save;
    }
}