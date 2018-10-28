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
 * 任务表控制器
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
                    $result = $ComplaintModel->save();
                    if($result === false){
                        $this->ajaxReturn(V(0, '操作失败'));
                    }
                    $this->ajaxReturn(V(1, '操作成功', $id));
                }
                else{
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
    /*投诉信息变更完成*/
    public function changeAuditStatus() {
        $id = I('id', 0, 'intval');
        $updateInfo = D('Admin/Complaint')->changeAuditStatus($id);
        $this->ajaxReturn($updateInfo);
    }
 }
