<?php
/**
 * 获取单词情况
 */
namespace Home\Model;

use Think\Model;

class UserTestResultModel extends Model {
    protected $selectFields = array('result_id', 'user_id', 'class_id', 'unit_id', 'word_id', 'words', 
        'test_time', 'input_words', 'test_result');
    protected $insertFields = array('user_id', 'class_id', 'unit_id', 'word_id', 'words', 'test_time', 
        'input_words', 'test_result');
   
    
    /**
     * 插入测试结果
     * @param $unit_id int 单元id
     * @param $word_id int 单词id
     * @param $write_word string 输入的单词
     * @param $type_id int  类型 0单元测试 1听写 2默写
     * @param $option
     */
    public function insertTestResult($unit_id, $word_id, $write_word, $type_id = 0) {
        $word_model = D('Home/Word');
        $wrong_word_model = D('Home/WrongWords');
        $learn_word_model = D('Home/LearnWord');
        $course_word_model = D('Home/CourseWord');
        $where['unit_id'] = $unit_id;
        $where['word_id'] = $word_id;
        $course_word_info = $course_word_model->getCourseWordInfo($where);
        if (empty($course_word_info)) {
            return V(0, '本单元没有该单词');
        }
        //先删除原单词
        $where['type_id'] = $type_id;
        $this->where($where)->delete();
        unset($where);
        $where['id'] = $word_id;
        $words = $word_model->where($where)->getField('words');
        $data = array();
        $data['user_id'] = UID;
        $data['class_id'] = $course_word_info['class_id'];
        $data['unit_id'] = $unit_id;
        $data['word_id'] = $word_id;
        $data['words'] = $words;
        $data['test_time'] = NOW_TIME;
        $data['input_words'] = $write_word;
        $data['type_id'] = $type_id;
        
        $test_result = 0;
        if ($write_word == $words) {
            $test_result = 1;
            //删除已掌握的单词
            $learn_word_model->deleteLearnWord($unit_id, $word_id);
        } else {
            if ($type_id == 1 || $type_id == 2) {
                $wrong_word_model->insertWrongWords($course_word_info['class_id'], $unit_id, $word_id);
            }
            //记录需要学习的单词
            $learn_word_model->addLearnWord($unit_id, $word_id, $type_id);
        }
        $data['test_result'] = $test_result;
        $add_result = $this->add($data);
        if ($add_result !== false) {
            return V(1, '添加成功');
        }
    }

    /**
     * 更新测试结果
     * @param $data array
     * @param $unit_id int 单元id
     * @param $word_id int 单词id
     * @param $write_word string 输入的单词
     * @param $type_id int  类型 0单元测试 1听写 2默写
     * @param $option
     */
    public function updateTestResult($data, $unit_id, $word_id, $write_word, $type_id = 0) {
        $word_model = D('Home/Word');
        $where['id'] = $word_id;
        $words = $word_model->where($where)->getField('words');
        $test_result = 0;
        $result = 0;
 
        if ($type_id == 1) {
            $test_data['hearing_words'] = $write_word;
            if ($write_word == $words) {
                $test_result = 1;
            }
            $test_data['hearing_result'] = $test_result;
            if ($test_result == 1 && $data['writing_result'] == 1) {
                $result = 1;
            } 
        }
        elseif ($type_id == 2) {
            $test_data['writing_words'] = $write_word;
            if ($write_word == $words) {
                $test_result = 1;
            }
            $test_data['writing_result'] = $test_result;
            if ($test_result == 1 && $data['hearing_result'] == 1) {
                $result = 1;
            }
        }
        $test_data['test_result'] = $result;
        unset($where);
        $where['unit_id'] = $unit_id;
        $where['word_id'] = $word_id;
        $where['user_id'] = UID;
        $this->where($where)->save($test_data);
    }

    /**
     * 根据单元获取该单元测试情况
     * @param $unit_id int 单元id
     */
    public function getUnitTestResult($unit_id, $type_id) {
        $where['type_id'] = $type_id;
        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        $result_list = $this->field('result_id, test_result')->where($where)->select();
        $wrong_num = 0;
        $right_num = 0;
        $finish_num = 0;
        if (!empty($result_list)) {
            foreach ($result_list as $key => $value) {
                if ($value['test_result'] == 1) {
                    $right_num++;
                } else {
                    $wrong_num++;
                }
            }
            $finish_num = count($result_list);
        }
        
        return array('finish_num'=>$finish_num+1, 'right_num'=>$right_num, 'wrong_num'=>$wrong_num);
    }

    /**
     * 获取测试结果列表
     * @param $where array
     */
    public function getTestResultList($where, $fields) {
        if(is_null($fields)){
            $fields = $this->selectFields;
        }
        $list = $this->where($where)->field($fields)->order('result_id desc')->select();
        return $list;
    }

    public function getTestResultInfo($where, $fields) {
        if(is_null($fields)){
            $fields = $this->selectFields;
        }
        return $this->where($where)->field($fields)->find();
    }

    /**
     * 获取测试通过率/得分
     * @param $unit_id int 单元id
     * @param $all_word int 本单元单词总数
     */
    public function getPass($unit_id, $all_word) {
        $where['user_id'] = UID;
        $where['unit_id'] = $unit_id;
        $where['test_result'] = 1;
        $right_num = $this->where($where)->count();
        $score = ($right_num / $all_word) * 100;
        return round($score);
    }
    /**
     * 获取该学生测试通过率/得分(教师端)
     */
    public function getUserPass($unit_id, $all_word,$user_id) {
        $where['user_id'] = $user_id;
        $where['unit_id'] = $unit_id;
        $where['test_result'] = 1;
        $right_num = $this->where($where)->count();
        $score = ($right_num / $all_word) * 100;
        return round($score);
    }

