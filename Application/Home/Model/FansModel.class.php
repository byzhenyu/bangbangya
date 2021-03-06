<?php
/**
 * Created by zhenyu.
 */
namespace Home\Model;
use Think\Model;
class FansModel extends Model
{
	 protected $selectFields = array('id','user_id', 'fans_user_id', 'status','add_time');
	 protected $insertFields = array('type', 'user_id', 'fans_user_id', 'status','add_time');
	 protected $updateFields = array('id', 'user_id', 'fans_user_id', 'status','add_time');
	 protected $_validate = array(
        array('userid', 'require', '关注者ID不能为空！', 1, 'length', 3),
        array('fans_user_id', 'require', '关注人的id不能为空', 1, 'length', 3)
    );
	/**
	 * 我的粉丝
	 * @param  $where 查找条件
	 * @param  $field 获取字段
	 * @param  string $sort  [order]
	 * @param  $type  类型 0 我的粉丝 1 我的关注 
	 * @return [arr]   
	 */
	public function getFansList($where=[], $field='', $sort = 'f.add_time DESC', $type) {
        if(!$field) $field = $this->selectFields;
        /*判断是我的粉丝还是我的关注*/
        $user_id = $type == 0 ? 'user_id' :  'fans_user_id';
        /*粉丝条件*/
        $where['f.status'] = 1;
        $where['u.status'] =array('eq', 1);
        // return P($where);
        $count = $this->alias('f')
                 ->join('__USER__ as u  on f.'.$user_id.' = u.user_id','LEFT')
                 ->field($field)
                 ->where($where)
                 ->count();
        $page = get_page($count);
        $list = $this->alias('f')
                ->join('__USER__ as u  on f.'.$user_id.' = u.user_id','LEFT')
                ->field($field)
                ->where($where)
                ->limit($page['limit'])
                ->order($sort)
                ->select();
        //粉丝数量

        return $list;
    }
    //获取粉丝关注数量
    public function getFansCount() {
        $fansCount = $this->alias('f')
            ->join('__USER__ as u  on f.user_id = u.user_id','LEFT')
            ->where(array('f.fans_user_id'=>UID,'f.status'=>1,'u.status'=>1))
            ->count();
        //关注数量
        $focusCount = $this->alias('f')
            ->join('__USER__ as u  on f.fans_user_id = u.user_id','LEFT')
            ->where(array('f.user_id'=>UID,'f.status'=>1,'u.status'=>1))
            ->count();

        return array(
            'fansCount'=>$fansCount,
            'focusCount'=>$focusCount
        );
    }
	/**
     * 数据插入前操作
     * @param $data
     * @param $option
     */
    protected function _before_insert(&$data, $option){
        $data['add_time'] = NOW_TIME;
        $data['status'] = 1;        
    }
     /**
     * 数据更新前操作
     * @param $data
     * @param $option
     */
    protected function _before_update(&$data, $option){
        $data['add_time'] = NOW_TIME;      
    }

}