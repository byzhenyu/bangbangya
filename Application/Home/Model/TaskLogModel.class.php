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
    protected $insertFields = array('user_id','task_id', 'task_price','task_name', 'valid_time','valid_info','valid_img','valid_text','valid_status','status','finish_time','add_time');
    protected $updateFields = array('task_id', 'task_name', 'valid_time','valid_info','valid_img','valid_text','valid_status','status','finish_time','add_time');
    protected $selectFields = array('id','task_id', 'user_id', 'task_name', 'task_price', 'valid_time', 'valid_info', 'valid_img', 'valid_pic', 'valid_text', 'valid_status', 'finish_time','add_time');
    protected $_validate = array(
        array('user_id', 'require', '用户id缺失', 1, 'regex', 1), //添加
        array('task_id','require','任务id缺失',1,'regex', 1),
        array('valid_time', 'require', '任务有效日期不能为空', 1, 'regex', 2),//修改
        array('valid_info','require','验证信息不能为空',1,'regex', 2),
        array('valid_img','require','请上传验证图',1,'regex', 5), //做任务用
        array('valid_status', array(0,1,2,3,4), '审核字段非法', 1, 'in', 5),
        array('valid_info', 'require', '验证信息不能为空', 0, 'regex', 5),

    );
    public function getTaskLog($where = [], $field = '', $sort = 'l.add_time DESC'){
        $map['l.status'] = array('eq', 1);

        $count = $this->alias('l')
                 ->join('__TASK__ as t on t.id = l.task_id', 'LEFT')
                 ->join('__TASK_CATEGORY__ as c on c.id = t.category_id')
                 ->field($field)
                 ->where($where)
                 ->where($map)
                 ->count();
        $page = get_page($count,10);
        $list = $this->alias('l')
                ->join('__TASK__ as t on t.id = l.task_id', 'LEFT')
                ->join('__TASK_CATEGORY__ as c on c.id = t.category_id')
                ->field($field)
                ->where($where)
                ->where($map)
                ->limit($page['limit'])
                ->order($sort)
                ->select();
        /*判断任务是否失效 过期改变valid_status 2 不合格*/
        foreach($list as $key=>$value){
            if($value['valid_status'] == 0){
                if($value['valid_time'] < NOW_TIME){
                   $this->where('id = '.$value['id'])->save(array('valid_status' => 2));
                   $list[$key]['valid_status'] =  2;
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
    * @desc 任务记录详情
    * @param  $where
    * @param  $field
    * @return mixed
    */
    public function  getTaskLogDetail($where = [], $field = null) {
        if(is_null($field)) $field = 'l.*, t.category_id, t.remark, c.category_name,t.user_id as task_user_id, u.head_pic,u.nick_name,s.shop_accounts,s.top_time,s.shop_type,s.partner_time';
        $taskDetail  = $this->alias('l')
                      ->join('__TASK__ as t on t.id = l.task_id', 'LEFT')
                      ->join('__TASK_CATEGORY__ as c on c.id = t.category_id')
                      ->join('__SHOP__ as s  on s.user_id = t.user_id')
                      ->join('__USER__ u on t.user_id = u.user_id')
                      ->field($field)
                      ->where($where)
                      ->find();

         if (!empty($taskDetail)) {
             /*查看任务详情信息*/
             $taskStep = M('TaskStep')->where(array('task_id'=>$taskDetail['task_id']))->select();
             if (!empty($taskStep)) {
                 $j = 0;
                 $i = 0;
                 foreach ($taskStep as $k=>$v) {
                     if ($v['type'] == 2) {
                         $taskDetail['check_info'][$j] = $v['step_img'];
                         $j++;
                     } else if($v['type'] == 1) {
                         $taskDetail['step_info'][$i]['step_img'] = $v['step_img'];
                         $taskDetail['step_info'][$i]['step_text'] = $v['step_text'];
                         $i++;
                     }
                 }
             }

             /*查看粉丝关注状态   0 不是粉丝  1 是粉丝*/
             fansSverify(UID, $taskDetail['task_user_id'], 1) == true? $taskDetail['is_fans'] = 1: $taskDetail['is_fans'] = 0;

         }

         return $taskDetail;
    }



    protected function _before_insert(&$data, $option){
        $data['valid_time'] = NOW_TIME  + C('TASK_LIMIT_TIME');
        $data['valid_status'] = 0;
        $data['add_time'] = NOW_TIME;
    }
    protected function _before_update(&$data, $option){
        $data['finish_time'] = NOW_TIME;
    }
    //接单减库存
    protected function _after_insert(&$data, $option) {
        $res = M('Task')->where(array('id'=>$data['task_id']))->setDec('task_num');
        if ($res) {
            return true;
        } else {
            $this->error = '任务减库存失败';
            return false;
        }

    }
    /**
    * @desc  丢弃任务
    * @param $taskLog 主键
    * @return mixed
    */
    public  function delTaskLog($id)
    {
          $taskInfo = $this->alias('l')
                       ->join('__TASK__ as t on t.id = l.task_id','LEFT')
                       ->field('l.valid_status, l.task_id,l.user_id,t.discard_id')
                       ->where('l.id = '.$id)
                       ->find();
           M()->startTrans();
          $taskLogDel = $this->where('id = '.$id)->save(array('status' => 0));
          if($taskLogDel) {
              /*task 更新的数据  如果是没有做的任务,更新任务数量*/
              if($taskLogDel['valid_status']  == 0){
                  $where['task_num'] = array('exp', ' task_num + 1');
              }
              if($taskInfo !== ',' || strpos($taskInfo, ','.$taskInfo['user_id'].',')  === false){
                  $where['discard_id'] = $taskInfo['discard_id'].UID.',';
                  $result  = D('Home/Task')->where('id = '.$taskInfo['task_id'])->save($where);
              }else{
                  $result = true;
              }
          }
          if($result && $taskLogDel) {
              M()->commit();
              return true;
          }else{
              M()->rollback();
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
          $where['valid_status'] = 0;
          $where['valid_time'] = array('gt', NOW_TIME);
          $result  = $this->where($where)->find();
          if($result)
          {
               $this->where($where)->save(array('end_time' => NOW_TIME + 1200));
               return true;
          } else {
               return false;
          }
    }

    /**
     * @desc 改变接单状态
     * @param $task_id
     * @return mixed
     */
    public function changeTaskStatus($tasklog_id, $valid_status = 1){

         $where['id'] = $tasklog_id;
         if($valid_status == 3){
             // 开启事务
             M() ->startTrans();
             /*查询任务信息*/
             $field =  'l.user_id, l.task_id, l.task_price, t.user_id as t_user_id, t.price, t.total_price, t.title';
             $taskInfo = $this->alias('l')
                 ->join('__TASK__ as t on t.id = l.task_id', 'LEFT')
                 ->field($field)
                 ->where('l.id ='.$tasklog_id)
                 ->find();
             $userModel = D('Home/User');
             /*更新接单用户数据*/
             $userData = array(
                 'task_money'  => array('exp','task_suc_money +'.$taskInfo['task_price']),
                 'total_money' => array('exp','total_money +'.$taskInfo['task_price'])
             );
             account_log($taskInfo['user_id'], $taskInfo['task_price'],'4', '您完成了'.$taskInfo['title'].'的任务',$taskInfo['task_id']);
             $userModel->where('user_id = '.$taskInfo['user_id'])->save($userData);
             /*更新发单用户的数据*/
             account_log($taskInfo['t_user_id'], $taskInfo['task_price'],'5', $taskInfo['title'].'的任务被完成',$taskInfo['task_id']);
             $userModel->where('user_id = '.$taskInfo['t_user_id'])->setDec('frozen_money', $taskInfo['task_price']);
             /*更新任务数据*/
             $taskRes = D('Home/Task')->where('id = '.$taskInfo['task_id'])->setDec('total_price', $taskInfo['task_price']);
            /*更新店铺数据*/
             $shopModel = D('Home/Shop');
             $shopModel->where('user_id = '.$taskInfo['user_id'])->setInc('take_task');
             $shopModel->where('user_id = '.$taskInfo['t_user_id'])->setInc('vol');
         }
         $taskLogRes =  $this->where($where)->save(array('valid_status' => $valid_status));
         if($taskLogRes){
             M()->commit();
             return true;
         }else{
             M()->rollback();
             return false;
         }

    }
    /**
     * @desc  任务审核详情
     * @param  task_id
     * @return mixed
     */
    public  function auditTask($where = [], $field = null, $sort = ' t.finish_time DESC'){
        $where['t.valid_status']  = array('neq',0);
        $count =  $this->alias('t')
            ->join('__USER__ as u on u.user_id = t.user_id','LEFT')
            ->where($where)
            ->count();
        $page = get_page($count, 1);
        $list = $this->alias('t')
            ->join('__USER__ as u on u.user_id = t.user_id','LEFT')
            ->field($field)
            ->where($where)
            ->limit($page['limit'])
            ->order($sort)
            ->select();
        $chatModel = D('Home/Chat');
        foreach ($list as $key => $value){
              if(strpos($value['valid_img'],',')  !== false ){
                    $list[$key]['valid_img']   =   explode(',',$value['valid_img']);
              }else{
                    $list[$key]['valid_img']  = array($value['valid_img']);
              }
              if($value['valid_status']  == 2){
                  /* taskChat 发任务的   userChat 接单人user_id*/
                  $list[$key]['message']['taskChat'] = $chatModel ->field('content')->where('task_user_id = '.UID.' and  task_log_id =  '.$value['tid'])->select();
                  $list[$key]['message']['userChat'] = $chatModel ->field('content')->where('user_id = '.UID.' and task_log_id =  '.$value['tid'])->select();
              }
        }
        return array(
            'list' => $list,
            'page' => $page['page'],
            'count'=> $count
        );
    }
    /**
    * @desc  任务的审核的数量
    * @param  $task_id
    * @return mixed
    */
    public function taskAudit($task_id){
        $where['task_id'] = $task_id;
        $where['valid_status'] = array('in' ,'2,3');
        $taskAudit['is_audit'] = $this->where($where)->count();
        $where['valid_status'] = 1 ;
        $taskAudit['no_audit'] = $this->where($where)->count();
        return $taskAudit;
    }
    //重做任务

    public function reDoTaskLog($log_id) {
        $where['id'] = $log_id;
        M()->startTrans();
        $res = $this->where($where)->setField('valid_status', 4);
        if ($res === false) {
            M()->rollback();
            return false;
        }
        $info = $this->where($where)->field('user_id,task_id,task_name,task_price')->find();
        $info['add_time'] = NOW_TIME;
        $info['valid_time'] = NOW_TIME + C('TASK_LIMIT_TIME');

        $new_id = $this->data($info)->add();
        if ($new_id === false) {
            M()->rollback();
            return false;
        } else {
            M()->commit();
            return $new_id;
        }

    }
}