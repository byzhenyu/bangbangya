<?php
/**
 * Created by PhpStorm.
 * User: jipingzhao liuniukeji.com
 * Date: 6/30/17
 * Time: 2:53 PM
 */
namespace Agent\Model;
use Think\Model;
use Common\Tools\Emchat;
class AgentModel extends Model
{
    protected $insertFields = array('*');
    protected $updateFields = array('contacts', 'password', 'mobile', 'account_number');
    protected $findFields = array('*');

    // 代理商登录
    public function agentLogin($username='', $password='', $field=null){
        if ($username == '' ||  $password == '') exit('参数错误!');
        if ($field == null) $field = $this->findFields;

        $verify = I('chkcode');
        if(!check_verify($verify)){
            return V(0, '验证码输入错误!');
        }
        $map['status'] = 1;
        /* 获取用户数据 */
        $where['agent_name|mobile']= array('eq', $username);
        $user = $this->field($field)->where($map)->where($where)->find();

        if(is_array($user)){

            /* 验证用户密码 */
            if(pwdHash($password, $user['password'], true)){
                if ($user['disabled'] != 1) {
                    return V(0, '代理商账号已经被禁用');
                }
                $this->updateLogin($user['id']); //更新用户登录信息
                return V(1, '登录成功', $user);
            } else {
                return V(0, '用户名或密码错误!');
            }
        } else {
            return V(0, '用户名或密码错误.');
        }
    }

    /**
     * 更新用户登录信息
     * @param  integer $uid 用户ID
     */
    protected function updateLogin($uid){
        $data = array(
            'id'              => $uid,
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(1),
        );
        $this->save($data);
    }

    /**
     * 返回代理商id
     * @param  string $address
     */
    public function getAgentId($address){
        $area = explode('-', $address);
        $where['province'] = $area[0];
        $where['city'] = $area[1];
        $where['district'] = $area[2];
        $where['status'] = 0;
        $where['disabled'] = 1;
        $agent_info = $this->where($where)->find();
        if(!$agent_info) {
            return V(0, '该地区下暂无代理商');
        } else {
            return $agent_info['id'];
        }
    }

    /**
     * 改变余额
     * @param string money 改变金额
     * @param int $type 1增加余额 2减少余额
     */
    public function changeAgentMoney($agent_id, $money, $type = 0) {
        if ($type == 0) return V(0, '改变余额参数错误');
        if ($money < 0) $money = 0;
        $where['id'] = $agent_id;
        if ($type == 1) {
            $result = $this->where($where)->setInc('account_amout', $money);
        } elseif ($type == 2) {
            $result = $this->where($where)->setDec('account_amout', $money);
        }
        
        return $result;
    }

}