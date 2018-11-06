<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     充值控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/6 0006 13:05
 * @CreateBy       PhpStorm
 */

namespace Mobile\Controller;
use Common\Controller\CommonController;
class PayController  extends CommonController{
    /**
     * @desc 我的钱包
     * @param uid
     * @return mixed
     */
    public function myWallet(){
        $where['change_type'] = 0;
        $payRecord = getAccount($where);
        $where['change_type'] = array('IN','1,3,6');
        $expendRecord = getAccount($where);
        $this->assign('userInfo',$this->userInfo);
        $this->assign('payRecord',$payRecord);
        $this->assign('expendRecord',$expendRecord);
        $this->display();
    }
      /**
      * @desc 充值页面
      * @param
      * @return mixed
      */
      public function topUpsPage(){
          $this->display();
      }
      /**
      * @desc  用户提现
      * @param  user_id
      * @return mixed
      */
      public  function withdraw()
      {
          $this->display();
      }
}