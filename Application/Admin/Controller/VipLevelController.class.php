<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description    合作商管理
 * @Author         
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;
class VipLevelController extends CommonController {

    public function listVipLevel(){
        $where['status'] = array('eq',1);

        $data = D('Admin/VipLevel')->getVipLevelList($where);

        $this->assign('list', $data['info']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    
    public function editVipLevel() {
        $id = I('id', 0, 'intval');
        $LevelModel = D('Admin/VipLevel');
        if (IS_POST) {
            $data = I('post.', '');
            $data['vip_price'] = yuan_to_fen($data['vip_price']);

            if($LevelModel->create($data) !== false) {
                if ($id > 0) {

                    $res = $LevelModel->save();

                } else {
                    $res = $LevelModel->add();
                }

                if ($res === false) {
                    $this->ajaxReturn(V(0, '操作失败'));
                }

                $this->ajaxReturn(V(1,'操作成功'));
            } else {
                $this->ajaxReturn(V(0,$LevelModel->getError()));
            }
        }
        $info = $LevelModel->getVipLevelDetail($id);
        $vipLevel = C('VIP_LEVEL');
        $vipTime = C('VIP_TIME');
        //p($info);
        $this->assign('vipLevel',$vipLevel);
        $this->assign('vipTime',$vipTime);
        $this->assign('id', $id);
        $this->assign('info', $info);
        $this->display();
    }

    public function recycle() {
        $this->_recycle('vip_level','id');
    }

    public function del() {
        $this->_del('vip_level', 'id');
    }
}
        