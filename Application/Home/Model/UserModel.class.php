<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 * 
 * @Description    
 * @Author         (wangzhenyu/byzhenyu@qq.com)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           ${DATE} ${TIME}
 * @CreateBy       ${PRODUCT_NAME}
 */
namespace Home\Model;
use Think\Model;
use Common\Tools\Emchat;
class UserModel extends Model
{
    protected $findFields = array('user_id', 'user_name','mobile','head_pic','nick_name','frozen_money','bonus_money','total_money','alipay_num','alipay_name','invitation_code','invitation_uid','open_id');
    //登录时表单验证的规则
    public $_login_validate = array(
        array('open_id', 'require', '微信未授权登录!',1,'regex',3),
    );

    // 用户登录
    public function doLogin($open_id,$where=[], $field=null){
        if ($open_id == '') return V(0, '参数错误');
        $where['u.status'] = 1; // 用户未被逻辑删除
        $where['u.open_id'] = $open_id; // 用户未被逻辑删除
        /* 获取用户数据 */
        $user = $this->alias('u')
             ->join('__SHOP__ as s on s.user_id = u.user_id','LEFT')
             ->field($field)
             ->where($where)
             ->find();
        if(is_array($user)){
                if ($user['disabled'] != 1) {
                    return V(0, '用户账号已经被禁用');
                }
                unset($user['password']);
                $user['token'] = $this->_createTokenAndSave($user); //生成登录token并保存
                return V(1, '登录成功', $user);
        } else {
            return V(2, '登录名或者密码错误');
        }

    }
    /**
    * @desc 生成新的用户
    * @param $open_id
    * @return mixed
    */
    public function createNewUser($data = []){
        M()->startTrans();
        $user_id  =  $this->add($data);
        $data['user_id'] = $user_id;
        $shop_id  = D('Home/Shop')->add($data);
        if($user_id && $shop_id){
            $this->doLogin($user_id);
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
        $data['token'] = $token;
        $data['login_time'] = NOW_TIME;
        D('Home/UserToken')->where($where)->data($data)->save();
        return $token;
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
     * 生成用户的邀请码 唯一性
     * @param $user_id
     * @return   str
     */
    function createCode($user_id){

        static $source_string = 'E5FCDG3HQA4B1NOPIJ2RSTUV67MWX89KLYZ';

        $num = $user_id;

        $code = '';

        while ( $num > 0) {

            $mod = $num % 35;

            $num = ($num - $mod) / 35;

            $code = $source_string[$mod].$code;

        }

        if(empty($code[3]))

            $code = str_pad($code,4,'0',STR_PAD_LEFT);

        return $code;

    }
     /**
     * 解码
     * @param $code  
     * @return   user_id
     */
    function decode($code) {

    static $source_string = 'E5FCDG3HQA4B1NOPIJ2RSTUV67MWX89KLYZ';

    if (strrpos($code, '0') !== false)

        $code = substr($code, strrpos($code, '0')+1);

    $len = strlen($code);

    $code = strrev($code);

    $num = 0;

    for ($i=0; $i < $len; $i++) {

        $num += strpos($source_string, $code[$i]) * pow(35, $i);

    }

    return $num;

   }
   /**
    * 用户信息
    * @param  $where
    * @param  $field
    * @return arr
    */
   public function getUserInfo($where = [],$field = null){
        if(is_null($field))  $field = $this->findFields;
        $info = $this->alias('u')
               ->join('__SHOP__ as s on u.user_id = s.user_id','LEFT')
               ->field($field)
               -> where($where)
               ->find();
        /*判断是否缴纳保证金*/
        if($info['shop_accounts'] > 0) {
            $info['is_accounts'] = 1;
        }else{
            $info['is_accounts'] = 0;
       }
       $info['zong'] = $info['bonus_money'] + $info['task_suc_money'];
        return $info;
   }
   /**
   * @desc 获取用户字段
   * @param $where
   * @param $field
   * @return mixed
   */
   public function getUserField($where = [], $field = null){
       if(is_null($field)){
           $field = $this->findFields;
       }
       $userField = $this->where($where)->getField($field);
       return $userField;
   }
   /**
    * 赚钱排名
    */
   public function getRankList($where = [],$field = "", $sort = ' task_suc_money DESC'){
       if(is_null($field))  $field = $this->findFields;
       $count = $this->where($where)->count();
       $page = get_page($count, 10);
       $ranklist = $this->field($field)->where($where)->limit($page['limit'])->order($sort)->select();
        return array(
            'ranklist'=>$ranklist,
            'page'=>$page['page']
        );
   }

   public function getUserInfoWithShop($where=[],$field='') {
        if ($field =='') {
            $field = 'u.*,s.*';
        }
        $info = $this->alias('u')
            ->join('__SHOP__ s on s.user_id = u.user_id')
            ->field($field)
            ->where($where)
            ->find();

        return $info;
   }
}
