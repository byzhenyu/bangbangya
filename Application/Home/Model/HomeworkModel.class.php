<?php
/**
 * @Description    家庭作业模型
 * @Author         <527459901@qq.com>
 * @Date           2018/10/10
 */
namespace Home\Model;
use Think\Model;
class HomeworkModel extends Model {
    protected $selectFields = array('id', 'teacher_id', 'class_id', 'unit_id', 'title','desc','end_time','grade_id','homework_type','create_time');

    /**
     * @desc 作业列表(带分页)
     * @param $where
     * @param bool $field
     * @return array
     */
    public function getHomeworkListPage($where, $field = false, $sort='create_time desc'){
        if(!$field) $field = $this->selectFields;
        $count = $this
            ->alias('h')
            ->join('ln_course_class as c on h.class_id=c.id','left')
            ->join('ln_course_unit as u on h.unit_id=u.id','left')
            ->join('ln_grade as g on h.grade_id=g.id','left')
            ->where($where)
            ->count();
        $page = get_web_page($count);
        $list = $this
            ->alias('h')
            ->join('ln_course_class as c on h.class_id=c.id','left')
            ->join('ln_course_unit as u on h.unit_id=u.id','left')
            ->join('ln_grade as g on h.grade_id=g.id','left')
            ->where($where)
            ->field($field)
            ->limit($page['limit'])
            ->order($sort)
            ->select();
        if(empty($list)){
            return array('list' =>array(), 'page' => $page['page'],);
        }
        $time = time();
        $UserM = M('User');
        $homework_countM = M('homework_count');
        foreach($list as &$v){
            //是否截止
            if($time >= $v['end_time']){
                $v['time_status'] = '已截止';
            }
            else{
                $v['time_status'] = '未截止';
            }
            //截止时间
            $v['end_time'] = date('m月d日 H:i',$v['end_time']);
            //完成人数
            $user_id = $UserM->where(array('grade_id'=>$v['grade_id']))->getField('user_id',true);
            if(empty($user_id)){
                $arr['user_id'] = 0;
            }
            else{
                $arr['user_id'] = array('in',$user_id);
            }
            $arr['homework_id'] = array('eq',$v['id']);
            $arr['is_pass'] = 1;
            $v['count'] = $homework_countM->where($arr)->count();
            //百分比
            $round = ($v['count'] / $v['student_number']) * 100;
            $v['percent'] =  round($round,2).'%';
        }
        return array(
            'list' => $list,
            'page' => $page['page'],
        );
    }

    /**
     * @desc 作业列表
     * @param $where
     * @param bool $field
     * @return array
     */
    public function getHomeworkList($where, $field = false, $sort='create_time desc'){
        if(!$field) $field = $this->selectFields;
        $list = $this
            ->alias('h')
            ->join('ln_course_class as c on h.class_id=c.id','left')
            ->join('ln_course_unit as u on h.unit_id=u.id','left')
            ->join('ln_grade as g on h.grade_id=g.id','left')
            ->where($where)
            ->field($field)
            ->order($sort)
            ->select();
        if(empty($list)){
            return array();
        }
        $time = time();
        $UserM = M('User');
        $homework_countM = M('homework_count');
        foreach($list as &$v){
            //是否截止
            if($time >= $v['end_time']){
                $v['time_status'] = '已截止';
            }
            else{
                $v['time_status'] = '未截止';
            }
            //截止时间
            $v['end_time'] = date('m月d日 H:i',$v['end_time']);
            //完成人数
            $user_id = $UserM->where(array('grade_id'=>$v['grade_id']))->getField('user_id',true);
            if(empty($user_id)){
                $arr['user_id'] = 0;
            }
            else{
                $arr['user_id'] = array('in',$user_id);
            }
            $arr['homework_id'] = array('eq',$v['id']);
            $arr['is_pass'] = 1;
            $v['count'] = $homework_countM->where($arr)->count();
            //百分比
            $round = ($v['count'] / $v['student_number']) * 100;
            $v['percent'] =  round($round,2).'%';
        }
        return $list;
    }

    /**
     * @desc 作业列表(带分页)
     * @param $where
     * @param bool $field
     * @return array
     */
    public function getHomeworkListPageNot($where,$field=null,$sort='create_time desc'){
        if(!$field) $field = $this->selectFields;
        $count = $this
            ->alias('h')
            ->join('ln_course_class as c on h.class_id=c.id','left')
            ->join('ln_course_unit as u on h.unit_id=u.id','left')
            ->where($where)
            ->count();
        $page = get_web_page($count);
        $list = $this
            ->alias('h')
            ->join('ln_course_class as c on h.class_id=c.id','left')
            ->join('ln_course_unit as u on h.unit_id=u.id','left')
            ->where($where)
            ->field($field)
            ->limit($page['limit'])
            ->order($sort)
            ->select();
        return array(
            'list' => $list,
            'page' => $page['page'],
        );
    }

    /**
     * @desc 获取作业指定字段
     **/
    public function getHomeworkField($where,$data){
        $field = $this->where($where)->getField($data);
        return $field;
    }

}