<?php
/**
 * Created by PhpStorm.
 * User: dulong
 * Date: 2017/6/26
 * Time: 10:18
 * 用户反馈控制器
 */

namespace Admin\Controller;


class FeedBackController extends CommonController
{
    /**
     * 用户反馈信息列表
     */
    public function listFeedBack(){
        $mobile = I('mobile', '', 'trim');
        $where = array();
        if($mobile){
            $where['u.nick_name|f.comment'] = array('like', '%'.$mobile.'%');
        }
        $field = 'f.*, u.nick_name';
        $feedBackInfo = D('Admin/FeedBack')->getFeedBackByPage($where, $field);
        $this->info = $feedBackInfo['info'];
        $this->page = $feedBackInfo['page'];
        $this->mobile = $mobile;
        $this->display();
    }

    /**
     * 查看反馈信息详情
     */
    public function feedBackInfo(){
        $id = I('id', 0, 'intval');
        if(!$id){
            $this->error('参数有误！', U('FeedBack/listFeedBack'));
        }
        $feedBackInfo = D('Admin/FeedBack')->where(array('id'=>$id))->find();
        $feedBackInfo['nick_name'] = D('User')->getUserName($feedBackInfo['user_id']);
        $this->info = $feedBackInfo;
        $this->display();
    }

    // 删除方法
    public function del(){
        $this->_del('feed_back', 'id');  //调用父类的方法
    }
}