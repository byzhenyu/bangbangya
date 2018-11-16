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
    protected $insertFields = array('task_id', 'user_id', 'add_time', 'audit_status','status');
    protected $updateFields = array('id','audit_status');
    protected $selectFields = array('id','task_id', 'user_id', 'add_time', 'audit_status','status');
    protected $_validate = array(
        array('audit_status', 'require', '处理状态不能为空!', 1, 'regex', 3),

    );
     /**
     * @desc 申诉类查询
     * @param $where array 检索条件
     * @param $field string 展示字段
     * @param $sort string 排序顺序
     * @return mixed
     */
    public function getComplaintList($where, $field = false, $sort = 'c.audit_status ASC')
    {
        /*status 状态查询 0 被删除的信息 1 正常显示的信息*/
        $where['c.status'] = 1;
        $count = $this->alias('c')
                ->join('__USER__ as u1 on u1.user_id = c.user_id ','LEFT')
                ->join( '__USER__ as u2 on u2.user_id = c.be_user_id ')
                ->where($where)
                ->count();
        $page = get_page($count, 10);
        $Complaintlist = $this->alias('c')
                          ->join('__USER__ as u1 on u1.user_id = c.user_id ','LEFT')
                          ->join( '__USER__ as u2 on u2.user_id = c.be_user_id ')
                          ->field($field)
                          ->where($where)
                          ->limit($page['limit'])
                          ->order($sort)
                          ->select();
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
                ->join('__USER__ as u1 on u1.user_id = c.user_id','LEFT')
                ->join('__USER__ as u2 on u2.user_id = c.be_user_id')
                ->join('__TASK__ as t on c.task_id = t.id','LEFT')
                ->field('c.*,u1.nick_name as username ,u2.nick_name as beusername ,t.title')
                ->where('c.id  = '.$where['id'])
                ->find();
        return $list;
    }
    /**
     * 修改申诉信息的状态
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
