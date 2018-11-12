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
     * @param  $[is_top] 是否是置顶 1 是  0 不是
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
         $info['top_time'] > NOW_TIME? $info['is_top'] = 1 :$info['is_top'] = 0;
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
    /**
    * @desc  置顶店铺
    * @param  $uid 用户ID
    * @param  $top_time 置顶时间 timestamp
    * @param  $zong 置顶需要的money
    * @return mixed
    */
    public  function  topShop($data){
        $where['user_id'] = $data['user_id'];
        $userModel = D('Home/User');
        /*开启事务*/
        M() ->startTrans();
        $user_total = $userModel->getUserField($where , 'total_money');
        if($user_total < $money){
            return false;
        }
        $userRes =  D('Home/User')->where($where)->setDec('total_money',$data['zong']);
        $shopTopTime = $this->where($where)->getField('top_time');
        if($shopTopTime < NOW_TIME){
              $changeTime = NOW_TIME + $data['top_time'];
        }else{
              $changeTime = $shopTopTime + $data['top_time'];
        }
        account_log($data['user_id'], $data['zong'], 6, '置顶店铺','');
        $shopRes = $this->where($where)->save(array('top_time' => $changeTime));
        if($userRes && $shopRes){
             M()->commit();
             return true;
        }else{
             M()->rollback();
             return false;
        }
    }
    /**
     * @desc  开通合作商
     * @param $user_id
     * @param type  合作商类型  1 普通  2 黄金  3 白金
     * @param money  money   分
     * @param time  增加的时间   1  月卡 2 年卡
     * @return mixed
     */
    public function getVip($user_id, $type , $money ,$time){
        $where['user_id'] = $user_id;
        $update = [];
        /*时间换算*/
        if($time == 1){
            $update['partner_time'] = strtotime("+1months");
        }else{
            $update['partner_time'] = strtotime("next year");
        }
        $shopInfo = $this->field('shop_type , partner_time ')->where('user_id = '.$user_id)->find();
        //开启事务
        M()->startTrans();
        if($type  == $shopInfo['shop_type']){
              if($shopInfo['partner_time'] > NOW_TIME){
                  $update['partner_time'] = $update['partner_time'] + $shopInfo['partner_time'] - NOW_TIME;
              }
        }else{
              $update['shop_type'] = $type;
        }
        $shopRes  = $this->where('user_id = '.$user_id)->save($update);
        $userModel = D('Home/User');
        $user_total = $userModel->getUserField($where , 'total_money');
        if($user_total < $money){
            M()->rollback();
            return false;
        }
        $userRes =  D('Home/User')->where('user_id = '.$user_id)->setDec('total_money',$money);
        account_log($user_id, $money, 7, '申请'.C(VIP_LEVEL)[$type],'');
        if($shopRes && $userRes){
            M()->commit();
            return true;
        }else{
            M()->rollback();
            return false;
        }


   }
}
