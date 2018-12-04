<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description     申诉 投诉控制器
 * @Author          wangzhenyu  byzhenyu@qq.com
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
            $where['u1.nick_name|u2.nick_name']  = array('like', '%'.$keyword.'%');
        }
        $field = 'u1.nick_name as username , u2.nick_name as beusername,  c.*';
        $data =  $ComplaintModel->getComplaintList($where, $field);
        $this->assign('list', $data['Complaintlist']);
        $this->assign('page', $data['page']);
        $this->assign('keyword', $keyword);
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
                    $userModel = D('Home/User');
                    $shopModel = D('Home/Shop');
                    if($data['audit_status'] == 1){
                        $ComplaintRes = $ComplaintModel->save();
                        $ComplaintInfo = $ComplaintModel ->getComplaintInfo($data['id']);
                        if($data['type'] == 0){
                            $usershopRes = $shopModel->where('user_id = '.$ComplaintInfo['be_user_id'])->setInc('be_complain_num');
                            $beusershopRes = $shopModel->where('user_id = '.$ComplaintInfo['user_id'])->setInc('complain_num');
                            D('Common/Push')->push('投诉处理结果', $ComplaintInfo['user_id'], '投诉成功', '任务编号'.$ComplaintInfo['task_id'], '已警告该用户!', '');
                            D('Common/Push')->push('投诉处理结果', $ComplaintInfo['be_user_id'], '被投诉', '任务编号'.$ComplaintInfo['be_user_id'], '您的操作违规,多次封号处理!', '');
                            if($usershopRes && $beusershopRes  && $ComplaintRes ){
                                $this->ajaxReturn(V(1, '操作成功'));
                            }else{
                                $this->ajaxReturn(V(0, '失败'));
                            }
                        }else{
                            M()->startTrans();
                            $ComplaintModel->save();
                            /*改变任务的状态*/
                            D('Home/TaskLog')->where(array('task_id' => $ComplaintInfo['task_id'],'user_id' => $ComplaintInfo['user_id'],'valid_status' =>2 ))->save(array('valid_status' => 3));
                            $taskNum = D('Home/Task')->where(array('id'=> $ComplaintInfo['task_id']))->getField('task_num');
                            if($taskNum){
                                D('Home/Task')->where(array('id'=> $ComplaintInfo['task_id']))->setDec('task_num');
                            }
                            /* end */
                            $userData = array(
                                'task_suc_money' => array('exp','task_suc_money + '.$ComplaintInfo['price']),
                                'total_money' => array('exp','total_money + '.$ComplaintInfo['price']),
                            );
                            $userRes = $userModel->where('user_id = '.$ComplaintInfo['user_id'])->save($userData);
                            $usershopRes = $shopModel->where('user_id = '.$ComplaintInfo['user_id'])->setInc('appeal_num');
                            account_log($ComplaintInfo['user_id'], $ComplaintInfo['price'],4,'申诉获得任务金额',$ComplaintInfo['task_id']);
                            D('Common/Push')->push('申诉处理结果', $ComplaintInfo['user_id'], '申诉成功', '任务编号'.$ComplaintInfo['task_id'], '收入金额'.(fen_to_yuan($ComplaintInfo['price'])), '');
                            $beuserData = array(
                                'total_money' => array('exp','total_money - '.$ComplaintInfo['price'])
                            );
                            $beuserRes = $userModel->where('user_id = '.$ComplaintInfo['be_user_id'])->save($beuserData);
                            $beusershopRes = $shopModel->where('user_id = '.$ComplaintInfo['be_user_id'])->setInc('be_appeal_num');
                            account_log($ComplaintInfo['be_user_id'], $ComplaintInfo['price'],3,'被申诉扣除任务的金额',$ComplaintInfo['task_id']);
                            D('Common/Push')->push('申诉处理结果', $ComplaintInfo['user_id'], '被申诉', '任务编号'.$ComplaintInfo['task_id'], '减去金额'.(fen_to_yuan($ComplaintInfo['price'])), '');
                            if($usershopRes && $beusershopRes  && $userRes && $beuserRes){
                                M()->commit();
                                $this->ajaxReturn(V(1, '操作成功'));
                            }else{
//                                M()->rollback();
                                $this->ajaxReturn(V(0, '失败'));
                            }
                        }
                    }else{
                            $this->ajaxReturn(V(1, '操作成功'));
                    }
                }else{
                    $this->ajaxReturn(V(3, $ComplaintModel->getError()));
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
