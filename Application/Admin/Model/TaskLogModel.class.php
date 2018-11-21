<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description     接单管理
 * @Author
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;

class TaskLogModel extends Model {

    protected $selectFields = array('id', 'user_id', 'task_id', 'task_name','valid_time','valid_info','valid_img','valid_status','finish_time','add_time');
    protected $_validate = array(
        array('valid_status', array(0,1,2,3,4), '审核状态非法', 1, 'in', 5),
        array('valid_text', 'require', '请输入 审核理由说明 ', 1, 'regex', 5),
        array('valid_text', '0,50', '您输入的 审核理由过长，超过了50个字符数限制', 1, 'length', 5),

    );

    /**
     * 接单记录列表
     * @param array $where
     * @param string $field
     * @param string $order
     * @return array
     */
    public function getTaskLogList($where = [], $field = '', $order = 'log.add_time desc') {
        if ($field == '') {
            $field = $this->selectFields;
        }
        $count = $this->alias('log')->where($where)->count();
        $page = get_page($count);
        $list = $this->alias('log')
            ->join('__USER__ u on log.user_id = u.user_id')
            ->field($field)
            ->where($where)
            ->limit($page['limit'])
            ->order($order)
            ->select();

        return array(
            'info'=>$list,
            'page'=>$page['page']
        );

    }
    /**
     * 详情
     */
    public function getTaskLogDetail($id=0) {
        $where['log.id'] = array('eq', $id);
        $info = $this->alias('log')
            ->join('__TASK__ t on log.task_id = t.id')
            ->join('__USER__ u on log.user_id = u.user_id')
            ->field('log.*,t.title,t.category_id,t.mobile_type,t.end_time,t.price,t.task_zong,t.task_num,t.total_price,t.look_num,t.link_url,t.validate_words,t.remark,t.is_show,t.audit_status,t.user_id as task_user_id,u.nick_name')
            ->where($where)->find();
            if($info) {
               $info['checkInfo'] = D('TaskStep')->getTaskStepList(array('task_id'=>$info['task_id'],'type'=>2));
                $info['valid_info'] = explode(',',$info['valid_info']);
                $info['valid_img'] = explode(',',$info['valid_img']);
            }
        return $info;
    }
    //排行
    public function getTaskRankByTime($where=[]) {
        $map['lg.valid_status'] = array('eq', 3);
        $map['lg.status'] = array('eq', 1);
        $field='sum(task_price) as total, lg.user_id,u.nick_name,u.head_pic';
        $count = $this->alias('lg')->where($map)->where($where)->count();

        $page = get_page($count);
        $data  = $this->alias('lg')
            ->join('__USER__ u on lg.user_id = u.user_id')
            ->where($where)
            ->where($map)
            ->field($field)
            ->group('lg.user_id')
            ->order('total desc')
            ->select();

        return array(
            'info'=>$data,
            'page'=>$page['page'],
            'count'=>$count
        );
    }

}
