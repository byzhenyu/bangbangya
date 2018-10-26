<?php
/**
 *轮播图属性模型类
 *
 */
namespace Admin\Model;
use Think\Model;
class BannerModel extends Model{
    protected $insertFields = array('title', 'img_url', 'jump_url', 'sort', 'add_time');
    protected $updateFields = array('id','title', 'img_url', 'jump_url', 'sort');
    protected $selectFields = array('id','title', 'img_url', 'jump_url', 'sort', 'add_time','status');
    protected $_validate = array(
        array('title', 'require', '标题不能为空！', 1, 'regex', 3),
        array('img_url', 'require', '图片地址不能为空！', 1, 'regex', 3),
        array('jump_url', 'require', '跳转地址不能为空！', 1, 'regex', 3),
    );
    /**
     * @desc 获取轮播图列表
     * @param $where array 检索条件
     * @param $field string 展示字段
     * @param $sort string 排序顺序
     * @return mixed
     */
    public function getBannerListByPage($where, $field = false, $sort = 'sort asc , add_time desc'){

        if(is_null($field)){
            $field = $this->selectFields;
        }
        $count = $this->where($where)->count();
        $page = get_page($count, 10);
        $bannerlist = $this->field($field)->where($where)->limit($page['limit'])->order($sort)->select();
        return array(
            'bannerlist'=>$bannerlist,
            'page'=>$page['page']
        );
    }
    /**
     * 修改用户启用禁用状态
     * @param $id
     * @return array
     */
    public function changeDisabled($id) {

        $bannerlist = $this->where(array('id'=>$id))->field('status, id')->find();
        $dataInfo = $bannerlist['status'] == 1 ? 0 : 1;
        $update_info = $this->where(array('id'=>$id))->setField('status', $dataInfo);
        if($update_info !== false){
            return V(1, '操作成功');
        } else {
            return V(0, '操作成功');
        }
    }
}