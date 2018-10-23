<?php

namespace Agent\Controller;
use Common\Controller\AgentCommonController;
class AgentController extends AgentCommonController{

    public function listAgentAccount(){
        $where = array('agent_id' => AGENT_ID);
        $keywords = I('keywords', '', 'trim');
        if($keywords) {
            $where['order_sn'] = array('like', '%'.$keywords.'%');
        }
        $model = D('AgentAccount');
        $list = $model->listAgentAccount($where);
        $this->assign('keywords', $keywords);
        $this->assign('info', $list['info']);
        $this->assign('page', $list['page']);
        $this->display('listAgentAccount');
    }

    public function setAgent(){
        if(IS_POST){
            $data = I('post.');
            $model = D('Home/Agent');
            $pwdCheck = $this->checkPwd($data);
            $create = $model->create($data, 5);
            if(!$pwdCheck['status']) $this->ajaxReturn($pwdCheck);
            if($pwdCheck['status'] && strlen($pwdCheck['info']) < 1) unset($data['password'], $data['new_password']);
            if($create !== false){
                $data['id'] = AGENT_ID;
                $res = $model->save($data);
                if(false !== $res){
                    $this->ajaxReturn(V(1, '保存成功'));
                }
                else{
                    $this->ajaxReturn(V(0, '保存失败'));
                }
            }
            else{
                $this->ajaxReturn(V(0, $model->getError()));
            }
        }
        $agentInfo = M('Agent')->find(AGENT_ID);
        $this->assign('agent', $agentInfo);
        $this->display('setAgent');
    }

    private function checkPwd($data){
        if(!$data['password'] && !$data['new_password']) return V(1, '');
        if(strlen($data['password']) != strlen($data['new_password']) || $data['password'] != $data['new_password']) return V('0', '两次密码不一致');
        $agentInfo = M('Agent')->find(AGENT_ID);
        if(pwdHash($data['password'], $agentInfo['password'], true)) return V(0, '不能和原密码一致');
        return V(1, pwdHash($data['password']));
    }

    /**
     * 学习币分销设置
     **/
    public function setDistribution(){
        if(IS_POST){
            $arr = I('post.');
            if(!$arr['first']) $this->ajaxReturn(V(0, '一级教师学习币不能为空！'));
            if(!$arr['second']) $this->ajaxReturn(V(0, '二级教师学习币不能为空！'));
            $data['id'] = AGENT_ID;
            $data['distribution_set'] = $arr['first'].'|'.$arr['second'];
            $res = M('Agent')->save($data);
            if(false !== $res){
                $this->ajaxReturn(V(1, '操作成功！'));
            }
            else{
                $this->ajaxReturn(V(0, '操作失败！'));
            }
        }
        $info = M('Agent')->find(AGENT_ID);
        if($info['distribution_set'] != '0'){
            $set = explode('|',$info['distribution_set']);
            $info['first'] = $set[0];
            $info['second'] = $set[1];
            $this->assign('info', $info);
        }
        $this->display();
    }
}