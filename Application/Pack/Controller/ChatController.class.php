<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     信息交流控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/15 0015 8:57
 * @CreateBy       PhpStorm
 */
namespace Pack\Controller;
use Common\Controller\CommonController;
class ChatController extends CommonController{
    public function _initialize()
    {
        $this->Chat = D("Home/Chat");
    }
    /**
    * @desc  信息交流
    * @param  $data  user_id task_id
    * @return mixed
    */
    public function addChat(){
          $data = I('post.');
          $taskModel = D('Home/Task');
          if($data['type']  == 0){
              $taskid = D('Home/TaskLog')->where('id = '.$data['task_log_id'])->getField('task_id');
              $data['user_id'] = $taskModel->where('id = '.$taskid)->getField('user_id');
          }
          else{
              $data['task_user_id'] = UID;
          }
          unset($data['type']);
          $res = $this->Chat->add($data);
          if($res){
              $this->ajaxReturn(V(1, '回复成功!'));
          }
          $this->ajaxReturn(V(0, '回复失败!'));
    }
}