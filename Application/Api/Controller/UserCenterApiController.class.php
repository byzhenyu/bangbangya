<?php
/**
 * Created by liuniukeji.com
 * 用户中心相关接口
*/
namespace Api\Controller;
use Common\Controller\ApiUserCommonController;
use Think\Verify;

class UserCenterApiController extends ApiUserCommonController{

    /**
     * 获取首页默认课程
     */
    public function getHomeDefaultCourse() {
        $period_id = D('Home/User')->getPeriod(UID);
        $where['period_id'] = $period_id;
        $class_info = D('Admin/CourseClass')->getCourseClassInfo($where, 'id, class_name', 'id asc');

        $this->apiReturn(V(1, '获取首页数据', $class_info));
    }

    /**
     * 编辑个人资料
     */
    public function editUserInfo(){
        $name = I('name', '', 'trim');
        $school_name = I('school_name', '', 'trim');
        $grade_name = I('grade_name', '', 'trim');
        $userModel = D('Home/User');
        if (!empty($_FILES['head_pic'])) {
            $img = app_upload_img('head_pic', '', 'User');
            if ($img === 0) {
                $this->apiReturn(V(0, '头像上传失败'));
            } else if ($img === -1){
                $this->apiReturn(V(0, '头像上传失败'));
            }
            $data['head_pic'] = $img;
        }
        if ($name) {
            $data['nickname'] = $data['truename'] = $name;
        }
        if ($school_name) {
            $data['school_name'] = $school_name;
        }
        if ($grade_name) {
            $data['grade'] = $grade_name;
        }
        $where['user_id'] = UID;
        $result = $userModel->where($where)->save($data);
        if(false === $result) {
            $this->apiReturn(V(0, '保存失败'));
        } else {
            $info = $userModel->where($where)->field('user_id,head_pic,truename,school_name,grade')->find();
            $this->apiReturn(V(1, '保存成功', $info));
        }
    }

    /**
     * @desc 教师端编辑个人资料
     * @param name 姓名
     */
    public function editTeacherInfo(){
        $name = I('name', '', 'trim');
        if ($name) {
            $data['nickname'] = $data['truename'] = $name;
        }
        if (!empty($_FILES['head_pic'])) {
            $img = app_upload_img('head_pic', '', 'User');
            if ($img === 0) {
                $this->apiReturn(V(0, '头像上传失败'));
            } else if ($img === -1){
                $this->apiReturn(V(0, '头像上传失败'));
            }
            $data['head_pic'] = $img;
        }
        $userModel = D('Home/User');
        $where['user_id'] = UID;
        $result = $userModel->where($where)->save($data);
        if(false === $result) {
            $this->apiReturn(V(0, '保存失败'));
        }
        else {
            $info = $userModel->where($where)->field('user_id,head_pic,truename,mobile')->find();
            $this->apiReturn(V(1, '保存成功', $info));
        }
    }

    /**
     * 获取服务中心
     */
    public function getServiceCenter(){
        $userModel = D('Home/User');
        $where['user_id'] = UID;
        $agent_id = $userModel->getUserField($where, 'agent_id');
        unset($where);
        $where['id'] = $agent_id;
        $info = D('Admin/Agent')->getAgentInfo($where, 'agent_name, mobile, province, city, district, address');
        $result['agent_name'] = $info['agent_name'];
        $result['mobile'] = $info['mobile'];
        $result['address'] = $info['province'].$info['city'].$info['district'].$info['address'];
        $this->apiReturn(V(1, '服务中心信息', $result));
    }

    /**
     * 绑定手机号
     */
    public function bindMobile(){
        $mobile = I('mobile');
        $code = I('sms_code');
 
        $user_model = D('Home/User');
        $where['user_id'] = UID;
        $exists = $user_model->checkUserExist(array('mobile' => $mobile));
        if(!$exists) {
            $this->apiReturn(V(0, '手机号已经被绑定过啦'));
        }
        $check = D('Home/SmsMessage')->checkSmsMessages($code, $mobile);
        if(!$check['status']) $this->apiReturn($check);
        $result = $user_model->where($where)->setField('mobile', $mobile);
        if(false !== $result){
            $this->apiReturn(V(1, '手机号绑定成功！', array('mobile' => $mobile)));
        }
        else{
            $this->apiReturn(V(0, '手机号绑定失败！'));
        }
    }

