<?php
/**
 * /**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     轮播图类
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/11/05 0031 09:13
 * @CreateBy       PhpStorm
 */
namespace Home\Model;
use Think\Model;
class BannerModel extends Model{
    protected $selectFields = array( 'title', 'img_url', 'jump_url');
    protected $findFields = array('title', 'img_url', 'jump_url');
    /**
    * @desc  轮播图查询
    * @param
    * @return mixed
    */
    public  function getBanner($where = [], $field = null, $sort = 'sort ASC' ){
          if(is_null($field)){
              $field = $this->selectFields;
          }
          $bannerList = $this->field($field)->where($where)->select();
          return $bannerList;
    }
}