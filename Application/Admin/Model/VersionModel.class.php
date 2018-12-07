<?php
/**
 * Created by PhpStorm.
 * User: tianzhongjian
 * Desc : 更新版本信息
 */
namespace Admin\Model;
use Think\Model;
class VersionModel extends Model {

    protected $insertFields = array('version', 'version_desc', 'version_link', 'version_type', 'add_time');
    protected $updateFields = array('version', 'version_desc', 'version_link', 'version_type', 'id');
    protected $selectFields = array();

    protected $_validate = array(
        array('version', 'require', '版本号不能为空！', 1, 'regex', 3),
        // array('version_desc', 'require', '版本更新信息不能为空！', 1, 'regex', 3),
        array('version_link', 'require', '版本链接不能为空！', 1, 'regex', 3),
        // array('version_type', 'require', '请选择版本类型！', 1, 'regex', 3)
    );

    /**
     * @desc 获取版本更新列表
     * @param $where
     * @param bool $field
     * @param string $order
     * @return array
     */
    public function getVersionList($where, $field = false, $order = 'id desc'){
        if(!$field) $field = '*';
        $number = $this->where($where)->count();
        $page = get_web_page($number);
        $list = $this->where($where)->field($field)->limit($page['limit'])->order($order)->select();
        return array(
            'info' => $list,
            'page' => $page['page']
        );
    }

    public function getVersionInfo($where){
        $version = $this->where($where)->order('add_time desc')->find();
        return $version;
    }

    public function _before_insert(&$data, $option){
        $data['add_time'] = NOW_TIME;
    }

    public function _before_update(&$data, $option){
    }
   
}
