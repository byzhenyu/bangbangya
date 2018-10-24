<?php
/**
 *用户提现模型类
 */
namespace Admin\Model;
use Think\Model;
use Common\Tools\Emchat;
class UserAccountModel extends Model{
    protected $selectFields = array('id,user_id,admin_user,money,add_time,admin_note,
        user_note,payment,brank_no,brank_name,brank_user_name');

    protected $_validate = array(
        array('admin_note', 'require', '管理员备注不能为空！', 1, 'regex', 3),
        array('admin_note', '1,30', '管理员备注的值最长不能超过 30 个字符！', 1, 'length', 3),
    );

    /**
     * 获取用户提现列表
     * @return array
     */
    public function getUserAccountList($where=[], $field = null,$sort='add_time desc'){
        $count = $this
            ->alias('ua')
            ->join('ln_user u on ua.user_id = u.user_id')
            ->where($where)
            ->count();
        $usersData = get_page($count);
        $list = $this
            ->alias('ua')
            ->join('ln_user u on ua.user_id = u.user_id')
            ->where($where)
            ->field($field)
            ->limit($usersData['limit'])
            ->order($sort)
            ->select();
        return array(
            'list'=>$list,
            'page'=>$usersData['page']
        );
    }

    /**
     * 用户提现详情
     * @param $where
     * @param null $fields
     * @return array
     **/
    public function getAccountInfo($where, $field = null){
        $userInfo = $this
            ->alias('ua')
            ->join('ln_user u on ua.user_id = u.user_id')
            ->where($where)
            ->field($field)
            ->find();
        return $userInfo;
    }

    /**
     * 详情
     * @param $where
     * @param null $fields
     * @return mixed
     */
    public function getUserAccountDetail($where, $fields = null){
        if(is_null($fields)){
            $fields = $this->selectFields;
        }
        $userInfo = $this->field($fields)->where($where)->find();
        return $userInfo;
    }
}