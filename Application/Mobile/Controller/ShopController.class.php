<?php
/**
 * /**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     商铺控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/31 0031 16:41
 * @CreateBy       PhpStorm
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class ShopController extends CommonController {
    public function _initialize() {
        $this->shop = D("Home/shop");
    }
    /*获取我的店铺信息*/
    public function myShopInfo()
    {
    	$user_id = I('user_id', 0 ,'intval');
        $ShopModel = D('Home/Shop');
        $field= 'u.head_pic,u.user_id,u.total_money,u.nick_name,shop_accounts, s.top_time,s.shop_name,s.take_task,s.task_count,s.task_num,s.vol,s.appeal_num,s.be_appeal_num,s.complain_num,s.be_complain_num,s.top_time';
        $ShopInfo = $ShopModel->getShopInfo($user_id, '', $field);
        P($ShopInfo);
        $where['t.user_id']  =  $user_id;
        $where['t.end_time']  =  array('gt', NOW_TIME);
        $taskField = 't.id, t.price, t.task_num, t.title, c.category_name , c.category_img';
        $taskInfo = D('Home/Task')->getTaskList($where, $taskField);
        $where['t.end_time'] =  array(array('gt',NOW_TIME - 172800),array('lt',NOW_TIME)) ;
        $last_taskInfo = D('Home/Task')->getTaskList($where, $taskField);
        p($last_taskInfo);
        $this->assign('ShopInfo', $ShopInfo);
        $this->assign('last_taskInfo', $last_taskInfo['list']);
        $this->assign('taskInfo', $taskInfo['list']);
        $this->display();
    }
    /*获取店铺信息*/
    public function getShopInfo()
    {
    	$ShopModel = D('Home/Shop');
        $ShopInfo = $ShopModel->getAllShop();
        p($ShopInfo);
        exit;
    }
    /**
    * @desc 我的店铺置顶
    * @param UID
    * @return mixed
    */
    public function myTopShop(){
        $total_money = I('total_money', 0, 'intval');
        if(IS_POST){
            $data = I('post.', '', 'strip_tags');
            $data['user_id'] = UID;
            $res = $this->shop->topShop($data);
            if($res){
                $this->ajaxReturn(V(1, '置顶成功'));
            }else{
                $this->ajaxReturn(V(1, '置顶失败'));
            }
        }
        $this->assign('total_money', $total_money);
        $this->display();
    }
}