    /**
     * 意见反馈
     */
    public function feedBack() {
        $feedBackModel = D('Admin/FeedBack');
        if ($feedBackModel->create()) {
            if ($feedBackModel->add() !== false) {
                $this->apiReturn(V(1, '感谢您的反馈'));
            }
        } else {
            $this->apiReturn(V(0, $feedBackModel->getError()));
        }
    }

    /**
     * 修改密码
     */
    public function settingUserPwd(){
        $password = I('password');
        $newPassword = I('new_password');
        $rePassword = I('re_password');
        if(!$password || !$newPassword) $this->apiReturn(V(0, '请输入密码！'));
        $passLen = strlen($newPassword);
        if($passLen < 6 || $passLen > 18) $this->apiReturn(V(0, '密码长度支持6-18位！'));
        if($newPassword != $rePassword) $this->apiReturn(V(0, '两次新密码不一致！'));
        $userModel = D('Home/User');
        $where['user_id'] = UID;
        $userInfo = $userModel->getUserInfo($where, 'password');
        if(!pwdHash($password, $userInfo['password'], true)) $this->apiReturn(V(0, '原密码输入不正确！'));
        $data = $userModel->where($where)->setField('password', $newPassword);
        if(false !== $data){
            $this->apiReturn(V(1, '密码修改成功'));
        }
        else{
            $this->apiReturn(V(0, '密码修改失败'));
        }
    }
    /**
     * @desc 教师端修改密码
     * @param mobile   手机号码
     * @param sms_code 短信验证码
     * @param password 新密码
     */
    public function settingTeacherPwd(){
        $mobile = I('mobile', '');
        $password = I('password');
        $sms_code = I('sms_code', '');
        if (isMobile($mobile) != true) $this->apiReturn(V(0, '请输入有效的手机号码'));
        $UserModel = D('Home/User');
        $where['user_id'] = UID;
        $usermobile = $UserModel->getUserField($where, 'mobile');
        if($usermobile != $mobile) $this->apiReturn(V(0, '该手机号不是绑定关系！'));
        if(!$password) $this->apiReturn(V(0, '请输入密码！'));
        $passLen = strlen($password);
        if($passLen < 6 || $passLen > 18) $this->apiReturn(V(0, '密码长度支持6-18位！'));
        $check_sms = D('Home/SmsMessage')->checkSmsMessage($sms_code, $mobile,1);
        if ($check_sms['status'] == 0) {
            $this->apiReturn($check_sms);
        }

        $where1['mobile'] = $mobile;
        $save = $UserModel->where($where1)->setField('password', pwdHash($password));
        if(false !== $save){
            $this->apiReturn(V(1, '密码修改成功'));
        }
        else{
            $this->apiReturn(V(0, '密码修改失败'));
        }
    }

    /**
     * @desc 用户学习设置信息
     */
    public function userStudySetting(){
        $data = I('post.');
        $data['user_id'] = UID;
        $model = D('Admin/UserSetting');
        if(false !== $model->create($data)) {
            $where = array('user_id' => UID);
            $setting = $model->getSettingInfo($where);
            if($setting){
                $result = $model->where($where)->save($data);
            }
            else{
                $result = $model->add($data);
            }
            if (false !== $result) {
                $this->apiReturn(V(1, '设置成功！', $data));
            }else{
                $this->apiReturn(V(0, $model->getError()));
            }
        }
        else{
            $this->apiReturn(V(0, $model->getError()));
        }
    }

