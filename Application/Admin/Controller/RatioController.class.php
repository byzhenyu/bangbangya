<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author         
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;

/**
 * 分销比例控制器
 */
class RatioController extends CommonController {
   /**
     * 二级分销比例
     * @return arr
     */
    public function distribution()
    {
        $list  = D('Admin/Ratio')->getDistribution();
        $this->list = $list;
        $this->display();
    }
    /**
     * 修改分销比例
     */
    public function editDistribution()
    {
        $id = I('id',0,'intval');
        $RatioModel = D('Admin/Ratio');
        if(IS_POST)
        {
            if ($RatioModel->create() === false) {
                $this->ajaxReturn(V(0, $RatioModel->getError()));
            }
            if ($id) {
                if ($RatioModel->save() !== false) {
                    $this->ajaxReturn(V(1, '编辑成功'));
                }
            }
            $this->ajaxReturn(V(0, $RatioModel->getDbError()));
        }
        $list  = $RatioModel->getDistribution();
        $this->list = $list;
        $this->display();
    }
}