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

class IndexController extends CommonController {
        public function _initialize() {
            $this->user = D("Home/User");
        }
       public function Index()
       {
           if(UID !== 'UID'){
               $userList = $this->user->field('head_pic,nick_name')->where('user_id ='.UID)->find();
           }
           $login  = I('login', 0, 'intval');
           $bannerList = D('Home/Banner')->getBanner(array('type = 1'));
           $this->assign('bannerList', $bannerList);
           $this->assign('userList',$userList);
           $this->assign('login',$login);
       	   $this->display('index');
       }
}