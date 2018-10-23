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
    protected $findFields = array('user_id', 'user_name', 'mobile', 'password', 'email', 'head_pic', 'disabled', 'nickname','truename','school_name','grade','register_time', 'period_id', 'parent_teacher_id', 'master_words_num','status');


    // 判断用户是否存在
    public function checkUserExist($info){
        $where['mobile'] = $info['mobile'];
        $where['status'] = 1;
//        $where['user_type'] =$info['user_type'];
        $count = $this->where($where)->count();
        if ($count > 0) {
            return false;
        } else {
            return true;
        }
    }

    
    /**
     * 获取会员列表或获取指定查询的会员信息
     * @param  String $mobile [查询时所使用的手机号]
     * @return array
     */
    public function getUsersListByPage($where, $field = null, $sort = 'register_time,user_id desc'){
        if(is_null($field)){
            $field = $this->findFields;
        }
        $where['status'] = 1;
        $where['disabled'] = 1;
        $count = $this->where($where)->count();
        $page = get_web_page($count);
        $list = $this->field($field)->where($where)->limit($page['limit'])->order($sort)->select();
        return array(
            'list'=>$list,
            'page'=>$page['page']
        );
    }

    /**
     * 根据指定条件，获取用户个人信息
     * @param  string $field 字段名
     */
    public function getUserInfo($where, $field = null){
        if ($field == null) {
            $field = $this->findFields;
        }
        $where['status'] = 1;
        $where['disabled'] = 1;
        $myInfo = $this->field($field)->where($where)->find();
        return $myInfo;
    }

    // 用户登录
    public function doLogin($username='', $password='', $user_type, $field=null){
        if ($username == '' ||  $password == '') return V(0, '参数错误');
        if ($field == null) $field = $this->findFields;

        $map['status'] = 1; // 用户未被逻辑删除
        $map['user_type'] = $user_type;
        /* 获取用户数据 */
        $user = $this->field($field)->where($map)->where('user_name="'. $username .'"')->find();
        if(is_array($user)){
            /* 验证用户密码 */
            if(pwdHash($password, $user['password'], true)){
                if ($user['disabled'] != 1) {
                    return V(0, '用户账号已经被禁用');
                }
                $this->_checkLearnSetting($user['user_id']);
                $user['token'] = $this->_createTokenAndSave($user); //生成登录token并保存

                return V(1, '登录成功', $user);
            } else {
                return V(0, '用户名或密码错误!');
            }
        } else {
            return V(0, '用户名或密码错误.');
        }

    }

    /**
     * 找回密码时修改密码
     * @param $mobile 手机号
     * @param $password 新密码
     * @return bool
     */
    public function change_pwd($mobile, $password,$user_type){
        $where['mobile'] = $mobile;
        $where['user_type'] = $user_type;
        $data['password'] = pwdHash($password);

        $this->where($where)->data($data)->save();
        return true;
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

    /**
     * 获取会员指定字段
     */
    public function getUserField($where, $field) {
        return $this->where($where)->getField($field);
    }

    /**
     * 生成token值, 并保存到数据库
     * @param array userInfo 用户信息
     * @return string token值
     */
    protected function _createTokenAndSave($userInfo){
        $userTokenModel = D('Home/UserToken');
        $token = randNumber(18); // 18位纯数字
        $where['user_id'] = $userInfo['user_id'];
        $data['token'] = $token;
        $count = $userTokenModel->where($where)->count();
        if ($count > 0) {
            $userTokenModel->where($where)->data($data)->save();
        } else {
            $data['user_id'] = $userInfo['user_id'];
            $data['user_name'] = $userInfo['user_name'];
            $userTokenModel->add($data);
        }
        
        return $token;
    }

    //获取学生学段
    public function getPeriod($user_id) {
        $where['user_id'] = $user_id;
        return $this->getUserField($where, 'period_id');
    }

    //获取学生学段课程
    public function getPeriodClass($user_id) {
        $period_id = $this->getPeriod($user_id);
        $where['period_id'] = $period_id;
        $class_list = M('course_class')->where($where)->getField('id, class_name', true);
        return $class_list;
    }

    //获取学生设置
    public function getLearnSetting() {
        $where['user_id'] = UID;
        return M('user_setting')->field('words_number, spell_rate, intelligent_number, true_time')->where($where)->find();
    }

    //验证学生设置是否存在，不存在插入
    private function _checkLearnSetting($user_id) {
         $where['user_id'] = $user_id;
         $info = M('user_setting')->where($where)->find();
         if (empty($info)) {
            $data['user_id'] = $user_id;
            M('user_setting')->add($data);
         }
    }



    /**
     * 根据指定条件，获取用户个人信息
     * @param $field 字段名
     * @return array
     */
    public function getUser($where, $field = null){
        if ($field == null) $field = $this->findFields;
        $myInfo = $this->field($field)->where($where)->find();
        return $myInfo;
    }
    /**
     * @desc 修改会员指定字段
     * @param $where
     * @param $data
     * @return array
     */
    public function setUser($where,$data){
        $save = $this->where($where)->save($data);
        return $save;
    }
    /**
     * 获取会员列表或获取指定查询的会员信息
     * @param  String $mobile [查询时所使用的手机号]
     * @return array
     */
    public function getUsersListBy($where, $field = null, $sort = 'user_id desc'){
        if(is_null($field)) $field = $this->findFields;
        $where['status'] = 1;
        $where['disabled'] = 1;
        $list = $this->field($field)->where($where)->order($sort)->select();
        return $list;
    }
    /**
     * 获取会员列表或获取指定查询的会员信息
     * @param  String $mobile [查询时所使用的手机号]
     * @return array
     */
    public function getUsersListranking($where, $field = null, $sort = 'user_id desc'){
        if(is_null($field)) $field = $this->findFields;
        $where['u.status'] = 1;
        $where['u.disabled'] = 1;
        $list = $this
            ->alias('u')
            ->join('ln_homework_count as h on u.user_id=h.user_id','left')
            ->field($field)
            ->where($where)
            ->order($sort)
            ->select();
        //echo $this->_sql();exit;
        return $list;
    }
    /**
     * 获取会员列表和教师列表
     * @return array
     */
    public function getUsersListTeacher($where, $field = null, $sort = 'u.user_id asc'){
        if(is_null($field)) $field = $this->findFields;
        $where['u.status'] = 1;
        $where['u.disabled'] = 1;
        $list = $this
            ->alias('u')
            ->join('ln_teacher as t on u.user_id=t.user_id')
            ->field($field)
            ->where($where)
            ->order($sort)
            ->select();
        return $list;
    }
}