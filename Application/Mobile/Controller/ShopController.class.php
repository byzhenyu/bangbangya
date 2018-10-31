<?php
/**
 * @Description    商铺控制器
 * @Author         <byzhenyu@qq.com>
 * @Date           2018/10/29
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class ShopController extends CommonController {
    /*获取我的店铺信息*/
    public function myShopInfo()
    {
    	$user_id = UID;
        $ShopModel = D('Home/Shop');
        $field= 'u.head_pic,u.user_id,s.shop_name,s.shop_accounts,s.task_count,s.task_num,s.vol,s.complain_num,s.be_complain_num,s.magic_guild_num,s.top_time';
        $ShopInfo = $ShopModel->getShopInfo($user_id, '', $field);
        p($ShopInfo);
        exit;
        $this->assgin('ShopInfo', $ShopInfo);
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
}