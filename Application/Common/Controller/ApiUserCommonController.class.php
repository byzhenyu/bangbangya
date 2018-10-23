<?php
/**
 * 用户登录后, 需要继承的基类
 * create by zhaojiping <QQ: 17620286>
 */
namespace Common\Controller;
use Common\Controller\ApiCommonController;
class ApiUserCommonController extends ApiCommonController {
    
    public function __construct(){
        parent::__construct();
        $token = I('token', 0, 'trim');
        $token = '200325684298602553';
        // 判断token值是否正确并返回用户信息
        $uid = $this->checkTokenAndGetUid($token);
        if ($uid > 0) {
            define('UID', $uid);
            //根据uid获取教师id
            $where['user_id'] = UID;
            $teacher_id = M('teacher')->where($where)->getField('id');
            define('TEACHER_ID', $teacher_id);
            unset($where);
        } else {
            $this->apiReturn(V(0, '登录信息失效，请重新登录'));
        }
        
    }

    protected function checkTokenAndGetUid($token){
        $where['token'] = $token;
        $id = M('UserToken')->where($where)->getField('user_id');
        return $id ? $id : 0;
    }
}
