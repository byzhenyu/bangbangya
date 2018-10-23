<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 * 
 * @Description    商家web主页
 * @Author         songgy<1661745274@qq.com>
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/03/15
 * @CreateBy       sublime
 * @ModifiedBy     
 */
namespace Agent\Controller;
use Common\Controller\AgentCommonController;
class IndexController extends AgentCommonController {

    public function index(){
        $this->menu_list = $this->getMenu();
        $this->display();
    }

    private function getMenu() {
        require_once(APP_PATH . '/Agent/Conf/menu.php');
        $menus = array();
        foreach($modules as $key => $val){
                $menus[$key]['label'] = $val['label'];
                foreach($val['items'] as $skey => $sval){
                     $menus[$key]['items'][$skey]['label'] = $sval['label'];
                     $menus[$key]['items'][$skey]['action'] = $sval['action'];
                }
        }
        return $menus;
    }
         
}