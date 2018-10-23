<?php
/**
 * 课程单元模型
 */
namespace Admin\Model;

use Think\Model;

class CourseWordModel extends Model {
    protected $insertFields = array('class_id', 'unit_id', 'word_id', 'sort');
    protected $updateFields = array('id', 'class_id', 'unit_id', 'word_id', 'sort');
    protected $_validate = array(
        array('unit_id', 'require', '单元不能为空！', 1, 'regex', 3),
    );

    /**
     * 获取单元单词列表
     * @param
     * @return mixed
     */
    public function getCourseWordList($where, $field = false){
        if(!$field) $field = 'w.*,u.unit,r.words,c.class_name';
        $count = $this->alias('w')->join('__COURSE_UNIT__ as u on w.unit_id = u.id')->join('__COURSE_CLASS__ as c on c.id = w.class_id')->join('__WORD__ as r on r.id = w.word_id')->where($where)->count();
        $page = get_web_page($count);
        $list = $this->alias('w')->join('__COURSE_UNIT__ as u on w.unit_id = u.id')->join('__COURSE_CLASS__ as c on c.id = w.class_id')->join('__WORD__ as r on r.id = w.word_id')->field($field)->where($where)->limit($page['limit'])->order('w.id desc')->select();

        return array('info' => $list, 'page' => $page['page']);
    }

    /**
     * @desc 获取某单元下的单词数量
     * @param $unit_id int 单元id
     * @return int
     */
    public function getCourseWordsCount($unit_id){
        if(!$unit_id) return 0;
        $count = $this->where(array('unit_id' => $unit_id))->count();
        return $count;
    }

    //添加操作前的钩子操作
    protected function _before_insert(&$data, $option){
        $word = I('post.words');
        if($word){
            $model = M('Word');
            $info = $model->where(array('words' => $word))->find();
            if(!$info){
                $this->error = '没找到对应的单词，查看单词录入或类型是否有误！';
                return false;
            }
            $data['word_id'] = $info['id'];
        }
        else {
            $this->error = '请填写想要录入的单词！';
            return false;
        }
        if($this->where(array('word_id' => $data['word_id'], 'unit_id' => $data['unit_id']))->find()){
            $this->error = '单元单词已经存在！';
            return false;
        }
        if(!$data['class_id']){
            $unit = M('CourseUnit');
            $unitInfo = $unit->find($data['unit_id']);
            $data['class_id'] = $unitInfo['class_id'];
        }
    }
    //更新操作前的钩子操作
    protected function _before_update(&$data, $option){
        $word = I('post.words');
        if($word){
            $model = M('Word');
            $info = $model->where(array('words' => $word))->find();
            if(!$info){
                $this->error = '没找到对应的单词，查看单词录入或类型是否有误！';
                return false;
            }
            $data['word_id'] = $info['id'];
        }
        else {
            $this->error = '请填写想要录入的单词！';
            return false;
        }
        if(!$data['class_id']){
            $unit = M('CourseUnit');
            $unitInfo = $unit->find($data['unit_id']);
            $data['class_id'] = $unitInfo['class_id'];
        }
    }

}