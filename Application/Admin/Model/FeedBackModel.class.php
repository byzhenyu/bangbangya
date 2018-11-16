<?php
/**
 * Created by PhpStorm.
 */

namespace Admin\Model;


use Think\Model;

class FeedBackModel extends Model
{
    protected $selectFields = array('id', 'comment', 'user_id', 'create_time', 'type');

    protected $_validate = array(
        array('comment', 'require', '请输入反馈内容', 1, 'regex', 3),
        array('comment', 'checkCommentLength', '反馈内容请保持在10-200字', 2, 'callback', 3),
        array('type', 'require', '反馈类型不能为空', 2, 'regex', 3)
    );

    /**
     * @desc 检测反馈内容长度
     * @param $data
     * @return bool
     */
    protected function checkCommentLength($data) {
        $length = mb_strlen($data, 'utf-8');
        if ($length < 10 || $length > 200) {
            return false;
        }
        return true;
    }
    /**
     * 意见反馈分页数据
     * @param $where
     * @return array
     */
    public function getFeedBackByPage($where, $field = null, $order = 'create_time desc'){
        $count = $this->alias('f')
                ->join('__USER__ as u on u.user_id = f.user_id','LEFT')
                ->where($where)
                ->count();
        $page = get_page($count);
        $info = $this->alias('f')
                ->join('__USER__ as u on u.user_id = f.user_id','LEFT')
                ->field($field)
                ->where($where)
                ->limit($page['limit'])
                ->order($order)
                ->select();
        return array(
            'info' => $info,
            'page' => $page['page'],
        );
    }

    public function _before_insert(&$data, $option){
        $data['create_time'] = NOW_TIME;
        $data['user_id'] = UID;
    }
}