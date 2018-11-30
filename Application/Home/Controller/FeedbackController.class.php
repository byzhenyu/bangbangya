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
namespace Home\Controller;
use Common\Controller\UserCommonController;
class FeedbackController extends UserCommonController {
    public function _initialize() {
        $this->Feedback = D("Home/FeedBack");
    }
    /**
     * 意见反馈
     **/
    public function feedback(){
        if(IS_POST){
            $data = I('post.', '');
            $create = $this->Feedback->create($data);
            if(false !== $create){
                $insRes = $this->Feedback->add($data);
                if($insRes){
                    $this->ajaxReturn(V(1,'反馈成功'));
                }
                else{
                    $this->ajaxReturn(V(0, '反馈失败'));
                }
            }
            else {
                $this->ajaxReturn(V(0,$this->Feedback->getError()));
            }
        }
        $this->display();
    }
}