<?php
/**
 * @Author: Marte
 * @Date:   2018-10-29 20:18:52
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-10-29 22:20:16
 */
namespace Home\Model;
use Think\Model;
class TaskModel extends Model
{
    protected $selectFields = array('id','title','category_id', 'mobile_type', 'end_time','price','task_num','total_price','link_url','validate_words','remark','is_show','audit_status','audit_info','add_time','status','user_id');
    /**
     * 任务类型
     * @param $where
     * @return array
     */
    public function getTaskCategory($where = [], $field = '', $order = 'id ASC') {
        if(is_null($field)){
            $field = $this->selectFields;
        }
        $where = array('status' => 1);
        $list = M('task_category')->field($field)
              ->where($where)->limit($page['limit'])
              ->order($order)
              ->select();
        return $list;
    }
    /**
     * 任务详情
     * @param $where
     * @return array
     */
    public function getTaskList($where = [], $field = '', $order = 't.add_time desc') {
        /*任务状态查询条件*/
        $where[] = array('u.disabled' => 1,'t.end_time' =>array('EGT',NOW_TIME),'t.task_num'=>array('GT',0),'t.status'=> 1,'t.audit_status' =>1,'t.is_show' =>1);
        $count = $this->alias('t')
              ->join('__TASK_CATEGORY__ as c on t.category_id = c.id', 'LEFT')
              ->join('__USER__ as u on t.user_id = u.user_id')
              ->field('t.*,c.id as category_id,c.category_name')
              ->where($where)
              ->count();
        $page = get_page($count);
        $list = $this->alias('t')
              ->join('__TASK_CATEGORY__ as c on t.category_id = c.id', 'LEFT')
              ->join('__USER__ as u on t.user_id = u.user_id')
              ->field('t.*,c.id as category_id,c.category_name')
              ->where($where)->limit($page['limit'])
              ->order($order)
              ->select();
        return array(
            'list' => $list,
            'page' => $page['page']
        );
    }
    /**
     * 任务条件搜索
     * @param $where
     * @return where
     */
    public function getTopShop($where = [],$field= '',$order=' shop_type DESC')
    {

    }
}