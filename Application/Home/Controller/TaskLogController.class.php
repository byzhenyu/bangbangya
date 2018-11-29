<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description
 * @Author   
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           ${DATE} ${TIME}
 * @CreateBy       ${PRODUCT_NAME}
 */

namespace Home\Controller;
use Common\Controller\UserCommonController;
class TaskLogController extends UserCommonController{
    public function _initialize() {
        $this->TaskLogModel = D("Home/TaskLog");
    }
    /**
     * @desc 我的任务
     */
    public function getTaskLog(){
        $type = I('type', 5, 'intval');
        $this->type = $type;
        $this->display();
    }

    /**
     * @desc 获取接单信息
     * @param  $user_id
     * @return mixed
     */
    public function ajax_getTaskLog(){
        /*where 接任务条件查找  0待提交 1审核中 2不合格 3已完成 default全部 */
        $type = I('type', 5, 'intval');
        $this->type = $type;
        switch ($type) {
            case 0:
            case 1:
            case 2:
            case 3:
                $where['l.valid_status'] = array('eq', $type);
                break;
            default:
                $where['l.valid_status'] = array('neq', 4);
                break;
        }
        $where['l.user_id'] = UID;
        $field = 'l.id, l.task_id, l.task_name,l.valid_time, l.valid_status, t.price, c.category_name, c.category_img';
        $taskLogModel = D('Home/TaskLog');
        $taskLogInfo = $taskLogModel->getTaskLog($where,$field);
        $this->list = $taskLogInfo['list'];
        $this->display();
    }

    /**
     * @desc 丢弃任务
     * @param $id  taskLog 主键
     * @return mixed
     */
    public  function delTaskLog(){
        $id = I('id', 0, 'intval');
        $taskLogModel = D('Home/TaskLog');
        $result  = $taskLogModel->delTaskLog($id);
        if($result)
        {
            $this->ajaxReturn(V(1,'删除成功'));
        }
        else{
            $this->ajaxReturn(V(0, '删除失败'));
        }
    }

    /**
     * @desc 我的任务-上传验证
     * @param $task_id
     * @return mixed
     */
    public function taskVerify() {
        $id = I('id', 0, 'intval');
        $taskLogModel = D('Home/TaskLog');
        if(IS_POST) {
            $data = I('post.', '');
            $data['valid_img'] = implode(',', $data['valid_img']);
            $data['valid_status'] = 1;

            if ($taskLogModel->create($data, 5) !== false) {
                $rs = $taskLogModel->save($data);
                if ($rs === false) {
                    $this->ajaxReturn(V(0, '操作失败'));
                }
                $this->ajaxReturn(V(1, '上传成功'));
            } else {
                $this->ajaxReturn(V(0, $taskLogModel->getError()));
            }
        }
        $info = $taskLogModel->getTaskLogDetail(array('l.id'=>$id));
        $this->assign('info', $info);
        $this->assign('id', $id);
        $this->display();
    }

    /**
     * @desc  任务不合格详情
     * @param  tasklog_id
     * @return mixed
     */
    public function taskLogFail(){
        $taskLog_id = I('id', 0, 'intval');
        $taskLogInfo = $this->TaskLogModel->field('id, user_id, task_id, valid_pic')->where(array('id'=>$taskLog_id))->find();
        $chatModel = D('Home/Chat');
        $taskLogInfo['userChat']  = $chatModel ->field('content')->where(array('user_id'=>$taskLogInfo['user_id'],'task_log_id'=>$taskLogInfo['id']))->select();
        $taskLogInfo['taskChat']  = $chatModel ->field('content')->where(array('task_user_id'=>$taskLogInfo['user_id'],'task_log_id'=>$taskLogInfo['id']))->select();
        if(strpos($taskLogInfo['valid_pic'], ',')  !== false){
            $taskLogInfo['valid_pic']   =   explode(',',$taskLogInfo['valid_pic']);
        } else {
            $taskLogInfo['valid_pic']   =    array($taskLogInfo['valid_pic']);
        }
        $this->assign('taskLogInfo',$taskLogInfo);
        $this->display();
    }

    /**
     * @desc  放弃任务
     * @param tasklog_id
     * @return mixed
     */
    public function giveUpTask(){
        $taskLog_id = I('id', 0, 'intval');

        $taskLogRes = $this->TaskLogModel->where(array('id'=>$taskLog_id))->save(array('status' => 0));
        if ($taskLogRes) {
            $this->ajaxReturn(V(1, '成功'));
        }else{
            $this->ajaxReturn(V(0, '失败'));
        }
    }

