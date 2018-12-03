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
class HelpModel extends Model {
    protected $insertFields = array('title', 'type', 'content', 'add_time','status','sort');
    protected $updateFields = array('id','title', 'type', 'content', 'add_time','status','sort');
    protected $selectFields = array('id','title', 'type', 'content', 'add_time','status','sort');
    protected $_validate = array(
        array('title', 'require', '请输入帮助问题的标题 ', 1, 'regex', 3),
        array('title', '0,255', '您输入的 任务标题 过长，超过了 255 个字符数限制', 1, 'length', 3),
        array('content', 'require', '请输入详情  ', 1, 'regex', 3),
        array('add_time', 'require', '创建数据的时间', 1, 'regex', 3)
    );
    /**
     * @desc 申诉类查询
     * @param $where array 检索条件
     * @param $field string 展示字段
     * @param $sort string 排序顺序
     * @return mixed
     */
    public function getHelpList($where, $field = false, $sort = 'sort asc, id desc')
    {
        if(is_null($field)){
            $field = $this->selectFields;
        }
        $count = $this->where($where)->count();
        $page = get_page($count, 100);
        $Helplist = $this->field($field)->where($where)->limit($page['limit'])->order($sort)->select();
        return array(
            'Helplist'=>$Helplist,
            'page'=>$page['page']
        );
    }
    /**
     * 禁用问题信息
     * @param   $[where] [<arr>] 检索条件
     * @return json 
     */
    public function changeStatus($id)
    {
        $questionInfo = $this->where('id = '.$id)->find();
        $status = $questionInfo['status'] == 1 ? 0 : 1;
        $updateInfo = $this->where('id = '.$id)->setField('status',$status);
        if($updateInfo !== false){
            return V(1, '操作成功');
        } else {
            return V(0, '操作成功');
        }
    }
    /**
     * 查询问题
     * @param $where
     * @param null $fields
     * @return mixed
     */
    public function getQuestionInfo($where=[], $fields = null){
        if(is_null($fields)){
            $fields = $this->selectFields;
        }
        $HelpInfo = $this->field($fields)->where($where)->find();
        return $HelpInfo;
    }
}