    /**
     * @desc 获取用户学习设置信息
     */
    public function getUserStudySetting(){
        $where = array('user_id' => UID);
        $model = D('Admin/UserSetting');
        $info = $model->getSettingInfo($where);
        if($info){
            $this->apiReturn(V(1, '设置信息获取成功！', $info));
        }
        else{
            $this->apiReturn(V(0, '学习信息尚未设置！'));
        }
    }

    /**
     * 学情报告
     */
    public function getUserTestResult() {
        $data = D('Home/UserTestResult')->getResult();
        $this->apiReturn(V(1, '学情报告',$data));
    }

    /**
     * 激活课程
     */
    public function activateCourse() {
        $code = I('activate_code', '');
        if (empty($code)) $this->ajaxReturn(V(0, '请输入激活码'));
        $activateCodeModel = D('Home/ActivateCode');
        $codeInfo = $activateCodeModel->where(['code' => $code])->find();
        if (empty($codeInfo)) $this->ajaxReturn(V(0, '该激活码不存在'));
        if (!$codeInfo['disabled']) $this->ajaxReturn(V(0, '该激活码已被禁用'));
        if ($codeInfo['state'] == 1) $this->ajaxReturn(V(0, '该激活码已使用过'));
        $new_time = $codeInfo['type'] * 24 *3600;
        $memberModel = D('Home/User');
        $where['user_id'] = UID;
        $info = $memberModel->getUser($where,'agent_id,used_number,expire_time,teacher_id,first_leader_id,second_leader_id');
        if ($info['expire_time'] < NOW_TIME) {
            $info['expire_time'] = NOW_TIME + $new_time;
        }
        else {
            $info['expire_time'] += $new_time;
        }
        //查询代理商
        $set = M('Agent')->where(array('id'=>$info['agent_id']))->getField('distribution_set');
        if (empty($set)) $this->ajaxReturn(V(0, '未获取到代理商信息！'));
        $sets = explode('|',$set);
        //修改激活码状态
        $saveInfo['state'] = 1;
        $saveInfo['use_time'] = NOW_TIME;
        $saveInfo['user_id'] = UID;
        $saveInfo['id'] = $codeInfo['id'];
        M()->startTrans();
        if ($activateCodeModel->save($saveInfo) === false) {
            M()->rollback();
            $this->ajaxReturn(V(0, '操作失败'));
        }
        //修改用户信息
        $used_number = $info['used_number'] +1;
        $arr['user_id'] = UID;
        $arr['expire_time'] = $info['expire_time'];
        $arr['used_time'] = NOW_TIME;
        $arr['is_used'] = 1;
        $arr['used_number'] = $used_number;
        if ($memberModel->save($arr) === false) {
            M()->rollback();
            $this->ajaxReturn(V(0, '操作失败'));
        }
        //分销学习币
        $TeacherModel = D('Home/Teacher');
        $DistributionLog = D('Home/DistributionLog');
        $data['teacher_id'] = $info['teacher_id'];
        $data['add_time'] = NOW_TIME;
        if($info['first_leader_id'] != 0){
            $where2['id'] = $info['first_leader_id'];
            $TeacherModel->where($where2)->setInc('points',$sets[0]);
            $data['to_teacher_id'] = $info['first_leader_id'];
            $data['desc'] = '分销给'.$sets[0].'个学习币';
            $DistributionLog->add($data);
        }
        if($info['second_leader_id'] != 0){
            $where2['id'] = $info['second_leader_id'];
            $TeacherModel->where($where2)->setInc('points',$sets[1]);
            $data['to_teacher_id'] = $info['second_leader_id'];
            $data['desc'] = '分销给'.$sets[1].'个学习币';
            $DistributionLog->add($data);
        }

        M()->commit();
        $this->ajaxReturn(V(1, '操作成功', $this->_getUserInfo()));
    }

    private function _getUserInfo() {
        $memberModel = D('Home/User');
        $where['user_id'] = UID;
        $info = $memberModel->getUserField($where, 'expire_time');
        return time_format($info, 'Y-m-d');
    }
}