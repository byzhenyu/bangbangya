<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     意见反馈控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/8 0008 18:20
 * @CreateBy       PhpStorm
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class FeedbackController extends CommonController {
    public function _initialize() {
        $this->Feedback = D("Home/FeedBack");
    }
      public function feedback(){
          if(IS_POST){
                 $data = I('post.', 1);
                 $data['user_id'] = UID;
                 $insRes = $this->Feedback->add($data);
                 if($insRes){
                     $this->ajaxReturn(V(1,'反馈成功'));
                 }else{
                     $this->ajaxReturn(V(2,$this->Feedback->getDbError()));
                 }
          }
          $this->display();
      }
}