<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     系统消息控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/16 0016 16:53
 * @CreateBy       PhpStorm
 */
namespace Pack\Controller;
use Common\Controller\CommonController;
class PushController extends CommonController {
    public function _initialize() {
        $this->Push = D("Home/Push");
    }
     public function getPush(){
          $p  = I('p', 1, 'intval');
          $where['user_id'] = array('in','0,'.UID);
          $pushList = $this->Push->getPush($where);
          if($p > 1){
              $this->ajaxReturn(V(1, '加载成功',$pushList['list']));
          }
          $this->assign('pushList',$pushList);
          $this->display();
     }
}