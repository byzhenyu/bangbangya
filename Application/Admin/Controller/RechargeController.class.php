<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description    充值管理
 * @Author         jicy
 * @Date           2018/11/6
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;

/**
 * 充值记录表控制器
 */
class RechargeController extends CommonController {

    public function listDepositRecharge() {
        $where['r.status'] = array('eq', 1);
        $where['r.recharge_type'] = array('eq', 1);
        $data = D('Admin/Recharge')->getRechargeList($where);
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }

    public function listBalanceRecharge() {
        $where['r.status'] = array('eq', 1);
        $where['r.recharge_type'] = array('eq', 0);
        $data = D('Admin/Recharge')->getRechargeList($where);
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    
    public function editRecharge() {
        $id = I('id');
        $rechargeModel = D('Admin/Recharge');
        
        if (IS_POST) {
            if ($rechargeModel->create() === false) {
                $this->ajaxReturn(V(0, $rechargeModel->getError()));
            }
            if ($id) {
                if ($rechargeModel->save() !== false) {
                    $this->ajaxReturn(V(1, '编辑成功'));
                }
            } else {
                if ($rechargeModel->add() !== false) {
                    $this->ajaxReturn(V(1, '添加成功'));
                }
            }
            $this->ajaxReturn(V(0, $rechargeModel->getDbError()));
        }
        
        $info = $rechargeModel->find($id);
        $this->assign('info', $info);
        $this->display();
    }
    
    public function del(){
        $this->_del('Recharge', 'id');
    }
}
        