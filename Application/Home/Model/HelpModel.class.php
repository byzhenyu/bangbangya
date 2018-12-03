<?php
/**
 * Created by PhpStorm.
 * ShopModel
 */
namespace Home\Model;
use  Think\Model;
class HelpModel extends Model {
    protected $insertFields = array('title', 'type', 'content', 'add_time','status','sort');
    protected $updateFields = array('id','title', 'type', 'content', 'add_time','status','sort');
    protected $selectFields = array('id','title', 'type', 'content', 'add_time');
    protected $findFields = array('id','title', 'type', 'content', 'add_time');
    protected $_validate = array(
        array('title', 'require', '请输入帮助问题的标题 ', 1, 'regex', 3),
        array('title', '0,255', '您输入的 任务标题 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('content', 'require', '请输入详情  ', 1, 'regex', 3),
        array('add_time', 'require', '创建数据的时间', 1, 'regex', 3)
    );
    /**
     * @desc 申诉类查询
     * @param $where array 检索条件
     * @param $field string 展示字段
     * @param $sort string 排序顺序
     * @return mixed
     */
    public function getHelpList($where, $field = false, $sort = 'sort asc , add_time desc')
    {
        $where['status'] = 1;
        if(is_null($field)){
            $field = $this->selectFields;
        }
        $count = $this->where($where)->count();
        $page = get_page($count, 10);
        $Helplist = $this->field($field)->where($where)->limit($page['limit'])->order($sort)->select();
        return array(
            'Helplist'=>$Helplist,
            'page'=>$page['page']
        );
    }

    public function getHelpListNot($where, $field = false, $sort = 'sort asc , add_time desc')
    {
        if(is_null($field)){
            $field = $this->selectFields;
        }
        $Helplist = $this->field($field)->where(array('status'=>1))->where($where)->order($sort)->select();
        return $Helplist;
    }
    /**
    * @desc
    * @param
    * @return mixed
    */
    public function getQuestionDetail($id = 0,$field  = null){
        if($id == 0) return false;
        if(is_null($field)){
            $field = $this->findFields;
        }
        $info = $this->field($field)->where('id = '.$id)->find();
        return $info;
    }
}