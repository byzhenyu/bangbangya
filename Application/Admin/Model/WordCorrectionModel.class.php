<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author
 * @Date           2018/9/6
 * @CreateBy       PhpStorm
 */
namespace Admin\Model;

use Think\Model;

/**
 * 单词纠错表模型
 */
class WordCorrectionModel extends Model {

    public function getWordCorrectionList($where = [], $field = '', $order = '') {
        $alias = 'wc';
        $join = [
            'left join ln_user u on wc.user_id = u.user_id',
            'left join ln_word w on wc.word_id = w.id',
        ];
        $count = $this->alias($alias)->join($join[0])->join($join[1])->where($where)->count();
        $page = get_page($count);
        $list = $this->alias($alias)->join($join[0])->join($join[1])->field($field)->where($where)->limit($page['limit'])->order($order)->select();
        return array(
            'list' => $list,
            'page' => $page['page'],
        );
    }

}
