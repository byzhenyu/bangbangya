<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://liuniukeji.com
 *
 * @Description     代理商管理
 * @Author         (jicy QQ:510434563)
 * @Copyright      Copyright (c) 山东六牛网络科技有限公司 保留所有版权(https://www.liuniukeji.com)
 * @Date           2018/08/28
 * @CreateBy       ${PRODUCT_NAME}
 */

namespace Admin\Controller;


class AgentController extends CommonController
{
    /**
     * 代理商
     */
    public function listAgent(){

        $keyword = I('keyword', '', 'trim');
        $where = array();

        if ($keyword) {
            $where['agent_name'] = array('like','%'.$keyword.'%');
        }
        $data = D('Admin/Agent')->getAgentByPage($where);

        $this->info = $data['info'];
        $this->page = $data['page'];
        $this->keyword = $keyword;
        $this->display();
    }

    /**
     * 编辑
     */
    public function editAgent() {
        $id = I('id', 0, 'intval');
        $model = D('Admin/Agent');
        if (IS_POST) {
            $data = I('post.', '');
            if ($model->create($data)!== false) {
                if ($id > 0) {
                    $res = $model->save();
                } else {
                    $res = $model->add();
                }
                if ($res ===false) {
                    $this->ajaxReturn(V(0,$model->getError()));
                } else {
                    $this->ajaxReturn(V(1,'操作成功'));
                }
            } else {
                $this->ajaxReturn(V(0, $model->getError()));
            }
        }
        $info = M('Agent')->where(array('id'=>$id))->find();
        $this->info = $info;
        $this->display();
    }

    // 删除方法
//    public function del(){
//        $this->_del('School', 'id');  //调用父类的方法
//    }
    public function changeDisabled() {
        $this->_changeDisabled('Agent');
    }
    public function recycle() {
        $this->_recycle('Agent','id');
    }
}