    /**
     *  任务详情
     */
    public function taskLogDetail() {
        $id = I('id', 0, 'intval');
        $info = D('Home/TaskLog')->getTaskLogDetail(array('l.id'=>$id));
        //p($info);
        $this->assign('taskDetail', $info);
        $this->display();
    }

    /**
     * 重做
     * 返回新logid
     */
    public function reDoLog() {
        $log_id = I('log_id', 0 , 'intval');

        $info = D('Home/TaskLog')->reDoTaskLog($log_id);
        $this->ajaxReturn($info);

    }

    /**
     * @desc  任务审核
     * @param  task_id
     * @return mixed
     */
    public function auditTask(){
        $task_id = I('task_id', 0, 'intval');
        $p = I('p', 1, 'intval');
        if($p <= 1){
            $p  = 1;
        }
        $where['task_id'] = $task_id;
        $field =  'u.user_id, u.head_pic, u.nick_name, t.task_id,t.id as tid, t.valid_info, t.valid_img, t.valid_status  ';
        $taskLogInfo = $this->TaskLogModel->auditTask($where, $field);
//        p($taskLogInfo);
        $taskAudit = $this->TaskLogModel->taskAudit($task_id);
        $this->assign('taskAudit',$taskAudit);
        $this->assign('task_id',$task_id);
        $this->assign('p',$p);
        $this->assign('taskLogInfo',$taskLogInfo);
        $this->display();
    }
    //接单
    public function getTask() {
        $task_id = I('id', 0, 'intval');
        $where['t.id'] = $task_id;
        $taskInfo = D('Home/Task')->getTaskDetail($where);
        if (empty($taskInfo)) {
            $this->ajaxReturn(V(0, '任务不存在，或被删除'));
        }
        if ($taskInfo['task_num'] < 1) {
            $this->ajaxReturn(V(0, '任务数量已经空了!请稍后再试!'));
        }
        $TaskLogModel = D('Home/TaskLog');
        $logStatus = $TaskLogModel->activateTask(UID, $task_id);
        if ($logStatus) {
            $this->ajaxReturn(V(0, '存在未完成任务,请勿重复接单'));
        }

        $data['user_id'] = UID;
        $data['task_id'] = $taskInfo['id'];
        $data['task_name'] = $taskInfo['title'];
        $data['task_price'] = $taskInfo['price'];
        if ($TaskLogModel->create($data) ===false) {
            $this->ajaxReturn(V(0, $TaskLogModel->getError()));
        }
        $taskLogId = $TaskLogModel->add();
        $this->ajaxReturn(V(1, '接单成功', $taskLogId));
        if ($taskLogId) {
            $this->ajaxReturn(V(1, '接单成功', $taskLogId));
        } else {
            $this->ajaxReturn(V(0, '操作失败请重试'));
        }

    }
    /**
     * @desc 任务审核不通过
     * @param  taskLog id
     * @param  valid_text 审核备注
     * @param  valid_pic 审核图片
     * @return mixed
     */
    public function fail(){
        $data  = I('post.', 3);
        $data['valid_status']  = 2;
        $data['valid_pic']  = rtrim($data['valid_pic'], ',');
        $taskModel = D('Home/Task');
        $tasklogInfo = $this->TaskLogModel->where(array('id'=>$data['id']))->find();
        M()->startTrans();
        $tasklogRes = $this->TaskLogModel->where(array('id'=>$data['id']))->save($data);
        $taskRes = $taskModel->where(array('id' =>$tasklogInfo['task_id']))->setInc('task_num');
        $ChatModel = D('Home/Chat');
        $CharData['user_id'] = $tasklogInfo['user_id'];
        $CharData['task_user_id'] = UID;
        $CharData['task_log_id'] = $tasklogInfo['id'];
        $CharData['content'] = $data['valid_text'];
        $chatRes = $ChatModel->add($CharData);
        if($tasklogRes  && $chatRes && $taskRes){
            D('Common/Push')->push('任务处理通知',$tasklogInfo['user_id'],'亲，您的做的任务审核未通过!','任务名称:'.$tasklogInfo['task_name'],'通知类型：失败',$data['valid_text']);
            M()->commit();
            $this->ajaxReturn(V(1, '完成'));
        }else{
            M()->rollback();
            $this->ajaxReturn(V(0, '失败'));
        }
        $this->ajaxReturn(V(0, $this->TaskLogModel->getError()));
    }
    public function pass(){
        $tasklog_id = I('id', 0, 'intval');
        $tasklogInfo = $this->TaskLogModel->where('id = '.$tasklog_id)->find();
        $res = user_money( UID, $tasklogInfo['task_price']);
        if(!$res){
            $this->ajaxReturn(V(2, '您的余额不足,请充值审核!'));
        }else{
            $userModel = D('Home/User');
            $ShopModel = D('Home/Shop');
            M()->startTrans();
            /*change shop */
            $userModel->where('user_id = '.UID)->setDec('total_money',$tasklogInfo['task_price']);
            account_log( UID,$tasklogInfo['task_price'],3,'任务结算',$tasklog_id);
            $ShopModel->where('user_id = '.UID)->setInc('vol');
            $taskUser = array(
                'task_suc_money' => array('exp','task_suc_money + '.$tasklogInfo['task_price']),
                'task_zong' => array('exp','task_zong + '.$tasklogInfo['task_price'])
            );
            $userModel->where('user_id = '.$tasklogInfo['user_id'])->save($taskUser);
            account_log($tasklogInfo['user_id'], $tasklogInfo['task_price'], 4,'完成任务', $tasklog_id);
            $ShopModel->where('user_id = '.$tasklogInfo['user_id'])->setInc('take_task');
            D('Common/Push')->push('收入提醒',$tasklogInfo['user_id'],'亲，您有一笔收入到账！','任务名称:'.$tasklogInfo['task_name'].'获得','收入金额：￥'.$tasklogInfo['task_price'] /100,'劳动换来的果实特别甜，继续加油吧！');
            $tasklogRes = $this->TaskLogModel->where('id = '.$tasklog_id)->save(array('valid_status' => 3));
            if($tasklogRes){
                M()->commit();
                $this->ajaxReturn(V(1, '完成'));
            }else{
                M()->rollback();
                $this->ajaxReturn(V(2, '失败'));
            }
            $this->ajaxReturn(V(0, $this->TaskLogModel->getError()));
        }
    }
    // 上传图片
    public function uploadImg() {

        //$this->_uploadImg();  //调用父类的方法
        $config = array(
            'rootPath' => '.'.C('UPLOAD_URL').'Task/',
            'savePath' => '',
            'maxSize' => C('UPLOAD_SIZE'),
            'exts' => 'jpg,jpeg,png,gif',
        );

        $Upload = new \Think\Upload($config);
        $info = $Upload->upload();

        if ($info === false) {
            $this->ajaxReturn(V(0, $Upload->getError()));
        } else {
            vendor('Alioss.autoload');
            $config = C('ALIOSS_CONFIG');

            $oss=new \OSS\OssClient($config['KEY_ID'],$config['KEY_SECRET'],$config['END_POINT']);
            $bucket=$config['BUCKET'];

            // 返回成功信息
            foreach($info as $file){
                $path = '.'.C('UPLOAD_URL').'Task/'.$file['savepath'].$file['savename'];
                $oss_path = trim($path, './');
                $local_path = trim($path, '.');
                $oss->uploadFile($bucket,$oss_path,$path);

                unlink($path);
                $data['nameosspath'] ='http://'.$bucket.'.'.$config['END_POINT'].'/'.$oss_path;
                $data['name'] =$local_path;

            }
            $this->ajaxReturn(V(1, 'success', $data));
        }
    }
    /**
     * 删除oss上指定文件
     * @param  string $object 文件路径 例如删除 /Public/README.md文件  传Public/README.md 即可
     */
    public function oss_delet_object(){

        // 实例化oss类
        $files = I('img_src', '','trim');
        $object = explode('com/',$files)[1];
        vendor('Alioss.autoload');
        $config=C('ALIOSS_CONFIG');
        $oss=new \OSS\OssClient($config['KEY_ID'],$config['KEY_SECRET'],$config['END_POINT']);
        $bucket=$config['BUCKET'];
        $test=$oss->deleteObject($bucket,$object);
        $this->ajaxReturn(V(1, '删除成功'));
    }
}