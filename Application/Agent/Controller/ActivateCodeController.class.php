<?php
/**
 * 激活码管理控制器
 */
namespace Agent\Controller;
use Common\Controller\AgentCommonController;
class ActivateCodeController extends AgentCommonController {

    /**
     * @desc 激活码列表
     */
    public function listActivateCode(){
        $keywords = I('keywords', '', 'trim');
        $model = D('Admin/ActivateCode');
        $where['a.agent_id'] = array('eq', AGENT_ID);
        if($keywords) $where['code'] = array('like', '%'.$keywords.'%');
        $list = $model->listActivateCode($where);
        $this->keywords = $keywords;
        $this->list = $list['info'];
        $this->page = $list['page'];
        $this->display();
    }

    /**
     * @desc 激活码生成
     */
    public function generateActivateCode(){
        $typeList = activate_code_type();
        $code_keys = array_keys($typeList);
        if(IS_POST){
            $type = I('type');
            $number = I('number');
            if(!in_array($type, $code_keys)) $this->ajaxReturn(V(0, '请选择激活码类型！'));
            if($number < 0) $this->ajaxReturn(V(0, '请输入想要生成的激活码数量！'));
            if($number > 10000) $this->ajaxReturn(V(0, '单次生成激活码数量不能超过10000条！'));
            $dataArray = [];
            for ($i = 0; $i < $number; $i++) {
                $randCode = rand(111111, 999999) . rand(111111, 999999);
                $time = NOW_TIME;
                $dataArray[] = array(
                    'code' => $randCode,
                    'type' => $type,
                    'create_time' => $time,
                    'agent_id' => AGENT_ID,
                );
            }
            $model = D('Admin/ActivateCode');
            $res = $model->addAll($dataArray);
            if(false !== $res){
                $this->ajaxReturn(V(1, '激活码生成成功！'));
            }
            else{
                $this->ajaxReturn(V(0, '激活码生成失败,请稍后重试！'));
            }
        }
        $code_type = array();
        foreach($code_keys as &$val){
            $code_type[] = array(
                'id' => $val,
                'type_name' => $typeList[$val]
            );
        }
        $this->code_type = $code_type;
        $this->display();
    }

    /**
     * @desc 激活码禁用启用
     */
    public function changeDisabled(){
        $code_id = I('code_id');
        $res = D('Admin/ActivateCode')->changeDisabled($code_id);
        if(false !== $res){
            $this->ajaxReturn(V(1, '修改成功！'));
        }
        else{
            $this->ajaxReturn(V(0, '修改失败！'));
        }
    }

    public function del() {
        $this->_del('ActivateCode','id');
    }
}