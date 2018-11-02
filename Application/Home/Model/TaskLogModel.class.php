<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     接单任务 Model
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/1 0001 13:19
 * @CreateBy       PhpStorm
 */
namespace Home\Model;
use Think\Model;
class TaskLogModel extends  Model{
    protected $insertFields = array('user_id','task_id', 'task_name', 'valid_time','valid_info','valid_img','valid_text','valid_status','status','finish_time','add_time');
    protected $updateFields = array('task_id', 'task_name', 'valid_time','valid_info','valid_img','valid_text','valid_status','status','finish_time','add_time');
    protected $selectFields = array('task_id', 'task_name', 'valid_status');
    protected $_validate = array(
        array('valid_time', 'require', '任务有效日期不能为空', 1, 'regex', 3),
        array('valid_info','require','验证信息不能为空',1,'regex', 3)
    );
    public function getTaskLog($where = [], $field = '', $sort = 'add_time DESC'){
        $where['l.status'] = 1;
        $count = $this->alias('l')
                 ->join('__TASK__ as t on t.id = l.task_id', 'LEFT')
                 ->join('__TASK_CATEGORY__ as c on c.id = t.category_id')
                 ->field($field)
                 ->where($where)
                 ->count();
        $page = get_page($count,10);
        $list = $this->alias('l')
                ->join('__TASK__ as t on t.id = l.task_id', 'LEFT')
                ->join('__TASK_CATEGORY__ as c on c.id = t.category_id')
                ->field($field)
                ->where($where)
                ->limit($page['limit'])
                ->order($sort)
                ->select();
        /*判断任务是否失效  is_past 1正常  0已经过期*/
        foreach($list as $key=>$value){
            if($value['valid_status'] == 0){
                if($value['valid_time'] > NOW_TIME){
                    $list[$key]['is_past'] = 1;
                }else{
                    $list[$key]['is_past'] = 0;
                }
            }
            unset($value['valid_time']);
        }
        return array(
            'list' => $list,
            'page' => $page['page']
        );
    }
    /**
    * @desc 我的任务详情
    * @param  $where
    * @param  $field
    * @return mixed
    */
    public function  getTaskLogDetail($where = [], $field = null, $sort = ''){
        if(is_null($field)) $field = '';
         $taskInfo  = $this->alias('l')
                      ->join('__TASK__ as t on t.id = l.task_id', 'LEFT')
                      ->join('__TASK_CATEGORY__ as c on c.id = t.category_id')
                      ->field($field)
                      ->where($where)
                      ->find();
         return $taskInfo;
    }
    protected function _before_insert(&$data, $option){
        $data['valid_time'] = NOW_TIME  + 1200;
        $data['valid_status'] = 0;
        $data['status'] = 1;
        $data['add_time'] = NOW_TIME;
    }
    protected function _before_update(&$data, $option){
        $data['finish_time'] = NOW_TIME;
    }
    /**
    * @desc  丢弃任务
    * @param $task_id
    * @return mixed
    */
    public  function delTaskLog($id)
    {
          $taskLogDel = $this->where(array('user_id' => UID,'task_id' => $id))->save(array('status' => 0));
          $taskInfo   = D('Home/Task')->where('id = '.$id)->find();
          if($taskLogDel) {
              /*task 更新的数据*/
              $where['task_num'] = array('exp', ' task_num + 1');
              $where['discard_id'] = $taskInfo['discard_id'].UID.',';
          }else{
              $where['discard_id'] = $taskInfo['discard_id'].UID.',';
          }
          $result  = D('Home/Task')->where('id = '.$id)->save($where);
          if($result) {
              return true;
          }else{
              return false;
          }
    }
    /**
    * @desc 判断是否有任务
    * @param $user_id
    * @param $task_id
    * @return mixed
    */
    public function  activateTask($user_id, $task_id)
    {
          $where['user_id'] = $user_id;
          $where['task_id'] = $task_id;
          $where['audit_status'] = 0;
          $result  = $this->where($where)->find();
          if($result)
          {
               $this->where($where)->save(array('end_time' => NOW_TIME + 1200));
               return true;
          }else{
               return false;
          }
    }
    /**
     * @desc 改变接单状态
     * @param $task_id
     * @return mixed
     */
    public function changeTask($task_id, $valid_status = 1){
        //开启事务
//         M() ->startTrans();
//         if($valid_status == 3){
//             $taskLogInfo = $this->field('task_id, user_id, task_price')->where('id = '.$task_id)->find();
//             $taskInfo = $this->field('task_id, user_id, task_price')->where('id = '.$task_id)->find();
//             $taskInfo = $this->alias('')
//                         ->
//             $userModel = D('Home/User');
//             /*更新用户数据*/
//             $userData = array(
//                 'task_money'  => array('exp','task_money +'.$taskInfo['price']),
//                 'task_money'  => array('exp','task_suc_money +'.$taskInfo['price']),
//                 'total_money' => array('exp','total_money +'.$taskInfo['price'])
//             );
//             $changeUserMoney = $userModel->where('id = '.$taskInfo['user_id'])->save($userData);
//         }
//         $result =  $this->where('id = '.$task_id)->save(array('valid_status' => $valid_status));

    }
}