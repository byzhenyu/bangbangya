<?php
/**
 * Created by liuniukeji.com
 * 公用接口
 * @author
*/
namespace Api\Controller;
use Common\Controller\ApiCommonController;
use Think\Verify;

class PublicApiController extends ApiCommonController {

    /**
     * 登录接口
     */
    public function login() {
        $user_name = I('post.username', '');
        $password = I('post.password', '');
        $user_type = I('type', 1, 'intval');
        if(!in_array($user_type, array(1, 2))) $this->apiReturn(V(0, '类型有误！'));
        $user_type--;
        $loginInfo = D('Home/User')->doLogin($user_name, $password, $user_type);
        if( $loginInfo['status'] == 1 ){
            $loginInfo['data']['register_time'] = time_format($loginInfo['data']['register_time'], 'Y-m-d');
            $loginInfo['data']['head_pic'] = strval($loginInfo['data']['head_pic']);
            $loginInfo['data']['expire_time'] = time_format($loginInfo['data']['expire_time'], 'Y/m/d');
            unset($loginInfo['data']['password']);

            $this->apiReturn($loginInfo);
        } else {
            $this->apiReturn(V(0, $loginInfo['info']));
        }
    }

    /**
     * 获取短信接口
     * user_type 0学生端 1教师端
     * type 2找回密码 3修改密码 4绑定手机
     */
    public function smsCode() {
        $mobile = I('mobile', '');
        $user_type = I('user_type', 0, 'intval');
        $type = I('type', 0, 'intval');
        //2找回密码 3修改密码 4绑定手机
        $type_array = array(2, 3, 4);
        if (!in_array($type, $type_array)) {
            $this->apiReturn(V(0, '参数错误'));
        }
        $user_type_array = array(0, 1);
        if (!in_array($user_type, $user_type_array)) {
            $this->apiReturn(V(0, '短信类型参数错误'));
        }
        if (empty($mobile) || !isMobile($mobile)) {
            $this->apiReturn(V(0, '请输入有效的手机号码'));
            exit;
        }
        //验证手机号码是否已经验证
        $info['mobile'] = $mobile;
        $info['user_type'] = $user_type;
        $result = D('Home/User')->checkUserExist($info);

        if ($result == false && $type == 4) {
            $this->apiReturn(V(0, '手机号码已存在'));
        } elseif ($result == true && in_array($type, array(2, 3))) {
            $this->apiReturn(V(0, '手机号码不存在'));
        } 
        // 短信内容
        $sms_code = randCode(C('SMS_CODE_LEN'), 1);

        switch ($type) {
            case 2: //找回密码
                $msg = '找回密码验证码';
                $sms_content = C('SMS_FINDPWD_MSG') . $sms_code;
                break;
            case 3: //修改密码
                $msg = '修改密码验证码';
                $sms_content = C('SMS_MODPWD_MSG') . $sms_code;
                break;
            case 4: //绑定手机号码
                $msg = '绑定手机号验证码';
                $sms_content = C('SMS_MODMOBILE_MSG') . $sms_code;
                break;
            default:
                break;
        }        

        $send_result = sendMessageRequest($mobile, $sms_content);
        // 保存短信信息
        $data['sms_content'] = $sms_content;
        $data['sms_code'] = $sms_code;
        $data['mobile'] = $mobile;
        $data['type'] = $msg;
        $data['send_status'] = $send_result['status'];
        $data['send_response_msg'] = $send_result['info'];
        $data['user_type'] = $user_type;
        D('Home/SmsMessage')->addSmsMessage($data);

        if ($send_result['status'] == 1) {
            $this->apiReturn(V(1, '发送成功'));
        } else {
            $this->apiReturn(V(0, '发送失败:'. $send_result['info']));
        }
    }

    /**
     * 找回密码
     */
    public function findpwdSave() {
        $mobile = I('mobile', '');
        $password = I('password', '');
        $user_type = I('user_type', 0);//用户类型
        $sms_code = I('sms_code', '');
        if (isMobile($mobile) != true) {
            $this->apiReturn(V(0, '请输入有效的手机号码'));
        }
        $check_mobile = D('Home/User')->checkUserExist($mobile);
        if ($check_mobile == false) { // 不存在
            $this->apiReturn(V(0, '手机号码不存在'));
        }
        $check_sms = D('Home/SmsMessage')->checkSmsMessage($sms_code, $mobile, $user_type);
        if ($check_sms['status'] == 0) {
            $this->apiReturn($check_sms);
        }
        if (strlen($password) < 6 || strlen($password) > 15){
            $this->apiReturn(V(0, '密码必须是6-20位的字符'));
        }
        $userModel = D('Home/User');
        $userModel->change_pwd($mobile, $password,$user_type);
        $this->apiReturn(V(1, '密码修改成功'));
    }

    /**
     * @desc 获取启动页图片
     */
    public function startAppAd(){
        $where = array('position' => 1);
        $model = D('Admin/Ad');
        $data = $model->getAdField($where, 'content');
        if($data){
            $this->apiReturn(V(1, '启动页内容获取成功', $data));
        }
        else{
            $this->apiReturn(V(0, '获取失败'));
        }
    }

    /**
     * @desc 获取版本更新信息
     */
    public function getVersionInfo(){
        $type = I('post.type', 1, 'intval');
        $type = 1; //TODO 默认1
        $where = array('version_type' => $type);
        $model = D('Admin/Version');
        $info = $model->getVersionInfo($where);
        if(is_array($info)){
            $order=array("\r\n","\n","\r");
            $replace='<br/>';
            $info['version_desc'] = str_replace($order,$replace,$info['version_desc']);
            $info['add_time'] = time_format($info['add_time']);
            $this->apiReturn(V(1, '版本更新信息获取成功！', $info));
        }
        else{
            $this->apiReturn(V(0, '没有可更新版本！'));
        }
    }

    /**
     * 获取图形验证码
     */
    public function getChkCode(){
        $Verify = new \Think\Verify(array(
            'length' => 4,
            'useNoise' => FALSE,
            'imageH' =>40,
            'imageW' => 100,
            'fontSize'=>14,
            'useCurve'=>false
        ));
        $Verify->entry(1);
       
    }
}
