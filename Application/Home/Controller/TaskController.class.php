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
class TaskController extends UserCommonController{
    public function _initialize() {
        $this->Task = D("Home/Task");
    }


    /**
     * 首页接单信息页面
     * @param UID
     * @param  [order] 排序方式 未定
     * @return array
     */
    public function listTask()
    {

        $shopWhere['s.top_time'] = array('gt', NOW_TIME);
        $shopField = 's.user_id, s.shop_img, s.shop_name';
        $topShop = D('Home/Shop')->getAllShop($shopWhere, $shopField);
        /*任务类别*/
        $taskCategory = D('Home/TaskCategory')->getTaskCategory();

        $this->assign('topShop',$topShop['shopList']);
        $this->assign('taskCategory',$taskCategory);
        $this->display();
    }

    /**
     * @desc 获取任务列表展示
     **/
    public function ajax_listTask(){
        $keyword = I('keyword', '');
        /* order 接单赚钱的条件查询 */
        $typeOrder = I('typeOrder',0,'intval');

        switch ($typeOrder) {
            case 1:
                $order = 't.top_time DESC,t.re_time DESC,t.add_time DESC';
                break;
            case 2:
                $order = 't.top_time DESC, re_time DESC, t.end_time DESC';
                break;
            case 3:
                $order = 't.top_time DESC,t.re_time DESC,s.top_time DESC, s.partner_time DESC, t.add_time ASC';
                break;
            case 4:
                $order = 't.look_num DESC';
                break;
            case 5:
                $where['t.mobile_type'] = '苹果';
                $order = 't.top_time DESC,t.re_time DESC,t.add_time DESC';
                break;
            case 6:
                $order = 't.price DESC,t.top_time DESC,t.re_time DESC,t.add_time DESC';
                break;
            default:
                $order = 't.top_time DESC, t.re_time DESC, t.add_time DESC';
                break;
        }

        /*选择任务类型*/
        $taskCategoryId = I('taskCategoryId', 0, 'intval');
        if($taskCategoryId)
        {
            $where['t.category_id'] = $taskCategoryId;
        }
        /*任务标题查询  标题  任务id号*/
        if ($keyword) {
            $where['t.title|t.id'] = array('like', '%'.$keyword.'%');
        }
        $where['t.user_id'] = array('NEQ',UID);
        //需要判断 是否已接单
        $logwhere['user_id'] = array('eq', UID);
        $logwhere['valid_status'] = array('in', [0,1,2]);
        $logwhere['status'] = array('eq', 1);
        $log_ids = M('TaskLog')->where($logwhere)->getField('task_id',true);

        if (!empty($log_ids)) {
            $where['t.id'] = array('not in', $log_ids);
        }
        /*任务信息*/
        $where['t.end_time'] = array('gt', NOW_TIME); //未结束
        $where['t.audit_status'] = array('eq', 1);//审核通过
        $taskInfo = D('Home/Task')->getTaskList($where, '', $order);
        $list = $taskInfo['list'];
        if (!empty($list)) {
            foreach ($list as $k=>$v) {
                $list[$k]['price'] = fen_to_yuan($v['price']);
                if ($list[$k]['top_time'] > NOW_TIME) {
                    $list[$k]['top'] = 1;
                }
                if ($list[$k]['re_time'] > NOW_TIME) { //推荐 暂时缺少图片未判断
                    $list[$k]['ret'] = 1;
                }
            }
        }
        $this->assign('taskInfo', $list);
        $this->assign('page', $taskInfo['page']);
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
        $where['t.user_id'] = array('eq', UID);
        $field = 't.id,t.end_time, t.top,t.top_time , t.recommend, t.re_time, t.title, t.audit_info,t.price,t.task_zong, t.task_num, t.total_price, t.audit_status, t.is_show, t.add_time, c.category_name ';
        $taskList = D('Home/Task')->getMyTask($where, $field);
        $total_money = D('Home/User')->where(array('user_id'=>UID))->getField('total_money');
        $this->assign('taskList', $taskList);
        $this->assign('total_money', $total_money);
        $this->display();
    }

    /**
     * @desc  接单任务详情  && 我的任务上传验证页面
     * @param  id
     * @param  user_id
     * @return mixed
     */
    public  function taskDetail(){
        $id = I('id', 0, 'intval');
        $where['t.id'] = $id;
        $taskModel = D('Home/Task');
        $taskDetail = $taskModel->getTaskDetail($where);
        //p($taskDetail);
        $this->assign('id', $id);
        $this->assign('taskDetail', $taskDetail);
        $this->display();
    }

