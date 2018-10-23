<?php
/**
 * 轮播管理类
 */
namespace Admin\Controller;
use Think\Controller;
class AdPositionController extends CommonController {

    // 轮播位置列表
    public function listAdPosition(){
        $where = array();
        $data = D('Admin/AdPosition')->getAdPositionlist($where);
        $this->assign('page', $data['page']);
        $this->assign('data', $data['info']);
        $this->display();
    }
    
    // 编辑轮播位置
    public function editAdPosition(){
        $position_id = I('position_id',0,'intval');
        $adpositionModel = D('Admin/AdPosition');
        if(IS_POST){
            if ($position_id > 0){
                if($adpositionModel->create(I('post.'), 2)){
                    if ($adpositionModel->save() !== false) {
                        $this->ajaxReturn(V(1, '修改成功!'));
                    }
                }
            } else {
                if($adpositionModel->create(I('post.'), 1)){
                    if ($adpositionModel->add() !== false) {
                        $this->ajaxReturn(V(1, '保存成功!'));
                    }
                }
            }
            $this->ajaxReturn(V(0, $adpositionModel->getError()));
        }

        $adpositionInfo = $adpositionModel->find($position_id);
        $this->assign('adpositionInfo', $adpositionInfo);
        $this->display();
    }
     
    //删除轮播位置
    public function del(){
        $position_id = I('position_id', 0, 'intval');
        $adInfo = M('ad')->where('position_id = ' .$position_id)->find();
        if (empty($adInfo)) {
            $this->_del('ad_position', 'position_id');
        } else {
            $this->ajaxReturn(V(0, '此轮播位置下含有轮播,您不能删除'));
        }
        
    }
}