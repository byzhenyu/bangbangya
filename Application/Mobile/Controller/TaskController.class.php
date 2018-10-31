<?php
/**
 * @Author: wangzhenyu
 * @Date:   2018-10-29 20:11:54
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-10-29 22:14:57
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class TaskController extends CommonController {
    public function __construct(){
        parent::__construct();
        $this->get_global_config();
    }
    /**
     * 首页接单信息页面
     * @param $UID
     * @param  $[order] 排序方式 未定
     * @return
     */
    public function listTask(){
        define('UID',1);
        $keyword = I('keyword', '');
        /*order */
        $typeOrder = I('typeOrder',0,'intval');
        if($order)
        {
            switch ($order) {
                case '1':
                    $order = 't.add_time DESC';
                    break;
                 case '2':
                    $order = 't.add_time DESC';
                    break;
                default:
                    $order = 't.add_time DESC';
                    break;
            }
        }
        /*选择任务类型*/
        $taskCategoryId = I('taskCategoryId',0,'intval');
        if($taskCategoryId)
        {
            $where['t.category_id'] = $taskCategoryId;
        }
        /*任务标题查询  标题  任务id号*/
        if ($keyword) {
            $where['t.title|t.id'] = array('like','%'.$keyword.'%');
        }
        $where['t.user_id'] = array('NEQ',UID);
        /*任务信息*/
        $taskInfo = D('Home/Task')->getTaskList($where,'',$order);
        /*任务类别*/
        $taskCategory = D('Home/Task')->getTaskCategory();
        /*置顶店铺*/
        $topShop = D('Home/Task')->getTopShop();
        p($taskInfo);
        p($taskCategory);
        exit;
        $this->assign('taskCategory',$taskCategory);
        $this->assign('taskInfo', $taskInfo['list']);
        $this->assign('page', $taskInfo['page']);
        $this->display();
    }
}