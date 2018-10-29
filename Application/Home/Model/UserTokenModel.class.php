<?php
/**
 * Created by liuniukeji.com
 * 用户token
 * @author songgy <1661745274@qq.com>
*/
namespace Home\Model;
use Think\Model;
class UserTokenModel extends Model
{
    protected $insertFields = array('token_id','user_id', 'user_name', 'token', 'login_time');
    protected $updateFields = array('id', 'token', 'login_time', 'client_type');

    protected function _before_insert(&$data, $option){
        $data['login_time'] = NOW_TIME;
    }

    protected function _before_update(&$data, $option){
        $data['login_time'] = NOW_TIME;
    }

    //根据token获取uid
    public function getUID($token) {
    	$where['token'] = $token;
    	$userId = $this->where($where)->getField('user_id');
        return $userId;
    }
}