    /*
     * 学情报告
     */
    public function getResult() {
        //所属学段
        $where['user_id'] = array('eq', UID);
        $period_id = M('User')->where($where)->getField('period_id');
        $class_id = M('CourseClass')->where(array('period_id'=>$period_id))->getField('id',true);
        $where['class_id'] = array('in', $class_id);
        $word_num = M('CourseUnit')->where($where)->sum('word_num');
        $data = $this->where($where)->field('result_id,test_result,test_time')->select();
        //时间段

        $t = 24*3600;
        $time = strtotime(time_format(NOW_TIME, 'Y-m-d'));

        $time1 =  $time- (7*24*3600);
        $time2 = $time1 + $t;
        $time3 = $time2 + $t;
        $time4 = $time3 + $t;
        $time5 = $time4 + $t;
        $time6 = $time5 + $t;
        $time7 = $time6 + $t;

        $arr = [];
        $arr[0]['total'] =0;
        $arr[0]['learn'] =0;
        $arr[0]['date_time'] = time_format($time1,'m/d');
        $arr[1]['total'] =0;
        $arr[1]['learn'] =0;
        $arr[1]['date_time'] = time_format($time2,'m/d');
        $arr[2]['total'] =0;
        $arr[2]['learn'] =0;
        $arr[2]['date_time'] = time_format($time3,'m/d');
        $arr[3]['total'] =0;
        $arr[3]['learn'] =0;
        $arr[3]['date_time'] = time_format($time4,'m/d');
        $arr[4]['total'] =0;
        $arr[4]['learn'] =0;
        $arr[4]['date_time'] = time_format($time5,'m/d');
        $arr[5]['total'] =0;
        $arr[5]['learn'] =0;
        $arr[5]['date_time'] = time_format($time6,'m/d');
        $arr[6]['total'] =0;
        $arr[6]['learn'] =0;
        $arr[6]['date_time'] = time_format($time7,'m/d');
        foreach ($data as $k=>$v) {
            $new[$v['test_result']][] = $v;
            
            if ($v['test_time'] > $time1 && $v['test_time'] <= $time2) {
                $arr[0]['total'] += 1;
                if ($v['test_result'] ==1) {
                    $arr[0]['learn'] += 1;
                }
            }
            
            if ($v['test_time'] > $time2 && $v['test_time'] <= $time3) {

                $arr[1]['total'] += 1;
                if ($v['test_result'] ==1) {
                    $arr[1]['learn'] += 1;
                }
            }
            
            if ($v['test_time'] > $time3 && $v['test_time'] <= $time4) {

                $arr[2]['total'] += 1;
                if ($v['test_result'] ==1) {
                    $arr[2]['learn'] += 1;
                }
            }
            
            if ($v['test_time'] > $time4 && $v['test_time'] <= $time5) {

                $arr[3]['total'] += 1;
                if ($v['test_result'] ==1) {
                    $arr[3]['learn'] += 1;
                }
            }
            
            if ($v['test_time'] > $time5 && $v['test_time'] <= $time6) {

                $arr[4]['total'] += 1;
                if ($v['test_result'] ==1) {
                    $arr[4]['learn'] += 1;
                }
            }
            
            if ($v['test_time'] > $time6 && $v['test_time'] <= $time7) {

                $arr[5]['total'] += 1;
                if ($v['test_result'] ==1) {
                    $arr[5]['learn'] += 1;
                }

            }
            if ($v['test_time'] > $time7) {
                
                $arr[6]['total'] += 1;
                if ($v['test_result'] ==1) {
                    $arr[6]['learn'] += 1;
                }

            }

        }
        $learn_word = count($new[0]);
        $error_word = count($new[1]);
        $new_word = $word_num -($learn_word + $error_word);
        $return['list'] = $arr;
        $return['learn_word'] = $learn_word ? $learn_word : 0;
        $return['error_word'] = $error_word ? $error_word : 0;
        $return['new_word'] = $new_word ? $new_word : 0;
        $return['word_num'] = $word_num ? $word_num : 0;
        return $return;
    }

    /**
     * 单元词汇
     * @param $where
     * @param string $field
     * @param string $order
     */
    public function getUnitWordByPage($where, $field='', $order='cw.id dsc') {
        if (!$field) {
            $field = 'cw.result_id,cw.test_result,cw.is_learn, w.*';
        }
        $count = $this->where($where)->count();
        $page = get_web_page($count, 10);
        $list = $this->alias('cw')
            ->join('__WORD__ w ON cw.word_id = w.id','left')
            ->field($field)
            ->where($where)
            ->limit($page['limit'])
            ->order($order)
            ->select();
        return array(
            'list'=>$list,
            'page'=>$page['page']
        );
    }

    /**
     * 获取标题数量
     * @param $where
     */
    public function getItemNum($where) {
        $count0 = M('CourseWord')->where($where)->count();
        $map1['is_learn'] = array('eq', 0);
        $map2['test_result'] = array('eq',0);
        $map3['test_result'] = array('eq',1);
        $count1 = $this->where($where)->where($map1)->count();
        $count2 = $this->where($where)->where($map2)->count();
        $count3 = $this->where($where)->where($map3)->count();

        return array($count0,$count1,$count2,$count3);
    }

    //获取单元下用户完成个数
    public function getTestCount($unit_id, $type_id) {
        $where['type_id'] = $type_id;
        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        return $this->where($where)->count('result_id');
    }
    
}