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
    protected $insertFields = array('id', 'title', 'category_id', 'mobile_type', 'end_time', 'price', 'task_num', 'total_price', 'link_url', 'validate_words', 'remark', 'audit_status', 'audit_info', 'add_time', 'status');
    protected $updateFields = array('id', 'title', 'category_id', 'mobile_type', 'end_time', 'price', 'task_num', 'total_price', 'link_url', 'validate_words', 'remark', 'audit_status', 'audit_info', 'add_time', 'status');

    protected $_validate = array(
        array('title', 'require', '请输入 任务标题 ', 1, 'regex', 3),
        array('title', '0,255', '您输入的 任务标题 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('category_id', 'require', '请输入  ', 1, 'regex', 3),
        array('mobile_type', 'require', '请输入 支持设备 (全部，安卓,苹果) ', 1, 'regex', 3),
        array('mobile_type', '0,10', '您输入的 支持设备 (全部，安卓,苹果) 过长，超过了 10 个字符数限制', 1, 'length', 3),
        array('end_time', 'require', '请输入 任务截止时间 ', 1, 'regex', 3),
        array('price', 'require', '请输入 出价金额（分） ', 1, 'regex', 3),
        array('task_num', 'require', '请输入 任务数量 ', 1, 'regex', 3),
        array('total_price', 'require', '请输入 总金额(分) ', 1, 'regex', 3),
        array('link_url', 'require', '请输入 链接地址 ', 1, 'regex', 3),
        array('link_url', '0,255', '您输入的 链接地址 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('validate_words', 'require', '请输入 验证文字内容 ', 1, 'regex', 3),
        array('validate_words', '0,255', '您输入的 验证文字内容 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('remark', 'require', '请输入 备注 ', 1, 'regex', 3),
        array('remark', '0,255', '您输入的 备注 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('audit_status', 'require', '请输入 审核状态0 未审核 1 审核通过 2 审核未通过 ', 1, 'regex', 3),
        array('audit_info', 'require', '请输入 审核理由说明 ', 1, 'regex', 3),
        array('audit_info', '0,255', '您输入的 审核理由说明 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('status', 'require', '请输入 1正常 0删除 ', 1, 'regex', 3),

    );

    public function getTaskList($where = [], $field = '', $order = 't.add_time desc') {
        $where['u.disabled'] = 1;  /*用户状态*/
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
              ->field('t.*,c.id as category_id,c.category_name,u.user_name')
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
    public function getTaskDetail($id = '')
    {
        if($id == '') return false;
        $list = $this->alias('t')
              ->join('__USER__ as u on t.user_id = u.user_id','LEFT')
              ->field('t.*,u.user_name')
              ->where('t.id ='.$id)
              ->find();
        return $list;
    }
   /**
     * 处理数据添加的时间 转换为时间戳
     * @param POST data
     * @param array()
     * @return data
     */
    public function updateTaskinfo($task_id = '', $data = [])
    {
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
