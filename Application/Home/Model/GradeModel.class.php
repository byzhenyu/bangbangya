<?php
/**
 * @Description    班级模型
 * @Author         <527459901@qq.com>
 * @Date           2018/10/09
 */
namespace Home\Model;
use Think\Model;
class GradeModel extends Model {
    protected $selectFields = array('id', 'teacher_id', 'grade_code', 'grade_name', 'grade_desc','disabled','period_id','agent_id','student_number','status');

    /**
     * 判断班级名称是否重复
     **/
    public function checkGradeName($name){
        $where['teacher_id'] = TEACHER_ID;
        $where['grade_name'] = $name;
        $where['status'] = 1;
        $count = $this->where($where)->count();
        if ($count > 0) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * @desc 班级列表+作业列表
     * @param $where
     * @param bool $field
     * @return array
     */
    public function getGradeLists($where, $field = false, $sort='id desc'){
        if(!$field) $field = $this->selectFields;
        $count = $this->where($where)->count();
        $page = get_web_page($count);
        $list = $this->where($where)->field($field)->limit($page['limit'])->order($sort)->select();
        if($list){
            $WorkModel = D('Home/Homework');
            $field1 = 'h.id,h.teacher_id,h.class_id,h.unit_id,h.end_time,h.grade_id,c.class_name,u.unit,g.grade_name,g.student_number';
            $teacher_id = TEACHER_ID;
            foreach($list as &$v){
                $where1['h.grade_id'] = $v['id'];
                $where1['h.teacher_id'] = $teacher_id;
                $v['worklist'] = $WorkModel->getHomeworkList($where1,$field1);
            }
            return V(1, '班级列表',$list);
        }
        else{
            return V(1, '未查询到');
        }

    }

    /**
     * @desc 班级列表
     * @param $where
     * @param bool $field
     * @return array
     */
    public function getGradeList($where, $field = false, $sort='id desc'){
        if(!$field) $field = $this->selectFields;
        $count = $this->where($where)->count();
        $page = get_web_page($count);
        $list = $this->where($where)->field($field)->limit($page['limit'])->order($sort)->select();
        return array(
            'list' => $list,
            'page' => $page['page'],
        );
    }

    /**
     * @desc 班级详情
     * @param $where
     * @param bool $field
     * @return array
     */
    public function getGradeInfo($where, $field = false){
        if(!$field) $field = $this->selectFields;
        $res = $this->where($where)->field($field)->find();
        return $res;
    }

    /**
     * @desc 获取班级指定字段
     * @param $where
     * @param $data
     * @return array
     */
    public function getGradeField($where,$data){
        $field = $this->where($where)->getField($data);
        return $field;
    }

    /**
     * @desc 修改班级指定字段
     * @param $where
     * @param $data
     * @return array
     */
    public function setGrade($where,$data){
        $save = $this->where($where)->save($data);
        return $save;
    }
}