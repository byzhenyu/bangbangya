<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;
use Think\Controller;
/**
 * 申诉表控制器
 */
class ComplaintController extends CommonController {
    public function listComplaint(){
        $keyword = I('keyword', '');
        $ComplaintModel = D('Admin/Complaint');
        if ($keyword) {
            $where['task_id|user_id']  = array('like', '%'.$keyword.'%');
        }
        $data =  $ComplaintModel->getComplaintList($where);
        $this->assign('list', $data['Complaintlist']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    /*删除*/
    public function recycle() {
        $this->_recycle('Complaint','id');
    }
    /**
     * 详情
     */
    public function ComplaintDetail() {
        $id = I('id', 0, 'intval');
        $ComplaintModel = D('Admin/Complaint');
        if(IS_POST){
            if($id > 0){
                if($ComplaintModel ->create()){
                    $data = I('post.','');
                    $result = $ComplaintModel->save();
                    if($data['audit_status'] == 1)
                    {
                          $ComplaintInfo = $ComplaintModel ->getComplaintInfo($data['id']);
                          $taskInfo = D('Admin/Task') ->getTaskDetail($ComplaintInfo['task_id']);
                          if($taskInfo['total_price'] == 0) $this->ajaxReturn(V(2, '任务总金额不足', $taskInfo['id']));
                          // die;
                          //开启事务 
                          M() ->startTrans();
                          /*更新用户数据*/
                          $userData = array(
                              'task_money'  => array('exp','task_money +'.$taskInfo['price']),
                               'total_money' => array('exp','total_money +'.$taskInfo['price'])
                          );
                          $userRes = D('Admin/User')->updateUserInfo($ComplaintInfo['user_id'],$userData);
                          /*更新任务表数据*/
                          $taskData = array(
                              'task_num'  => array('exp','task_num - 1'),
                               'total_price' => array('exp','total_price -'.$taskInfo['price'])
                          );
                          $taskRes = D('Admin/Task')->updateTaskinfo($taskInfo['id'],$taskData);
                          if($userRes  &&  $taskRes  && $result)
                          {

                              M()->commit();
                              $this->ajaxReturn(V(1, '操作成功', $id));
                          }else{
                              M()->rollback();
                              $this->ajaxReturn(V(0, '操作失败'));                    
                          }
                    }else if($result === false){
                           $this->ajaxReturn(V(0, '操作失败'));
                    }
                    $this->ajaxReturn(V(1, '操作成功', $id));
                }else{
                    $this->ajaxReturn(V(2, $ComplaintModel->getError()));
                }
            }
        }
        $where['id'] = $id;
        $ComplaintInfo = $ComplaintModel ->getComplaintInfo($where);
        $this->Info = $ComplaintInfo;
        $this->display();
    }
    public function del(){
        $this->_del('Complaint', 'id');
    }
    /*申诉信息变更完成*/
    public function changeAuditStatus() {
        $id = I('id', 0, 'intval');
        $updateInfo = D('Admin/Complaint')->changeAuditStatus($id);
        $this->ajaxReturn($updateInfo);
    }
 }
