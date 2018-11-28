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
    protected $selectFields = array('id','title','category_id', 'mobile_type', 'end_time','price','task_zong','task_num','total_price','link_url','validate_words','remark','is_show','audit_status','audit_info','add_time','status','user_id');
    protected $findFields = array('id','title','category_id', 'mobile_type', 'end_time','price','task_zong','task_num','total_price','link_url','validate_words','remark','audit_info');
    protected $_validate = array(
        array('title', 'require', '标题不能为空！', 1, 'regex', 3),
        array('title', 'checkTitleLength', '标题不能超过30个字！', 2, 'callback', 3),
        array('category_id', 'number', '任务分类不能为空！', 1, 'regex', 3),
        array('mobile_type', 'require', '支持设备不能为空！', 1, 'regex', 3),
        array('end_time', 'require', '任务截止时间不能为空！', 1, 'regex', 3),
        array('end_time', 'checkEndTime', '任务截止时间已过期！', 1, 'callback', 3),
        array('price', 'require', '任务价格不能为空！', 1, 'regex', 3),
        array('task_num', 'number', '任务数量必须是一个数字！', 1, 'regex', 3),
        array('validate_words', 'checkTitleLength', '文字验证说明30个字以内', 2, 'callback', 3),
        array('remark', 'checkRemarkLength', '备注200个字以内', 2, 'callback', 3),
    );

    protected function checkTitleLength($data) {
        $length = mb_strlen($data, 'utf-8');
        if ($length > 30) {
            return false;
        }
        return true;
    }
    protected function checkRemarkLength($data) {
        $length = mb_strlen($data, 'utf-8');
        if ($length > 200) {
            return false;
        }
        return true;
    }

    protected function checkEndTime($data) {

        if ($data < NOW_TIME) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * 接单赚钱_任务列表
     * @param $where
     * @return array
     */
    public function getTaskList($where = [], $field = '', $order = 't.top_time DESC, t.re_time DESC, t.add_time DESC') {
        /*任务状态查询条件*/
        if ($field =='') {
            $field = 't.id,t.user_id,t.discard_id,t.title,t.price,t.category_id,t.mobile_type,t.top_time,t.re_time,t.task_num,c.category_name,c.category_img,s.shop_accounts';
        }
        $map['t.status'] = array('eq', 1); //未删除
//        $map['t.end_time'] = array('gt', NOW_TIME); //未结束
        $map['t.is_show'] = array('eq', 1);//已发布
//        $map['t.audit_status'] = array('eq', 1);//审核通过
        $count = $this->alias('t')
              ->join('__TASK_CATEGORY__ as c on t.category_id = c.id', 'LEFT')
              ->join('__SHOP__ as s on s.user_id = t.user_id')
              ->where($where)
              ->where($map)
              ->count();

        $page = get_page($count,10);
        $list = $this->alias('t')
              ->join('__TASK_CATEGORY__ as c on t.category_id = c.id', 'LEFT')
              ->join('__SHOP__ as s on s.user_id = t.user_id')
              ->field($field)
              ->where($map)
              ->where($where)
              ->limit($page['limit'])
              ->order($order)
              ->select();

        /*判断是否丢失任务*/
        $new = array();

        foreach ($list  as  $key=> $value) {
            if (strpos($value['discard_id'], ',' . UID . ',') !== false) {

            } else {
                $new[] = $value;
            }
        }
        return array(
            'list' => $new,
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
        $info = $this->field($field)->where(array('id'=>$id))->find();
        $taskStep = D('Home/TaskStep')->where(array('task_id'=>$id))->select();
        $j = 0;
        $i = 0;
        foreach ($taskStep as $k=>$v) {
            if ($v['type'] == 2) {

                $info['check_info'][$j] = $v['step_img'];
                $j++;
            } else if($v['type'] == 1) {

                $info['step_info'][$i]['step_img'] = $v['step_img'];
                $info['step_info'][$i]['step_text'] = $v['step_text'];
                $i++;
            }
        }
        return $info;
    }
    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){

        $data['add_time'] = NOW_TIME;
        $data['user_id'] = UID;

    }

    /**
    * @desc 接单_任务详情
    * @param $where
    * @param $field
    * @return mixed
    */
    public function getTaskDetail ($where = [], $field = null) {

        if (!$field) {
            $field = 't.*, u.nick_name,u.head_pic,s.shop_accounts,s.top_time,s.shop_type,s.partner_time,c.category_name';
        }

        $taskDetail =  $this->alias('t')
                       ->join('__USER__ as u on t.user_id = u.user_id', 'LEFT')
                       ->join('__SHOP__ as s  on s.user_id = t.user_id')
                       ->join('__TASK_CATEGORY__ as c on c.id = t.category_id')
                       ->field($field)
                       ->where($where)
                       ->find();
        $this->where(array('id'=>$where['t.id']))->setInc('look_num');
        if (!empty($taskDetail)) {
            /*查看任务详情信息*/
            $taskStep = M('TaskStep')->where(array('task_id'=>$where['t.id']))->select();
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
            /*查看粉丝关注状态   0 不是粉丝  1 是粉丝*/
            fansSverify(UID, $taskDetail['user_id'], 1) == true? $taskDetail['is_fans'] = 1: $taskDetail['is_fans'] = 0;
            /*判断任务是否到期  is_stale 0到期 1正常 */
            $taskDetail['end_time'] < NOW_TIME ? $taskDetail['is_stale'] = 0 : $taskDetail['is_stale'] = 1;
            /*判断是否已经接单 is_task   0 未接单  1 已经正常接单  2接单失效过期重新 抢单*/
            $logwhere['status'] = array('eq', 1);
            $logwhere['task_id'] = array('eq', $taskDetail['id']);
            $logwhere['user_id'] = array('eq', UID);
            $logwhere['valid_time'] = array('gt', NOW_TIME);
            $logwhere['valid_status'] = array('in', [0,1,2,3]);
            $logInfo  = M('TaskLog')->where($logwhere)->field('id, valid_time,valid_status')->find();
            if (!empty($logInfo)) {
                $taskDetail['task_log_id'] = $logInfo['id']; //用于判断是否接单
                $taskDetail['log_valid_status'] = $logInfo['valid_status']; //0显示上传 2显示重做 3 已完成
            } else {
                $taskDetail['task_log_id'] = 0;
                $taskDetail['log_valid_status'] = 0;
            }
        }

        return $taskDetail;
    }
    /**
    * @desc  我的任务列表
    * @param  UID
    * @return mixed
    */
    public function getMyTask($where = [],$field = null, $sort = ' t.add_time DESC'){
          $where['t.status'] = 1;
          $count = $this->alias('t')
                 ->join('__TASK_CATEGORY__ as c on  c.id = t.category_id', 'LEFT')
                 ->where($where)
                 ->count();

//          $page = get_web_page($count, 10);
          $taskInfo = $this->alias('t')
                      ->join('__TASK_CATEGORY__ as c on  c.id = t.category_id', 'LEFT')
                      ->where($where)
                      ->field($field)
                      ->order($sort)
//                      ->limit($page['limit'])
                      ->select();

          if(!empty($taskInfo)){
              $taskLogModel = D('Home/TaskLog');
              foreach ($taskInfo as $key=>$value){
                  if($value['end_time'] < NOW_TIME  && $value['audit_status']  == 1 ){
                      $taskInfo[$key]['audit_status']  = 4;
                      $this->where('id = '.$value['id'])->save(array('audit_status' => 4));
                  }
                  if($value['top_time'] < NOW_TIME ){
                      $taskInfo[$key]['top']  = 0;
                      $this->where('id = '.$value['id'])->save(array('top' => 0));
                  }
                  if($value['re_time'] < NOW_TIME ){
                      $taskInfo[$key]['recommend']  = 0;
                      $this->where('id = '.$value['id'])->save(array('recommend' => 0));
                  }
                  if($value['audit_status'] !== 0){
                      $taskInfo[$key]['beginNum'] = $taskLogModel ->where('task_id = '.$value['id'].' and valid_status  != 2 and  valid_status  != 4')->count();
                      $taskInfo[$key]['sucNum'] = $taskLogModel ->where('task_id = '.$value['id'].'  and valid_status  = 3')->count();
                      $taskInfo[$key]['auditNum'] = $taskLogModel ->where('task_id = '.$value['id'].'  and valid_status  = 1')->count();
                  }else{
                      $taskInfo[$key]['beginNum'] = 0;
                      $taskInfo[$key]['sucNum'] = 0;
                      $taskInfo[$key]['auditNum'] = 0;
                  }
                  if($value['audit_status'] == 1 || $value['audit_status'] == 5){
                      $taskRes = $taskLogModel->where('task_id ='.$value['id'])->find();
                      if($taskRes){
                          $taskInfo[$key]['is_task_num']  = 1;
                      }else{
                          $taskInfo[$key]['is_task_num']  = 0;
                      }
                  }
              }
          }
          return $taskInfo;
    }

    //丢弃任务
    public function discardTask($id = 0) {
        if ($id == 0) {
            return V(0, '任务id缺失，操作失败');
        }
        $discard_id = $this->where(array('id'=>$id))->getField('discard_id');

        $ids = $discard_id.UID.',';

        $res = $this->where(array('id'=>$id))->setField('discard_id', $ids);
        if ($res === false) {
            return V(0, '操作失败');
        } else {
            return V(1, '操作成功');
        }
    }
    //删除任务
    public function delTask($id) {
        $logWhere['task_id'] = array('eq', $id);
        $logWhere['status'] = array('eq', 1);
        $logWhere['valid_time'] = array('gt', NOW_TIME);
        $logWhere['valid_status'] = array('in', array(0,1));
        $taskLogRes = M('TaskLog')->where($logWhere)->count();
        if ($taskLogRes > 0) {
            return V(0, '存在未审核任务不能删除');
        }
        $res = $this->where(array('id'=>$id))->setField('status', 0);
        if ($res ===false) {
            return V(0, '删除失败');
        }else {
            return V(1, '删除成功');
        }
    }
}