<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/5 0005 8:59
 * @CreateBy       PhpStorm
 */
namespace Mobile\Controller;
use Think\Controller;
class IndexController extends Controller{
      public function index(){

          $bannerList = D('Home/Banner')->getBanner(array('type = 1'));
          $this->assign('bannerList', $bannerList);
          $this->display();
      }
}