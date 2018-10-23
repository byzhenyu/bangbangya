<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     学校管理
 * @Author         (jicy QQ:510434563)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/08/28
 * @CreateBy       ${PRODUCT_NAME}
 */

namespace Admin\Controller;


class SchoolController extends CommonController
{
    /**
     * 学校
     */
    public function listSchool(){

        $keyword = I('keyword', '', 'trim');
        $where = array();

        if ($keyword) {
            $where['school_name'] = array('like','%'.$keyword.'%');
        }
        $schoolInfo = D('Admin/School')->getSchoolByPage($where);

        $this->info = $schoolInfo['info'];
        $this->page = $schoolInfo['page'];
        $this->keyword = $keyword;
        $this->display();
    }

    /**
     * 编辑
     */
    public function editSchool() {
        $id = I('id', 0, 'intval');
        $model = D('Admin/School');
        if (IS_POST) {
            $data = I('post.', '');
            if ($model->create($data)!== false) {
                if ($id > 0) {
                    $res = $model->save();
                } else {
                    $res = $model->add();
                }
                if ($res ===false) {
                    $this->ajaxReturn(V(0,'操作失败'));
                } else {
                    $this->ajaxReturn(V(1,'操作成功'));
                }
            } else {
                $this->ajaxReturn(V(0, $model->getError()));
            }
        }
        $info = M('School')->where(array('id'=>$id))->find();
        $this->info = $info;
        $this->display();
    }

    // 删除方法
//    public function del(){
//        $this->_del('School', 'id');  //调用父类的方法
//    }

    public function recycle() {
        $this->_recycle('School','id');
    }
}