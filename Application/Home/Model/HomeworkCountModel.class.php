<?php
/**
 * @Description    家庭作业测试结果统计模型
 * @Author         <527459901@qq.com>
 * @Date           2018/10/10
 */
namespace Home\Model;
use Think\Model;
class HomeworkCountModel extends Model {
    protected $selectFields = array('id', 'user_id', 'homework_id', 'score','use_time','test_time','is_pass','type_id');

    /**
     * 测试结果列表分页
     * @param $where
     * @param bool $field
     * @return array
     **/
    public function getHomeworkCountListByPage($where,$field=false){
        if(!$field) $field = $this->selectFields;
        $count = $this
            ->alias('h')
            ->join('ln_user as u on h.user_id=u.user_id','left')
            ->where($where)
            ->count();
        $page = get_web_page($count);
        $list = $this
            ->alias('h')
            ->join('ln_user as u on h.user_id=u.user_id','left')
            ->where($where)
            ->field($field)
            ->limit($page['limit'])
            ->select();
        return array(
            'list' => $list,
            'page' => $page['page'],
        );
    }

    /**
     * 测试结果列表
     * @param $where
     * @param bool $field
     * @return array
     **/
    public function getHomeworkCountList($where,$field=false){
        if(!$field) $field = $this->selectFields;
        $list = $this->where($where)->field($field)->select();
        return $list;
    }

    /**
     * 测试结果指定字段
     **/
    public function getHomeworkCountField($where,$field){
        $info = $this->where($where)->getField($field,true);
        return $info;
    }

    /**
     * 获取作业测试统计
     */
    public function getHomeworkCountInfo($where, $fields) {
        if(is_null($fields)){
            $fields = $this->selectFields;
        }
        $info = $this->where($where)->field($fields)->find();
        return $info;
    }
}