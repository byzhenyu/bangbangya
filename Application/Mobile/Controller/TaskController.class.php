<?php
/**
 * @Description    任务控制器
 * @Author         <byzhenyu@qq.com>
 * @Date           2018/10/30
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
        $taskCategory = D('Home/Task')->getTaskCategory();
        /*任务信息*/
        $taskInfo = D('Home/Task')->getTaskList($where, '', $order);
        /*置顶店铺*/
        $shopWhere['s.top_time'] = array('gt', NOW_TIME);
        $shopField = 's.user_id, s.shop_img, s.shop_name';
        $topShop = D('Home/Shop')->getAllShop($shopWhere, $shopField);
        p($taskInfo);
        p($taskCategory);
        p($topShop);
        exit;
        $this->assign('taskCategory',$taskCategory);
        $this->assign('taskInfo', $taskInfo['list']);
        $this->assign('page', $taskInfo['page']);
        $this->display();
    }
    /**
    * @desc 任务发布
    * @param $POST['data']
    * @return mixed
    */
    public function taskAnnouncement(){

    }
}