<?php
/**
 * Created by PhpStorm.
 * User: jipingzhao liuniukeji.com
 * Date: 6/30/17
 * Time: 2:53 PM
 */
namespace Home\Model;
use Think\Model;
use Common\Tools\Emchat;
class UserModel extends Model
{
    protected $findFields = array('user_id', 'user_name', 'password','mobile','head_pic','nick_name','task_money','frozen_money','bonus_money','total_money','alipay_num','alipay_name','invitation_code','invitation_uid','disabled','register_time','open_id','status');
    //登录时表单验证的规则
    public $_login_validate = array(
        array('open_id', 'require', '微信未授权登录!',1,'regex'),
    );

    // 用户登录
    public function doLogin($open_id='', $field=null){
        if ($open_id == '') return V(0, '参数错误');
        if ($field == null) $field = $this->findFields;

        $map['status'] = 1; // 用户未被逻辑删除
        /* 获取用户数据 */
        $user = $this->field($field)->where($map)->where('open_id = ' .$open_id)->find();
        // p($User);
        // die;
        if(is_array($user)){
                if ($user['disabled'] != 1) {
                    return V(0, '用户账号已经被禁用');
                }
                unset($user['password']);
                $user['token'] = $this->_createTokenAndSave($user); //生成登录token并保存
                
                return V(2, '登录成功', $user);
        } else {
            return V(0, '用户名或密码错误.');
        }

    }
    /**
     * 生成token值, 并保存到数据库
     * @param array userInfo 用户信息
     * @return string token值
     */
    protected function _createTokenAndSave($userInfo){
        $token = randNumber(18); // 18位纯数字
        $where['user_id'] = $userInfo['user_id'];
        $token = D('Home/UserToken')->where($where)->getField('token');
        return $token;
        //上线前需用下面代码
        /*$data['token'] = $token;
        D('Home/UserToken')->where($where)->data($data)->save();
        return $token;*/
    }
    /**
     * 保存头像
     * @param string $head_pic 用户头像
     */
    public function saveHeaderPic($head_pic) {
        if ($head_pic == '') {
            return V(0, '请上传头像');
        }
        $where = array();
        $where['user_id'] = UID;
        $data['head_pic'] = $head_pic;
        $updateInfo = $this->where($where)->save($data);
        if ($updateInfo !== false) {
            return V(1, '保存成功');
        } else {
            return V(0, '保存失败');
        }
    }
}