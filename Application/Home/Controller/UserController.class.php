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
class UserController extends CommonController{
    public function _initialize() {
        $this->user = D("Home/User");
    }

    public function userList()
    {
        $this->display();
    }

    /**
     * 好友邀请
     * @return [type] [description]
     */
    public function friendQequest()
    {
        $userInfo = $this->user->field('head_pic, nick_name, invitation_code')->find();
        $this->assign('userInfo', $userInfo);
        $this->display();
    }

}