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
use Common\Controller\CommonController;
use Common\Controller\UserCommonController;

class TaskController extends UserCommonController{
       public function _initialize() {
           $this->user = D("Home/User");
           $this->Task = D("Home/Task");
       }
       public function listTask()
       {
           if(UID !== 'UID'){
               $userList = $this->user->field('head_pic,nick_name')->where('user_id ='.UID)->find();
           }
           $this->assign('userList',$userList);
       	   $this->display();
       }

    /**
     * @desc 发布任务
     * @param $POST['data']
     * @return mixed       发布任务只需要判断用户现在的余额  不冻结资金 改动   用户审批一个扣一个的钱
     */
    public function addTask(){
        $id = I('id', 0 ,'intval');
        $where['user_id'] = UID;
        $taskModel = D('Home/Task');
        $taskStepModel = D('Home/TaskStep');
        $taskCategoryModel = D('Home/TaskCategory');
        $taskCategoryField = 'id, category_name, limit_num, limit_money';
        /*任务分类信息*/
        $taskCategoryInfo = $taskCategoryModel->getTaskCategory(array('status'=>1), $taskCategoryField);

        /*用户总金额金额*/
        $userInfo  = D('Home/User')->getUserInfoWithShop(array('u.user_id'=>UID), 'shop_type,partner_time, total_money');
        $userMoney = $userInfo['total_money'];
        //手续费
        $orderFee = C('BASE_ORDER_FEE');
        if ($userInfo['shop_type'] != 0 && $userInfo['partner_time'] > NOW_TIME) {
            $orderFee = M('VipLevel')->where(array('type'=>$userInfo['shop_type']))->getField('order_fee');
        }

        if (IS_POST) {
            $data = I('post.', '');
            $taskData = $data['task']; //任务
            $step0Data = $data['step'][0]; //验证图
            $step1Data = $data['step'][1]; //步骤
            $taskData['end_time'] = strtotime($taskData['end_time']);
            $taskData['price'] = yuan_to_fen($taskData['price']);
            $taskData['total_price'] = ($taskData['price'] * $taskData['task_num'] * (1 + ($orderFee/100)));
            $taskData['task_zong'] = $taskData['task_num'];
            $taskData['audit_status'] = 0;
            if ($taskModel->create($taskData) ===false) {
                $this->ajaxReturn(V(0, $taskModel->getError()));
            }
            M()->startTrans();
            $task_id = $id;
            if ($id > 0) {
                $res = $taskModel->where(array('id'=>$id))->save($taskData);

                if ($res === false) {
                    M()->rollback();
                    $this->ajaxReturn(V(0, '任务保存失败'));
                }
                $taskStepModel->where(array('task_id'=>$id))->delete();
                //处理步骤表数据

            } else {
                $task_id = $taskModel->add($taskData);
                if (!$task_id) {
                    M()->rollback();
                    $this->ajaxReturn(V(0, '任务添加失败'));
                }

            }
            if (!empty($step0Data)) {
                foreach ($step0Data as &$val) {
                    $val['task_id'] = $task_id;
                    $val['step_text'] = '';
                    $val['type'] = 2;

                }
                $step0Res = $taskStepModel->addAll($step0Data);
                if ($step0Res === false ) {
                    M()->rollback();
                    $this->ajaxReturn(V(0, '验证图保存失败'));
                }
            }
            if (!empty($step1Data)) {
                foreach ($step1Data as &$val) {
                    $val['task_id'] = $task_id;
                    $val['type'] = 1;
                }
                $step1Res = $taskStepModel->addAll($step1Data);
                if ($step1Res === false) {
                    M()->rollback();
                    $this->ajaxReturn(V(0, '步骤保存失败'));
                }
            }
            M()->commit();
            $this->ajaxReturn(V(1, '操作成功'));

        }

        $taskInfo = $taskModel->getMyTaskDetail($id);
        $base = $taskInfo['step_info'] ? count($taskInfo['step_info']) : 0;
        $this->count = $taskInfo['check_info'] ? count($taskInfo['check_info']) : 0;
        $this->base = $base;
        $this->assign('id', $id);
        $this->assign('orderFee',$orderFee);
        $this->assign('taskInfo', $taskInfo);
        $this->assign('userMoney', $userMoney);
        $this->assign('taskCategoryInfo', $taskCategoryInfo);
        $this->display();
    }
   //我的发布
    /**
     * @desc  我的发布
     * @param UID
     * @return array
     */
    public function myTask() {
        $where['t.user_id'] = UID;
        $field = 't.id,t.end_time, t.top,t.top_time , t.recommend, t.re_time, t.title, t.audit_info,t.price,t.task_zong, t.task_num, t.total_price, t.audit_status, t.is_show, t.add_time, c.category_name, c.category_img ';
        $taskList = D('Home/Task')->getMyTask($where, $field);
        $total_money = D('Home/User')->where('user_id = '.UID)->getField('total_money');
       // p($taskList);
        $this->assign('taskList', $taskList);
        $this->assign('total_money', $total_money);

        $this->display();
    }

    //我的任务详情
    public function myTaskDetail() {
        $id = I('id', 0, 'intval');
        $where['t.id'] = $id;
        $taskModel = D('Home/Task');
        $taskDetail = $taskModel->getTaskDetail($where);
        $this->assign('id', $id);
        $this->assign('taskDetail', $taskDetail);
        $this->display();
    }
    /**
     * @desc  上传图片
     */
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
     * @desc  推荐置顶
     * @param  $task_id
     * @return mixed
     */
    public function topTask(){
        $data = I('post.', 4);
        $res  =  user_money(UID, $data['money']);
        if(!$res){
            $this->ajaxReturn(V(2, '余额不足'));
        }else{
            M()->startTrans();
            $userModel = D('Home/User');
            $userRes  = $userModel->where('user_id = '.UID)->setDec('total_money',$data['money']);
            $taskData = $this->Task->where(array('id'=>$data['id']))->field('top_time,re_time')->find();
            if($data['top'] == 1){
                $type = 10;
                $desc = '任务置顶';
                if($taskData['top_time'] > NOW_TIME){
                    $taskData['top_time'] = $data['topNum'] * 3600  + $taskData['top_time'];
                }else{
                    $taskData['top_time'] = $data['topNum'] * 3600  + NOW_TIME;
                    $taskData['top'] = 1;
                }
            }else{
                $type = 9;
                $desc = '任务推荐';
                if($taskData['re_time'] > NOW_TIME){
                    $taskData['re_time'] = $data['topNum'] * 3600  + $taskData['re_time'];
                }else{
                    $taskData['recommend'] = 1;
                    $taskData['re_time'] = $data['topNum'] * 3600  + NOW_TIME;
                }
            }
            account_log(UID, $data['money'], $type, $desc, $data['id']);
            $taskRes = $this->Task->where(array('id'=>$data['id']))->save($taskData);
            if($taskRes  && $userRes){
                M()->commit();
                $this->ajaxReturn(V(1, $desc.'成功'));
            }else{
                M()->rollback();
                $this->ajaxReturn(V(2, $desc.'失败'));
            }
            $this->ajaxReturn(V(0, $this->Task->getError()));
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