    /**
     * @desc  我的任务详情
     */
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
     * @desc 暂停任务
     * @param  id
     * @param  audit_status
     * @return json
     */
    public function pause() {
        $data = I('post.', 2);
        $taskPause = $this->Task->where(array('id'=>$data['id']))->save($data);
        if ($taskPause) {
            $this->ajaxReturn(V(1, $data['audit_status'] == 1 ? '开启成功':'暂停成功'));
        }
        $this->ajaxReturn(V(0, $this->Task->getError()));
    }
    /**
     * @desc  追加任务价格
     * @param
     * @return mixed
     */
    public function addTaskPrice(){
        $data = I('post.', 3);
        $res  =  user_money(UID, $data['money']);
        if(!$res){
            $this->ajaxReturn(V(2, '余额不足'));
        }else{
            M()->startTrans();
            $taskRes = $this->Task->where(array('id'=>$data['id']))->save($data);
            if($taskRes){
                M()->commit();
                $this->ajaxReturn(V(1, '上调成功'));
            }else{
                M()->rollback();
                $this->ajaxReturn(V(2, '上调失败'));
            }
            $this->ajaxReturn(V(0, $this->Task->getError()));
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
            } else {
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
     * @desc 任务下架
     * @param  $task_id
     * @return mixed
     */
    public function taskSold(){
        $taskLogModel = D('Home/TaskLog');
        $where['task_id'] = I('id', 0 ,'intval');
        $where['valid_status']  = array('in','0,1');
        /*计算正在进行时  和未审核的 钱数*/
        $taskLogInfo = $taskLogModel->field('task_price,user_id, task_id')->where($where)->select();
        $money = 0;
        foreach ($taskLogInfo as $key=>$value){
            $money += $value['task_price'];
        }
        $res  = user_money(UID,$money);
        if(!$res){
            $this->ajaxReturn(V(2, '您的余额余额不足,下架失败'));
        }else{
            //开启事务
            M()->startTrans();
            $userModel = D('Home/User');
            if($money != 0){
                $userRes  = $userModel->where(array('user_id'=>UID))->setDec('total_money',$money);
            }else{
                $userRes = true;
            }
            account_log(UID, $money, 3,'任务结算',$where['task_id']);
            D('Common/Push')->push('支出提醒',UID,'亲，您发布的任务下架操作！','任务编号:'.$where['task_id'],'支出金额：￥'.$money /100,'置顶您的店铺或任务才能被更好的接单呦！');
            foreach ($taskLogInfo as $key=>$value){
                $userMoney = array(
                    'task_suc_money' => array('exp','task_suc_money + '.$value['task_price']),
                    'total_money' => array('exp','total_money + '.$value['task_price'])
                );
                $userModel ->where('user_id = '.$value['user_id'])->save($userMoney);
                account_log( $value['user_id'], $value['task_price'], 4,'完成任务',$where['task_id']);
                D('Common/Push')->push('收入提醒',$value['user_id'],'亲，您有一笔收入到账！','任务名称:'.$value['task_name'],'收入金额：￥'.$value['task_price'] /100,'劳动换来的果实特别甜，继续加油吧！');
                $taskLogModel->where('task_id = '.$value['task_id'])->save(array('valid_status' => 3));
            }
            $taskRes = $this->Task->where('id = '.$where['task_id'])->save(array('audit_status' => 3,'end_time' => NOW_TIME));
            if($userRes && $taskRes){
                M()->commit();
                $this->ajaxReturn(V(1, '下架成功'));
            }else{
                M()->rollback();
                $this->ajaxReturn(V(2, '下架失败'));
            }
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

    //丢弃任务
    public function discardTask() {
        $id = I('id', 0, 'intval');
        $res = D('Home/Task')->discardTask($id);
        $this->ajaxReturn($res);
    }

    //发布
    public function changeShowStatus() {
        $id = I('id', 0, 'intval');
        $where['id'] = array('eq', $id);
        $taskModel = M('Task');
        $is_show = $taskModel->where($where)->getField('is_show');

        if ($is_show == 0) {
            $data['is_show'] = 1;
            $data['audit_status'] = 0;
        } else {
            $data['is_show'] = 0;
        }
        $res = $taskModel->where($where)->save($data);
        if ($res === false) {
            $this->ajaxReturn(V(0, '操作失败'));
        } else {
            $this->ajaxReturn(V(1, '操作成功'));
        }
    }

    public function del(){
        $id = I('id', 0 , 'intval');
        $info = D('Home/Task')->delTask($id);
        $this->ajaxReturn($info);
    }
}