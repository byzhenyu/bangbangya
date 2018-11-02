<?php
/**
 * /**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     粉丝关注控制器
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/10/31 0031 16:41
 * @CreateBy       PhpStorm
 */
namespace Mobile\Controller;
use Common\Controller\CommonController;
class FansController extends CommonController {
    public function __construct(){
        parent::__construct();
        $this->get_global_config();
    }
    /**
     * 我的粉丝和我的关注信息
     */
    public function FansList()
    {
    	$where['f.fans_user_id']  =  UID;
    	$field = 'f.user_id,u.head_pic,u.nick_name,f.add_time';
        $fansModel = D('Home/Fans');
        /*我的粉丝*/
        $fanslist =  $fansModel->getFansList($where, $field, '', 0);
        /*我的关注*/
        unset($where);
        $where['f.user_id']  =  UID;
        $focuslist =  $fansModel->getFansList($where, $field, '', 1);
        p($fanslist);
        p($focuslist);
        $this->assign('fanslist',$fanslist);
        $this->assign('focuslist',$focuslist);
        $this->display();
    }
    /**
     * 关注
     * $user_id 用户ID
     * $fans_id 关注这个ID
     */
    public function attention()
    {
        $where['user_id'] = UID;
        $where['fans_user_id'] = I('fans_id',0 ,'intval');
        $fansModel = D('Home/Fans');
        /*查看是否有信息*/
        if (fansSverify($where['user_id'], $where['fans_user_id'])){
	        $result = $fansModel->where($where)->save(array('status' => 1));
        	$this->ajaxReturn(V(0, '关注成功'));
        }else{
        	if ($fansModel->add($where) !== false){
	            $this->ajaxReturn(V(0, '关注成功'));
	        }
        }
        $this->ajaxReturn(V(2, '关注失败'));
    }
    public function removeAttention()
    {
        $where['user_id'] = UID;
        $where['fans_user_id'] = I('fans_id',0 ,'intval');
        $fansModel = D('Home/Fans');
        /*查看是否有信息*/
        if (fansSverify($where['user_id'],$where['fans_user_id'] )){
	        $result = $fansModel->where($where)->save(array('status' => 0));
	        $this->ajaxReturn(V(0, '取消成功'));
        }
        $this->ajaxReturn(V(1, '取消失败 '));
    }
}