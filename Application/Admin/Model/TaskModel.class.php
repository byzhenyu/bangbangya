<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;

/**
 * 任务表模型
 */
class TaskModel extends Model {
    protected $insertFields = array('id', 'title', 'user_id',  'category_id', 'mobile_type', 'end_time', 'price', 'task_num', 'task_zong', 'total_price', 'link_url', 'look_num', 'validate_words', 'remark', 'is_show', 'audit_status', 'audit_info', 'add_time', 'status','top', 'top_time', 'recommend', 're_time', 'discard_id');
    protected $updateFields = array('id', 'title', 'user_id',  'category_id', 'mobile_type', 'end_time', 'price', 'task_num', 'task_zong', 'total_price', 'link_url', 'look_num', 'validate_words', 'remark', 'is_show', 'audit_status', 'audit_info', 'add_time', 'status','top', 'top_time', 'recommend', 're_time', 'discard_id');

    protected $_validate = array(

        array('audit_status', array(0,1,2), '审核状态非法', 1, 'in', 5),
        array('audit_info', 'require', '请输入 审核理由说明 ', 1, 'regex', 5),
        array('audit_info', '0,30', '您输入的 审核理由过长，超过了30个字符数限制', 1, 'length', 5),

    );
    //审核扣钱

    public function getTaskList($where = [], $field = '', $order = 't.add_time desc') {
        $where['u.disabled'] = 1;  /*用户状态*/
        $where['t.is_show'] = 1;
        $count = $this->alias('t')
              ->join('__TASK_CATEGORY__ as c on t.category_id = c.id', 'LEFT')
              ->join('__USER__ as u on t.user_id = u.user_id')
              ->field('t.*,c.id as category_id,c.category_name,u.user_name')
              ->where($where)
              ->count();
        $page = get_page($count);
        $list = $this->alias('t')
              ->join('__TASK_CATEGORY__ as c on t.category_id = c.id', 'LEFT')
              ->join('__USER__ as u on t.user_id = u.user_id')
              ->field('t.*,c.id as category_id,c.category_name,u.nick_name')
              ->where($where)->limit($page['limit'])
              ->order($order)
              ->select();
        return array(
            'list' => $list,
            'page' => $page['page']
        );
    }
     /**
     * 查看任务详情
     * @param $id task id
     * @return data
     */
    public function getTaskDetail($id = 0) {

        $list = $this->alias('t')
              ->join('__USER__ as u on t.user_id = u.user_id','LEFT')
              ->join('__TASK_CATEGORY__ c on t.category_id = c.id', 'LEFT')
              ->field('t.*,u.nick_name,u.total_money,c.category_name')
              ->where('t.id ='.$id)
              ->find();
        if ($list) {
            $step = D('TaskStep')->getTaskStepList(array('task_id'=>$id));
            $c = 0;
            $s = 0;
            foreach ($step as $k=>$v) {
                if ($v['type'] == 2) {
                    $list['checkInfo'][$c] = $v;
                    $c++;
                } else {
                    $list['stepInfo'][$s] = $v;
                    $s++;
                }

            }

        } else {
            $list = [];
        }
        return $list;
    }
   /**
     * 处理数据添加的时间 转换为时间戳
     * @param POST data
     * @param array()
     * @return data
     */
   public function updateTaskinfo($task_id = '', $data = []) {
        if($task_id == '') return false;
        $result = $this->where(' id = '.$task_id) ->save($data);
        return $result;
   }
    /**
     * 处理数据添加的时间 转换为时间戳
     * @param POST data
     * @param array()
     * @return data
     */
    public function timeToTimestamp($data)
    {
        $data['add_time'] = strtotime($data['add_time']);
        $data['end_time'] = strtotime($data['end_time']);
        return $data;
    }

}
