<?php
/**
 * /**  
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description    用户控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/31 0031 15:16
 * @CreateBy       PhpStorm
 */

namespace Mobile\Controller;
use Common\Controller\CommonController;
class UserController extends CommonController {
    public function _initialize() {
        $this->user = D("Home/User");
    }
    /**
    * @desc 用户中心
    * @param uid
    * @return mixed
    */
    public function personalCenter(){
          $user_id = UID;
          $where['u.user_id'] = $user_id;
          $field = 'u.head_pic, u.nick_name, u.total_money,u.bonus_money,s.shop_type, u.task_suc_money,u.user_id, s.shop_accounts,s.take_task';
          $userList = $this->user->getUserInfo($where, $field);
          $this->assign('userList',$userList);
          $this->display();
    }
    /**
    * @desc 保证金
    * @param UID
    * @return mixed
    */
    public function securityDeposit(){
        $shop_accounts = D('Home/Shop')->where('user_id = '.UID)->getField('shop_accounts');
        /*是否解冻*/
        if($shop_accounts > 0){
            $unfreeze = 1;
        }else{
            $unfreeze = 0;
        }
        $this->assign('unfreeze',$unfreeze);
        $this->assign('shop_accounts',$shop_accounts);
        $this->display();
    }
    /**
    * @desc  充值保证金
    * @param  UID
    * @return mixed
    */
    public function accountsUpsPage(){
        $shopModel = D('Home/Shop');
        if(IS_POST){
            $data = I('post.', 1);
            $res  =  user_money(UID, $data['shop_accounts']);
            if(!$res){
                $this->ajaxReturn(V(2, '余额不足'));
            }else{
                M()->startTrans();
                $userRes = $this->user->where('user_id = '.UID)->setDec('total_money',$data['shop_accounts']);
                $shopRes = $shopModel->where('user_id = '.UID)->setInc('shop_accounts',$data['shop_accounts']);
                if($userRes && $shopRes){
                    account_log( UID, $data['shop_accounts'], 5 , '缴纳保证金',UID);
                    M()->commit();
                    $this->ajaxReturn(V(1, '保证金缴纳成功'));
                }else{
                    M()->rollback();
                    $this->ajaxReturn(V(0, $this->user->getError()));
                }
            }
        }
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
    /**
     * 任务总收入排名
     * @return   arr
     */
    public function getRankList()
    {
        $where['task_suc_money']  = array('neq', 0);
        $field = 'user_id, head_pic, nick_name, task_suc_money';
        $rankList = $this->user->getRankList($where, $field);
        $this->assign('randList', $rankList);
        $this->display();
    }
    /**
    * @desc 合作商查看
    * @param  $user_id
    * @param  $shop_type
    * @return mixed
    */
     public  function partners(){
         $vip = getVip();
         $user_id = UID;
         $where['u.user_id'] = $user_id;
         $field = 's.shop_type, s.partner_time ';
         $shopInfo  = $this->user->getUserInfo($where, $field);
         if($shopInfo['shop_type']  == 0){
                  $shop_type = 0;
         }elseif($shopInfo['partner_time']  < NOW_TIME){
                  $shop_type = 0;
         }else{
                  $shop_type = $shopInfo['shop_type'];
         }
         $this->assign('shop_type', $shop_type);
         $this->assign('vip', $vip);
         $this->display();
      }
      /**
      * @desc  上传用户头像  OSS
      * @return url
      */
      public function uploadImg(){
        $config = array(
            'rootPath' => '.'.C('UPLOAD_URL').'head_pic/',
            'savePath' => '',
            'maxSize' => C('UPLOAD_SIZE'),
            'exts' => 'jpg,jpeg,png,gif',
        );

        $Upload = new \Think\Upload($config);
        $info = $Upload->upload();

        if ($info === false) {
            $this->ajaxReturn(V(0, $Upload->getError()));
        } else {
            vendor('Alioss.autoload');
            $config = C('ALIOSS_CONFIG');

            $oss=new \OSS\OssClient($config['KEY_ID'],$config['KEY_SECRET'],$config['END_POINT']);
            $bucket=$config['BUCKET'];

            // 返回成功信息
            foreach($info as $file){
                $path = '.'.C('UPLOAD_URL').'head_pic/'.$file['savepath'].$file['savename'];
                $oss_path = trim($path, './');
                $local_path = trim($path, '.');
                $oss->uploadFile($bucket,$oss_path,$path);
                unlink($path);
                $data['nameosspath'] ='http://'.$bucket.'.'.$config['END_POINT'].'/'.$oss_path;
                $data['name'] =$local_path;
            }
            $user_pic = $this->user->where('user_id = '.UID)->getField('head_pic');
            /*删除OSS文件*/
            if($user_pic != '/Uploads/default.jpg' || $user_pic  != '') {
                 $object = $object = explode('com/',$user_pic)[1];
                 $oss->deleteObject($bucket,$object);
            }
            $this->user->where('user_id = '.UID)->save(array('head_pic'=>$data['nameosspath']));
            $this->ajaxReturn(V(1, '更换头像成功', $data));
        }
      }
}