<?php
/**
 * 轮播管理模型类
 */
namespace Admin\Model;
use Think\Model;
class AdModel extends Model { 
    protected $insertFields = array('title','type','item_id','link_url','description','start_time','end_time','display','content','sort','position_id','background_color');
    protected $updateFields = array('ad_id','title','type','item_id','link_url','description','start_time','end_time','display','content','sort','position_id','background_color');
    protected $_validate = array(
        array('title', 'require', '轮播名称不能为空！', 1, 'regex', 3),
        array('title', '1,45', '轮播名称的值最长不能超过 45 个字符！', 1, 'length', 3),
        array('position_id', '1, 100000', '轮播位置不能为空！', 1, 'between', 3),
        array('start_time', 'require', '轮播开始时间不能为空！', 1, 'regex', 3),
        array('end_time', 'require', '轮播结束时间不能为空！', 1, 'regex', 3),
        array('content', '1,150', '请上传轮播图片', 1, 'length', 3),
        array('link_url', '0,150', '轮播网站地址的值最长不能超过 150 个字符！', 1, 'length', 3),
        array('display', 'number', '是否启用 1：启用0：禁用必须是一个整数！', 2, 'regex', 3)
    );

    //获取轮播列表
    public function getAdlist($where, $field = null, $order = 'ad_id desc')
    {
        $count = $this->alias('ad')
            ->join('__AD_POSITION__ pos on ad.position_id = pos.position_id','left')
            ->where($where)
            ->count('ad.ad_id');
        $page = get_page($count);

        $info = $this->alias('ad')
            ->join('__AD_POSITION__ pos on ad.position_id = pos.position_id','left')
            ->field($field)
            ->where($where)
            ->limit($page['limit'])
            ->order($order)
            ->select();
  
        return array(
            'info' => $info,
            'page' => $page['page']
        );
    }

    /**
     * @desc 获取轮播内容
     * @param $where
     * @param $field
     * @return bool|mixed
     */
    public function getAdField($where, $field){
        if(!$where || !$field) return false;
        return $this->where($where)->getField($field, true);
    }


    //轮播添加之前的钩子操作
    protected function _before_insert(&$data, $option){

    }
    //修改轮播的钩子操作
    protected function _before_update(&$data, $option){

    }
    //删除轮播的钩子操作
    public function _before_delete($option){
        if(is_array($option['where']['ad_id'])){
            $this->error = '不支持批量删除';
            return false;
        }
        $images = $this->field('content')->find($option['where']['ad_id']);
        deleteImage($images);
    }
}
