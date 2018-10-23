<?php
/**
 * 版本更新控制器
 */
namespace Admin\Controller;
use Think\Controller;
class VersionController extends CommonController {

    public function editVersion(){
        $data = I('post.');
        $id = I('id', 0, 'intval');
        $model = D('Version');
        if(IS_POST){
            if ($id > 0){
                if($model->create($data, 2)){
                    if ($model->save() !== false) {
                        $this->ajaxReturn(V(1, '修改成功!'));
                    }
                }
            } else {
                if($model->create($data, 1)){
                    if ($model->add() !== false) {
                        $this->ajaxReturn(V(1, '保存成功!'));
                    }
                }
            }
            $this->ajaxReturn(V(0, $model->getError()));
        }
        $info = M('Version')->find($id);
        $this->info = $info;
        $this->display();
    }

    /**
     * @desc 版本更新列表
     */
    public function listVersion(){
        $where = array();
        $keywords = I('keyword', '', 'trim');
        if($keywords) $where['version'] = $keywords;
        $list = D('Version')->getVersionList($where);
        $this->keyword = $keywords;
        $this->list = $list['info'];
        $this->page = $list['page'];
        $this->display();
    }

    //删除广告
    public function del(){
        $this->_del('Version', 'id');
    }
}