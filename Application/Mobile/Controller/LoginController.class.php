<?php
/**
 * @Description    登录注册控制器
 * @Author         <809888972@qq.com>
 * @Date           2018/10/18
 */
namespace Mobile\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->get_global_config();
    }
    /**
     * 登录页
     **/
    public function login(){
        if(is_login()) {
            $this->redirect('Mobile/Index/index');
        }
        else{
            $this->display();
        }
    }

    /**
     * 登录
     * @param open_id    微信授权码
     **/
    public function dologin(){
        $UserModel = D('Home/User');
        $data = I('post.');
        // p(I('post.'));
        // die;
        if($UserModel->validate($UserModel->_login_validate)->create($data)){
            $userInfo = $UserModel->doLogin($data['open_id']);
            if(is_array($userInfo)){

                $this->ajaxReturn(V(1, '登录成功,正在跳转！',$userInfo));
            }
        }
        $this->ajaxReturn(V(0, $UserModel->getError()));
    }
    /**
     * 注册页
     **/
    public function register(){
        if(is_login()) {
            $this->redirect('Mobile/Index/index');
        }
        else{
            $this->display();
        }
    }

    /**
     * 注册
     * @param mobile       手机号
     * @param sms_code     短信验证码
     * @param password     密码
     * @param re_password    确认密码
     **/
    /**
     * @desc 注册接口
     */
    public function doregister()
    {
        $mobile = I('mobile', '');
        $sms_code = I('sms_code', '');
        $password = I('password', '', 'trim');
        $re_password = I('re_password', '', 'trim');
        $userModel = D('Admin/User');
        if (!isMobile($mobile)) $this->ajaxReturn(V(0, '请填写正确的手机格式！'));
        if($password != $re_password) $this->ajaxReturn(V(0, '两次密码输入不一致！'));
        $valid = D('Home/SmsMessage')->checkSmsMessage($sms_code, $mobile, 1);
        if (!$valid['status']) $this->ajaxReturn($valid);
        $data = I('post.');
        $data['user_name'] = $mobile;
        if ($userModel->create($data, 1) !== false) {
            $user_id = $userModel->add();
            if ($user_id > 0) {
                $this->ajaxReturn(V(1, '注册成功，请登录'));
            }
            else{
                $this->ajaxReturn(V(0, $userModel->getError()));
            }
        }
        else{
            $this->ajaxReturn(V(0, $userModel->getError()));
        }
    }

    /*
     * 找回密码
     * */
    public function forgetPassword(){

        $this->display();

    }
    /**
     * 找回密码
     * @param mobile       手机号
     * @param sms_code     短信验证码
     * @param password     密码
     **/
    public function findpwd() {
        $mobile = I('mobile', '');
        $sms_code = I('sms_code', '');
        $password = I('password', '', 'trim');
        $re_password = I('re_password', '', 'trim');
        if (isMobile($mobile) != true) {
            $this->ajaxReturn(V(0, '请输入有效的手机号码'));
        }
        if($password != $re_password) {
            $this->ajaxReturn(V(0, '两次密码输入不一致！'));
        }
        $check_mobile = D('Home/User')->checkUserExist($mobile);
        if ($check_mobile == false) {
            $this->ajaxReturn(V(0, '手机号码不存在'));
        }
        $check_sms = D('Home/SmsMessage')->checkSmsMessage($sms_code, $mobile, 2);
        if ($check_sms['status'] == 0) {
            $this->ajaxReturn($check_sms);
        }
        if (strlen($password) < 6 || strlen($password) > 15){
            $this->ajaxReturn(V(0, '密码必须是6-20位的字符'));
        }
        $userModel = D('Home/User');
        $userModel->change_pwd($mobile, $password);
        $this->ajaxReturn(V(1, '密码修改成功'));
    }

    /**
     * @desc 微信登录
     */
    public function thirdLogin() {
        $thirdType = 'wx';
        $open_id = I('open_id');
        if($thirdType && !in_array($thirdType, array('wx'))) $this->ajaxReturn(V(0, '第三方登录类型有误'));
        if('wx' == $thirdType){
            $where['weixin'] = $map['weixin'] = $open_id;
        }
        if(!$thirdType) $where['weixin'] = $map['weixin'] = $open_id;
        $map['head_pic'] = I('head_pic', '');
        $map['nickname'] = I('nickname', '');
        if (!$open_id){
            $this->ajaxReturn(V(0, '参数有误'));
        }

        $memberModel = M('User');
        $findFields = 'user_id,user_name,password,mobile,head_pic,points,nickname,sex,user_money,frozen_money,disabled,register_time,suffix_id,follow_num,fans_num';
        $user = $memberModel->where($where)->field($findFields)->find();
        if (!$user) {
            $map['user_name'] = $map['nickname'];
            $map['register_time'] = time();
            $map['last_login_time'] = time();
            $map['last_login_ip'] = get_client_ip();
            $row_id = $memberModel->add($map);
            if ($row_id) {
                $token = randNumber(18);
                M('UserToken')->add(array('user_id' => $row_id, 'token' => $token, 'login_time' => time()));
                $user = $memberModel->where($where)->field($findFields)->find();
                $user['nickname'] = $user['nickname'] !='' ? $user['nickname'] : $user['mobile'];
                $user['token'] = $token;
                $user['register_time'] = time_format($user['register_time'], 'Y-m-d');
                $this->ajaxReturn(V(1, '登录成功', $user));
            } else {
                $this->ajaxReturn(V(0, '登录失败'));
            }

        } else {
            $token = D('Home/User')->updateWeixinData($user);
            $user['token'] = $token;
            $user['register_time'] = time_format($user['register_time'], 'Y-m-d');
            $this->ajaxReturn(V(1, '登录成功', $user));
        }
    }
    /**
     * 退出登录
     **/
    public function logout(){
        session('user_auth',null);
        session('user_type',null);
        session('user_drive',null);
        $this->redirect('Mobile/Login/login');
    }

    /**
     * [get_global_config 获取配置]
     * @return [type] [description]
     */
    public function get_global_config(){
        /* 读取数据库中的配置 */
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $configParse = new \Common\Tools\ConfigParse();
            $config      =   $configParse->lists();
            S('DB_CONFIG_DATA',$config,60);
        }
        C($config); //添加配置
    }
}