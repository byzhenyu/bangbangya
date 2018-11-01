<?php
/**
 * /**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     商铺信息 Model
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/31 0031 16:41
 * @CreateBy       PhpStorm
 */
namespace Home\Model;
use  Think\Model;
class ShopModel extends Model{
    protected $findFields = array('id','user_id', 'shop_name', 'shop_img','shop_accounts','top_time','partner_time','add_time','status','task_count','task_num','vol','complain_num','be_complain_num','magic_guild_num');
    /**
     * 商铺信息查询
     * @param  $user_id   user_id
     * @param  $shop_id shop.userid
     * @param  $[isOwnShop] 是否是自己的店铺 1 是  0 不是
     * @return arr
     */
    public function getShopInfo($user_id = '',$shop_id = '' ,$field = '')
    {
    	  if($user_id == '') return false;
    	  if($shop_id == '') $shop_id = $user_id;
          /*店铺状态*/
          $where[] = array('u.user_id' => $shop_id,'s.status' => 1,'u.disabled' => 1,'u.status' => 1);
          $info = $this->alias('s')
                 ->join('__USER__ as u  on  u.user_id = s.user_id','LEFT')
                 ->field($field)
                 ->where($where)
                 ->find();
          /*判断是否是自己的店铺*/
         $user_id === $shop_id ? $info['isOwnShop'] = 1 : $info['isOwnShop'] = 0;

          return $info;
    }
    /**
     * 查看所有店铺
     * @param  $[where] [<条件查找>]
     * @return  array
     */
    public function getAllShop($where = [],$field = null ,$sort = ' s.top_time  DESC')
    {
    	$where[] = array('u.disabled' => 1,'u.status' => 1,'s.status' => 1);
    	$count =  $this->alias('s')
    	         ->join('__USER__ as u on u.user_id = s.user_id','LEFT')
    	         ->field($field) 
    	         ->where($where)
    	         ->count(); 
        $page = get_page($count,10);
        $shopList = $this->alias('s')
                 ->join('__USER__ as u on u.user_id = s.user_id','LEFT')
                 ->field($field)
                 ->where($where)
                 ->limit($page['limit'])
                 ->order($sort)
                 ->select();
        return array(
            'shopList'=>$shopList,
            'page'=>$page['page']
        );                        
    }
}
