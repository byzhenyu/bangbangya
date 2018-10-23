<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description    代理商管理
 * @Author         (jicy QQ:510434563)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/08/28
 * @CreateBy       ${PRODUCT_NAME}
 */

namespace Admin\Model;


use Think\Model;

class AgentModel extends Model
{
    protected $insertFields = array( 'agent_name', 'password', 'contacts', 'mobile','province','city','district', 'address','add_time','last_login_time','last_login_ip','disabled','status');
    protected $updateFields = array( 'agent_name', 'password', 'contacts', 'mobile','province','city','district', 'address', 'add_time','last_login_time','last_login_ip','disabled','status', 'id');
    protected $selectFields = array('id', 'agent_name', 'password', 'contacts', 'mobile','province','city','district', 'address','add_time','last_login_time','last_login_ip','disabled','status');
    protected $_validate = array(
        array('agent_name', 'require', '代理商不能为空', 1, 'regex', 3),
        array('agent_name', 'checkNameExist', '代理商名称重复', self::MUST_VALIDATE, 'callback', 3),
        array('contacts', 'require', '联系人不能为空', 1, 'regex', 3),
        array('mobile', 'isMobile', '手机号不合法', 1, 'function', 3),
        array('province', 'require', '省不能为空', 1, 'regex', 3),
        array('city', 'require', '市不能为空', 1, 'regex', 3),
        array('district', 'require', '县/区不能为空', 1, 'regex', 3),
        array('address', '0, 100', '详细地址不能超过100字！', 1, 'length', 3),
        );

    protected function checkNameExist($data) {
        time_format();
        $id = I('id', 0, 'intval');
        $where['agent_name'] = array('eq', $data);
        if ($id > 0) {
            $where['id'] = array('neq',$id);
        }
        $count = $this->where($where)->count();
        if ($count > 0) {
            return false;
        } else {
            return true;
        }
    }

    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){
        $data['password'] = pwdHash($data['password']);
        $data['add_time'] = NOW_TIME;

    }

    //修改操作前的钩子操作
    protected function _before_update(&$data, $option){
        // 判断密码为空就不修改这个字段
        if(empty($data['password']))
            unset($data['password']);
        else
            $data['password'] = pwdHash($data['password']);
    }

    /**
     * 意见反馈分页数据
     * @param $where
     * @return array
     */
    public function getAgentByPage($where, $field = null, $order = 'id desc'){

        if(is_null($field)){
            $field = $this->selectFields;
        }

        $count = $this->where($where)->count();
        $page = get_page($count);

        $info = $this->field($field)->where($where)->limit($page['limit'])->order($order)->select();

        return array(
            'info' => $info,
            'page' => $page['page'],
        );
    }

    public function getAgentInfo($where, $field) {
        if(is_null($field)){
            $field = $this->selectFields;
        }
        $info = $this->where($where)->field($field)->find();
        return $info;
    }
}