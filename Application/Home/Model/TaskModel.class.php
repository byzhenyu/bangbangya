<?php
/**
 * /**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     任务信息类
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/31 0031 15:22
 * @CreateBy       PhpStorm
 */
namespace Home\Model;
use Think\Model;
class TaskModel extends Model{
    protected $selectFields = array('id','title','category_id', 'mobile_type', 'end_time','price','task_num','total_price','link_url','validate_words','remark','is_show','audit_status','audit_info','add_time','status','user_id');
    protected $findFields = array('id','title','category_id', 'mobile_type', 'end_time','price','task_num','total_price','link_url','validate_words','remark','audit_info');
    protected $_validate = array(
        array('title', 'require', '标题不能为空！', 1, 'regex', 3),
        array('title', 'checkTitleLength', '标题不能超过30个字！', 2, 'callback', 3),
        array('title', 'checkTitle', '标题重复！', 1, 'callback', 3),
        array('category_id', 'require', '任务分类不能为空！', 1, 'regex', 3),
        array('mobile_type', 'require', '支持设备不能为空！', 1, 'regex', 3),
        array('end_time', 'require', '任务截止时间不能为空！', 1, 'regex', 3),
        array('price', 'number', '任务价格必须是一个数字！', 1, 'regex', 3),
        array('task_num', 'number', '任务数量必须是一个数字！', 1, 'regex', 3),
        array('total_price', 'number', '任务总金额不能为空！', 1, 'regex', 3)
    );
    /**
     * 接单赚钱_任务列表
     * @param $where
     * @return array
     */
    public function getTaskList($where = [], $field = '', $order = 't.add_time desc') {
        /*任务状态查询条件*/
        $where[] = array('t.end_time' =>array('gt',NOW_TIME),'t.task_num'=>array('GT',0),'t.status'=> 1,'t.audit_status' =>1,'t.is_show' =>1);
        $count = $this->alias('t')
              ->join('__TASK_CATEGORY__ as c on t.category_id = c.id', 'LEFT')
              ->join('__SHOP__ as s on s.user_id = t.user_id')
              ->where($where)
              ->count();
        $page = get_page($count);
        $list = $this->alias('t')
              ->join('__TASK_CATEGORY__ as c on t.category_id = c.id', 'LEFT')
              ->join('__SHOP__ as s on s.user_id = t.user_id')
              ->field('t.*,c.id as category_id,c.category_name')
              ->where($where)
              ->limit($page['limit'])
              ->order($order)
              ->select();
        /*判断是否丢失任务*/
        foreach ($list  as  $key=> $value) {
            if (strpos($value['discard_id'], ',' . UID . ',') !== false) {
                unset($list[$key]);
            }
        }
        return array(
            'list' => $list,
            'page' => $page['page']
        );
    }
    /**
    * @desc 获取我发布任务详情
    * @param $id
    * @param $field
    * @return array
    */
    public  function  getMyTaskDetail($id = 0,$field = null){
        if($id == 0) return false;
        if(is_null($field)){
            $field = $this->findFields;
        }
        $info = $this->field($field)->where(array('id = '.$id))->find();
        $info['taskStep'] = D('Home/TaskStep')->where('task_id = '.$id)->select();
        return $info;
    }
    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){
        $data['audit_status'] = 0;
        $data['add_time'] = NOW_TIME;
    }
    /**
    * @desc  发布任务
    * @param $data
    * @return mixed
    */
    public  function addTask($data, $where = [])
    {
        $task = $data['task'];
        $task['user_id'] = UID;
        $taskStep = $data['taskStep'];
        //开启事务
        M() ->startTrans();
        $result = $this->add($task);
        if($result)
        {
            if($task['is_show']  == 1){
                /*改变用户金额*/
                $userMoney = array(
                    'total_money'  => array('exp','total_money - '.$task['total_price']),
                    'frozen_money' => array('exp','frozen_money + '.$task['total_price'])
                );
                $changeUserMoney = D('Home/User')->where($where)->save($userMoney);
                if($changeUserMoney)
                {
                    /*记录用户消费几率*/
                   account_log($where['user_id'], $task['total_price'], 3, $desc = '发布标题为<'.$task['title'].'>的任务', $result);
                }
            }
            $taskStepModel = D('Home/TaskStep');
            $addDateNum = 0;
            foreach($taskStep as $key=>$value){
                $addDateNum ++ ;
                if($key == 0) {
                     $value['type'] = 1;
                }else{
                     $value['type'] = 2;
                }
                $value['task_id']  = $result;
                $taskStepModel->add($value);
            }
        }
        if($result && count($taskStep))
        {
            M()->commit();
            return true;
        }else{
            M()->rollback();
            return false;
        }
    }
    /**
    * @desc 接单_任务详情
    * @param $where
    * @param $field
    * @return mixed
    */
    public function getTaskDetail($where = [], $field = null){
        $taskDetail =  $this->alias('t')
                       ->join('__USER__ as u on t.user_id = u.user_id', 'LEFT')
                       ->join('__SHOP__ as s  on s.user_id = t.user_id')
                       ->join('__TASK_CATEGORY__ as c on c.id = t.category_id')
                       ->field($field)
                       ->where($where)
                       ->find();

        /*查看任务详情信息*/
        $taskDetail['taskStep'] = D('Home/taskStep')->where('task_id = '.$where['t.id'])->select();
        /*查看粉丝关注状态   0 不是粉丝  1 是粉丝*/
        fansSverify(UID, $taskDetail['user_id'], 1) == true? $taskDetail['is_fans'] = 1: $taskDetail['is_fans'] = 0;
        /*判断任务是否到期  is_stale 0到期 1正常 */
        $taskDetail['end_time'] < NOW_TIME? $taskDetail['is_stale'] = 0 : $taskDetail['is_stale'] = 1;
        /*判断是否已经接单 is_task   0 未接单  1 已经正常接单  2接单失效过期重新 抢单*/
        $valid_time  = D('Home/TaskLog')->where(array('user_id' => UID, 'task_id'=> $where['t.id']))->getField('valid_time');
        if($valid_time){
            $taskDetail['is_task'] = 1;
            /*判断订单是否到期 is_past  0过期 1正常*/
            $valid_time > NOW_TIME?$taskDetail['is_past'] = 1:$taskDetail['is_past'] = 0;
        }else{
            $taskDetail['is_task'] = 0;
        }
        return $taskDetail;
    }
}