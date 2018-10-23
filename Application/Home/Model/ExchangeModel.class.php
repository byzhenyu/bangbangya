<?php
/**
 * Created by PhpStorm.
 * User: jipingzhao liuniukeji.com
 * Date: 6/30/17
 * Time: 2:53 PM
 */
namespace Home\Model;
use Think\Model;
use Common\Tools\Emchat;
class ExchangeModel extends Model
{
    protected $findFields = array('id', 'teacher_id', 'exchange_num', 'desc', 'exchange_time', 'exchange_state', 'op_time');

    /**
     * 获取学习币兑换申请列表
     * @return array
     */
    public function getExchangeListByPage($where, $field = null, $sort = 'exchange_time desc'){
        if(is_null($field)) $field = $this->findFields;
        $count = $this->where($where)->count();
        $page = get_web_page($count);
        $list = $this->field($field)->where($where)->limit($page['limit'])->order($sort)->select();
        return array(
            'list'=>$list,
            'page'=>$page['page']
        );
    }
}