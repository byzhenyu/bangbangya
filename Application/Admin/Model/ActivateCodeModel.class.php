<?php
/**
 * 激活码模型
 */
namespace Admin\Model;

use Think\Model;

class ActivateCodeModel extends Model {
    protected $insertFields = array();
    protected $updateFields = array();
    protected $_validate = array(
    );

    /**
     * @desc 激活码列表
     * @param $where
     * @param bool $field
     * @param string $order
     * @return array
     */
    public function listActivateCode($where, $field = false, $order = ''){
        if(!$field) $field = 'a.*,g.agent_name as create_name,u.user_name';
        $number = $this->alias('a')->join('__AGENT__ as g on a.agent_id = g.id', 'LEFT')->join('__USER__ as u on a.user_id = u.user_id', 'LEFT')->where($where)->count();
        $page = get_web_page($number);
        $list = $this->alias('a')->join('__AGENT__ as g on a.agent_id = g.id', 'LEFT')->join('__USER__ as u on a.user_id = u.user_id', 'LEFT')->where($where)->field($field)->limit($page['limit'])->order($order)->select();
        foreach($list as &$val){
            if(!$val['create_name']) $val['create_name'] = '<span style="color: red;">总后台</span>';
            if(!$val['use_time']){
                $val['use_time'] = $val['user_name'] = '未使用';
            }
            else{
                $val['use_time'] = time_format($val['use_time']);
            }
        }
        return array(
            'info' => $list,
            'page' => $page['page']
        );
    }

    /**
     * @desc 改变激活码状态
     * @param $code_id
     * @return bool
     */
    public function changeDisabled($code_id){
        $res = $this->find($code_id);
        $status = !$res['disabled'];
        $result = $this->where(array('id' => $code_id))->save(array('disabled' => $status));
        return $result;
    }
}