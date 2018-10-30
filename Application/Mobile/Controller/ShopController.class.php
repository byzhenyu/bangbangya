<?php
/**
 * @Description    登录注册控制器
 * @Author         <406752025@qq.com>
 * @Date           2018/10/29
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class ShopController extends CommonController {
    public function __construct(){
        parent::__construct();
        $this->get_global_config();
    }
    /*获取我的店铺信息*/
    public function myShopInfo()
    {
    	$user_id = UID;
        $ShopModel = D('Home/Shop');
        $ShopInfo = $ShopModel->getShopInfo($user_id);
        p($ShopInfo);
        die;
        $this->assgin('ShopInfo',$ShopInfo);
        $this->display();
    }

}