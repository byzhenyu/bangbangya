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
            $where['task_id']  = array('like', '%'.$keyword.'%');
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
        $where['id'] = $id;
        $ComplaintModel = D('Admin/Complaint');
        $ComplaintInfo = $ComplaintModel->getComplaintInfo($where);
        $this->Info = $ComplaintInfo;
        $this->display();
    }
    public function del(){
        $this->_del('Complaint', 'id');
    }
    /*完成*/
    public function ComplaintComplete($id)
    {
    	$ComplaintTable = M('Complaint');
    	$result = $ComplaintTable -> where('id = '.$id) ->save(array('audit_status' => 1));
    	if($result)
    	{
            $this->success("成功！", U("Complaint/listComplaint"));
    	}else{
            $this->error("删除失败！", $ComplaintTable->getError());
    	}
    }
    /*投诉信息变更完成*/
    public function changeAuditStatus() {
        $id = I('id', 0, 'intval');
        $updateInfo = D('Admin/Complaint')->changeAuditStatus($id);
        $this->ajaxReturn($updateInfo);
    }
 }
