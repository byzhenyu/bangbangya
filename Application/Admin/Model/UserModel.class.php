<?php
/**
 *会员用户属性模型类
 */
namespace Admin\Model;
use Think\Model;
use Common\Tools\Emchat;
class UserModel extends Model{
    protected $insertFields = array('user_name','password','mobile','head_pic','nick_name', 'task_money', 'bonus_money', 'total_money', 'alipay_num', 'alipay_name', 'invitation_code', 'invitation_uid', 'open_id', 'disabled', 'register_time','status');
    protected $selectFields = array('user_id','user_name','password','mobile','head_pic','nick_name', 'task_money', 'bonus_money', 'total_money', 'alipay_num', 'alipay_name', 'invitation_code', 'invitation_uid', 'open_id', 'disabled', 'register_time','status');
    protected $findFileds = array('user_id','user_name','password','mobile','head_pic','nick_name', 'task_money', 'bonus_money', 'total_money', 'alipay_num', 'alipay_name', 'invitation_code', 'invitation_uid', 'open_id', 'disabled', 'register_time','status');
    protected $_validate = array(
        array('valid_info', 'require', '验证信息不能为空！', 1, 'regex', 3),
        array('mobile','isMobile','不是有效的手机号码',1,'function', 3),
        array('valid_time', 'require', '任务有效期不能为空', 1, 'regex', 1),
    	array('password', '6,20', '密码长度有误', 1, 'length', 1),
    	array('password', '6,20', '密码长度有误', 2, 'length', 12),

    );

    /**
     * @desc 获取会员列表
     * @param $where array 检索条件
     * @param $field string 展示字段
     * @param $sort string 排序顺序
     * @return mixed
     */
    public function getUsersListByPage($where, $field = false, $sort = 'register_time desc'){

        if(is_null($field)){
            $field = $this->selectFields;
        }
        $where['status'] = array('eq', 1);

        $count = $this->where($where)->count();
        $page = get_page($count, 15);
        $userslist = $this->field($field)->where($where)->limit($page['limit'])->order($sort)->select();
        return array(
            'userslist'=>$userslist,
            'page'=>$page['page']
        );


    }
    public function getUsersList($where, $field = false, $sort = 'u.register_time desc'){
        if(is_null($field)){
            $field = $this->selectFields;
        }
        $where['u.status'] = array('eq', 1);
        $count = $this->alias('u')->join('__TEACHER__ as t on u.teacher_id = t.id', 'LEFT')->where($where)->count();
        $page = get_page($count);
        $userslist = $this->alias('u')->join('__TEACHER__ as t on u.teacher_id = t.id', 'LEFT')->field($field)->where($where)->limit($page['limit'])->order($sort)->select();
        return array(
            'userslist'=>$userslist,
            'page'=>$page['page']
        );
    }


    /**
     * 修改用户启用禁用状态
     * @param $shop_id
     * @param $is_admin
     * @return array
     */
    public function changeDisabled($user_id) {

        $userInfo = $this->where(array('user_id'=>$user_id))->field('disabled, user_id')->find();
        $dataInfo = $userInfo['disabled'] == 1 ? 0 : 1;
        $update_info = $this->where(array('user_id'=>$user_id))->setField('disabled', $dataInfo);
        if($update_info !== false){
            //改变用户token
            $this->changeToken($userInfo['user_id']);
            return V(1, '操作成功');
        } else {
            return V(0, '操作成功');
        }
    }

    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){
        $data['password'] = pwdHash($data['password']);
        $data['register_time'] = NOW_TIME;
        if(!$data['user_name']) $data['user_name'] = $data['mobile'];
    }

    //会员添加后生成token
    protected function _after_insert($data, $option){
        $user_id = $this->getLastInsID();
        $where['user_id'] = $user_id;
        $info = $this->where($where)->find();
        $token = randNumber(18); // 18位纯数字
        $user_name = $info['user_name'];
        $data['user_id'] = $user_id;
        $data['user_name'] = $user_name;
        $data['token'] = $token;
        M('user_token')->add($data);
    }


    //修改操作前的钩子操作
    protected function _before_update(&$data, $option){
        // 判断密码为空就不修改这个字段
        if(empty($data['password']))
            unset($data['password']);
        else 
            $data['password'] = pwdHash($data['password']);
    }

    //修改操作前的钩子操作
    protected function _after_update(&$data, $option){
        $user_id = I('user_id', 0);
        if ($data['disabled'] == 0) {
            $this->changeToken($user_id);
        }
    }

    /**
     * 查询用户信息
     * @param $where
     * @param null $fields
     * @return mixed
     */
    public function getUserInfo($where=[], $fields = null){
        if(is_null($fields)){
            $fields = $this->selectFields;
        }
        $userInfo = $this->field($fields)->where($where)->find();
        return $userInfo;
    }
    /**
     * 查询用户信息
     * @param $user_id
     *@return mixed
     * create by wangwujiang 2018/3/22
     */
    public function getUserName($user_id){
        $userInfo = $this->where('user_id ='.$user_id)->getField('user_name');
        return $userInfo;
    }
    //禁用账号改变会员token下线
    public function changeToken($user_id) {
        $token = randNumber(18);//18位纯数字
        $where['user_id'] = $user_id;
        $data['token'] = $token;
        M('user_token')->where($where)->data($data)->save();
        return $token;
    }
    /**
     * 更新用户信息
     *@param $user_id
     *@param  $[data] [<更新数据>]
     *@return bool
     */
    public function updateUserInfo($user_id = '',$data = []){
        if($user_id == '') return false;
        $result = $this->where(' user_id = '.$user_id) ->save($data);
        return $result;
    }

}