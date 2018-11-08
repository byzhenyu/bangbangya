<?php
/**
 * /**  
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     任务信息控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/31 0031 15:16
 * @CreateBy       PhpStorm
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class TaskController extends CommonController {
    /**
     * 首页接单信息页面
     * @param $UID 
     * @param  $[order] 排序方式 未定
     * @return
     */
    public function listTask(){
        $keyword = I('keyword', '');
        /* order 接单赚钱的条件查询 */
        $typeOrder = I('typeOrder',0,'intval');
        if ($typeOrder){
            switch ($typeOrder) {
                case '1':
                    $order = 't.add_time DESC';
                    break;
                 case '2':
                    $order = 't.end_time DESC';
                    break;
                case '3':
                    $where['s.top_time|s.partner_time'] = array('gt',NOW_TIME);
                    break;
                case '4':
                    $order = 't.look_num DESC';
                    break;
                case '5':
                    $where['t.type'] = '苹果';
                    break;
                default:
                    $order = 't.add_time DESC';
                    break;
            }
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
        /*任务类别*/
        $taskCategory = D('Home/TaskCategory')->getTaskCategory();
        /*任务信息*/
        $taskInfo = D('Home/Task')->getTaskList($where, '', $order);
        /*置顶店铺*/
        $shopWhere['s.top_time'] = array('gt', NOW_TIME);
        $shopField = 's.user_id, s.shop_img, s.shop_name';
        $topShop = D('Home/Shop')->getAllShop($shopWhere, $shopField);

        $this->assign('topShop',$topShop['shopList']);
        $this->assign('taskCategory',$taskCategory);
        $this->assign('taskInfo', $taskInfo['list']);
        $this->assign('page', $taskInfo['page']);
        $this->display();
    }
    /**
    * @desc 发布任务
    * @param $POST['data']
    * @return mixed
    */
    public function addTask(){
        $where['user_id'] = UID;
        $taskCategoryModel = D('Home/TaskCategory');
        $taskCategoryField = 'id, category_name';
        /*任务分类信息*/
        $taskCategoryInfo = $taskCategoryModel->getTaskCategory('', $taskCategoryField);
        /*用户总金额金额*/
        $userInfo  = session('user_auth');
        $userMoney = $userInfo['total_money'];
        if($userInfo['shop_type'] != 0 && $userInfo['top_time'] > NOW_TIME){
            $userInfo['shop_type'] = 0;
        }

        $id = I('id', 0 ,'intval');
        $taskModel = D('Home/Task');
        $taskStepModel = D('Home/TaskStep');
        if($id >0) {
            $taskInfo = $taskModel->getMyTaskDetail($id);
        }else{
            $taskInfo = [];
        }
        if (IS_POST) {
            $data = json_decode(I('data', '', 'strip_tags'),true);
            if ($id) {
                if ($taskModel->save() !== false) {
                    $this->ajaxReturn(V(1, '编辑成功'));
                }
            } else {
                $createTask = $taskModel->addTask($data, $where);
                if($createTask)
                {
                    $this->ajaxReturn(V(1, '添加成功'));
                }
            }
            $this->ajaxReturn(V(0, $taskModel->getDbError()));
        }
//        P($userMoney);
//        p($taskCategoryInfo);
//        p($taskInfo);
//        exit;
        $this->assign('taskInfo', $taskInfo);
        $this->assign('userMoney', $userMoney);
        $this->assign('taskCategoryInfo', $taskCategoryInfo);
        $this->display();
    }
    /**
    * @desc  接单任务详情  && 我的任务上传验证页面
    * @param  $id
    * @param  $user_id
    * @return mixed
    */
     public  function taskDetail(){
        $id = I('id', 0, 'intval');
        $where['t.id'] = $id;
        $field = 'u.nick_name, u.head_pic, s.shop_accounts, s.top_time,s.user_id, t.id, c.category_name, t.price, t.validate_words, t.link_url, t.remark, t.end_time ';
        $taskModel = D('Home/Task');
        $taskDetail = $taskModel->getTaskDetail($where, $field);
        p($taskDetail);
//        exit;
        $this->assign('taskDetail', $taskDetail);
        $this->display();
    }
    /**
    * @desc  我的发布
    * @param UID
    * @return mixed
    */
    public function myTask(){
        $this->display();
    }
}