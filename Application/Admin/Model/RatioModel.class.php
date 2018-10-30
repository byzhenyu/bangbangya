<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;

/**
 * 任务表模型
 */
class RatioModel extends Model {
    protected $insertFields = array('pay', 'depoist', 'change_time');
    protected $updateFields = array('id', 'pay', 'depoist');

    protected $_validate = array(
        array('pay', 'require', '请输入充值比例', 1, 'regex', 3),
        array('depoist', 'require', '您输入的提现比例', 1, 'length', 3)  
    );
    protected function _before_update(&$data, $option){
        $data['change_time'] = NOW_TIME;
    }
    /**
     * 二级分销比例
     * @return   arr
     */
    public function getDistribution()
    {
        $result = $this->find();
        return $result;
    }
}