<?php
/**
 * 用户测试结果模型
 */
namespace Admin\Model;

use Think\Model;

class UserTestResultModel extends Model {
    protected $insertFields = array();
    protected $updateFields = array();
    protected $_validate = array(
        array('unit_id', 'require', '单元不能为空！', 1, 'regex', 3),
    );

    /**
     * 获取排名列表
     */
    public function getRankList() {
        $User = D('User');

        $count = $User->count();
        $page = get_page($count);
        $list = $User->limit($page['limit'])->order('master_words_num desc')->select();

        $p = I('p', 1);
        $page_size = C('PAGE_SIZE');
        foreach ($list as $k => $v) {
            $list[$k]['rank'] = ($p - 1) * $page_size + $k + 1;
        }

        return array(
            'list' => $list,
            'page' => $page['page'],
        );
    }

    /**
     * 获取用户测试结果列表
     */
    public function getUserTestResultList($where = [], $field = '', $order = '') {
        $alias = 'utr';
        $join = [
            'left join ln_user u on utr.user_id = u.user_id',
            'left join ln_course_class cc on utr.class_id = cc.id',
            'left join ln_course_unit cu on utr.unit_id = cu.id',
        ];
        $count = $this->alias($alias)->join($join[0])->join($join[1])->join($join[2])->where($where)->count();
        $page = get_page($count);
        $list = $this->alias($alias)->join($join[0])->join($join[1])->join($join[2])->field($field)->where($where)->limit($page['limit'])->order($order)->select();
        return array(
            'list' => $list,
            'page' => $page['page'],
        );
    }

    /**
     * @desc 用户单元单词测试数量
     * @param $where
     * @return int
     */
    public function statisticWordsNum($where) {
        if (!is_array($where)) {
            return 0;
        }

        $res = $this->where($where)->field('word_id')->count('word_id');
        return $res;
    }

    /**
     * @desc 测试排名情况
     * @param $where
     * @param bool $field
     * @return mixed
     */
    public function rankingList($where, $field = false, $ranking) {
        if (!$field) {
            $field = 'count(t.word_id) as number,u.head_pic,u.truename as show_name';
        }

        $p = I('p', 1, 'intval');
        $limit = ($p - 1) * $ranking . ',' . $p * $ranking;
        $list = $this->alias('t')->where($where)->group('t.user_id')->join('__USER__ as u on t.user_id = u.user_id')->field($field)->limit($limit)->order('number desc')->select();
        foreach ($list as &$val) {
            $val['head_pic'] = strval($val['head_pic']);
        }
        return $list;
    }

    /**
     * @desc 获取当前登录用户的排名情况
     * @param $type
     * @return mixed
     */
    public function getCurrentRowNumber($type) {
        $uid = UID;
        $timeArray = time_rand($type);
        $timeString = ' and test_time between ' . $timeArray['start'] . ' and ' . $timeArray['end'];
        $model = M();
        $sql = 'select rownumber from (select number,@rownumber:=@rownumber+1 as rownumber,user_id from (select @rownumber:=0) as r,(select count(1) as number,user_id from ln_user_test_result where test_result = 1 ' . $timeString . ' group by user_id order by number desc) as t) as sel where sel.user_id = ' . $uid;
        $query = $model->query($sql);
        $currentRowNumber = $query[0]['rownumber'];
        return $currentRowNumber;
    }

    /**
     * @desc 学生测试详情
     * @param $where array 条件
     * @param bool $field 展示字段
     * @param string $order 排序顺序
     * @return mixed
     */
    public function userTestResultList($where, $field = false, $order = '') {
        if (!$field) {
            $field = 't.*,u.unit';
        }

        $number = $this->alias('t')->join('__COURSE_UNIT__ as u on t.unit_id = u.id')->where($where)->count();
        $page = get_web_page($number);
        $list = $this->alias('t')->join('__COURSE_UNIT__ as u on t.unit_id = u.id')->where($where)->field($field)->limit($page['limit'])->order($order)->select();
        return array(
            'info' => $list,
            'page' => $page['page'],
        );
    }

    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option) {

    }
    //更新操作前的钩子操作
    protected function _before_update(&$data, $option) {
    }

}