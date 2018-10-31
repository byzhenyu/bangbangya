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