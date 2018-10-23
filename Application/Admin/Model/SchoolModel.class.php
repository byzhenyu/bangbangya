<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description    学校管理
 * @Author         (jicy QQ:510434563)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/08/28
 * @CreateBy       ${PRODUCT_NAME}
 */

namespace Admin\Model;


use Think\Model;

class SchoolModel extends Model
{
    protected $insertFields = array( 'province', 'city', 'district', 'school_name','sort');
    protected $updateFields = array( 'province', 'city', 'district', 'school_name','sort');
    protected $selectFields = array('id', 'province', 'city', 'district', 'school_name','sort');
    protected $_validate = array(
        array('province', 'require', '省不能为空', 1, 'regex', 3),
        array('city', 'require', '市不能为空', 1, 'regex', 3),
        array('district', 'require', '县/区不能为空', 1, 'regex', 3),
        array('school_name', 'checkNameExist', '学校名称重复', self::MUST_VALIDATE, 'callback', 3),
        array('sort', 'number', '排序字段必须是数字！', self::VALUE_VALIDATE, 'regex', 3),

    );

    protected function checkNameExist($data) {
        $id = I('id',0,'intval');
        $where['school_name'] = array('eq', $data);
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
    /**
     * 意见反馈分页数据
     * @param $where
     * @return array
     */
    public function getSchoolByPage($where, $field = null, $order = 'sort asc, id desc'){

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
}