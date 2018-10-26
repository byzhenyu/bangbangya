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
class ComplaintModel extends Model {
    protected $insertFields = array('task_id', 'user_id', 'add_time', 'type', 'audit_status','status');
    protected $updateFields = array('id','task_id', 'user_id', 'add_time', 'type', 'audit_status','status');
    protected $selectFields = array('id','task_id', 'user_id', 'add_time', 'type', 'audit_status','status');
    protected $_validate = array(
        array('task_id', 'require', '任务ID不能为空!', 1, 'regex', 3),
        array('user_id', 'require', '投诉人ID不能为空!', 1, 'regex', 3),
        array('add_time', 'require', '投诉人时间不能为空!', 1, 'regex', 3),
        array('information', '0,255', '您输入的投内容过长!，超过了 255 个字符数限制', 1, 'length', 3),
        array('type', 'require', '没有传入类型!', 1, 'regex', 3),   
        array('status', 'require', '请输入 1正常 0删除 ', 1, 'regex', 3),

    );
     /**
     * @desc 申诉类查询
     * @param $where array 检索条件
     * @param $field string 展示字段
     * @param $sort string 排序顺序
     * @return mixed
     */
    public function getComplaintList($where, $field = false, $sort = 'add_time desc')
    {
        if(is_null($field)){
            $field = $this->selectFields;
        }
        /*status 状态查询 0 被删除的信息 1 正常显示的信息*/
        $where['status'] = 1;
        $count = $this->where($where)->count();
        $page = get_page($count, 10);
        $Complaintlist = $this->field($field)->where($where)->limit($page['limit'])->order($sort)->select();
        return array(
            'Complaintlist'=>$Complaintlist,
            'page'=>$page['page']
        );
    }
    /**
     * @ 申诉详情
     * @param $where
     * @param null $fields
     * @return arr 
     */
    public function getComplaintInfo($where=[], $fields = null)
    {
        $list = $this->alias('c')
                ->join('__USER__ as u on c.user_id = u.user_id','LEFT')
                ->join('__TASK__ as t on c.task_id = t.id','LEFT')
                ->field('c.*,u.user_name,u.mobile,t.title')
                ->where('c.id  = '.$where['id'])
                ->find();
        return $list;
    }
    /**
     * 修改投诉信息的状态
     * @param $shop_id
     * @param $is_admin
     * @return array
     */
    public function changeAuditStatus($id) {

        $ComplaintInfo = $this->where(array('id'=>$id))->field('audit_status, id')->find();
        $dataInfo = $ComplaintInfo['audit_status'] == 1 ? 0 : 1;
        $update_info = $this->where(array('id'=>$id))->setField('audit_status', $audit_status);
        if($update_info !== false){
            return V(1, '操作成功');
        } else {
            return V(0, '操作成功');
        }
    }

}
