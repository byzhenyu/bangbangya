<?php
/**
 * Created by liuniukeji.com
 * 五步学习法
*/
namespace Api\Controller;
use Common\Controller\ApiUserCommonController;
use Think\Verify;

class LearnApiController extends ApiUserCommonController{

    /**
     * 单词纠错
     */
    public function correctionWord() {
        $word_id = I('word_id', 0, 'intval');
        $type_id = I('type_id', 0, 'intval');
        $correct_writing = I('correct_writing', '');
        $data['user_id'] = UID;
        $data['word_id'] = $word_id;
        $data['type'] = $type_id;
        $data['correct_writing'] = $correct_writing;
        $data['add_time'] = NOW_TIME;
        $result = M('word_correction')->add($data);
        $this->apiReturn(V(1, '感谢您的纠正'));
    }

    /**
     * 跟读
     */
    public function readWord() {
        $learnWordModel = D('Home/LearnWord');
        $unit_id = I('unit_id', 0, 'intval');
        $count = I('count', 0, 'intval');
        $word_id = I('word_id', 0, 'intval'); 
        $type_id = I('type_id', 0, 'intval'); //0跟读 1记忆 2回忆 3听写 4默写
        $is_continue = I('is_continue', 0, 'intval'); //是否继续学习 1是 0否
        $up_num = I('up_num', 0, 'intval'); //上一个



        $check = $learnWordModel->checkIsTest($unit_id);
        if ($check['status'] == 0) {
            $this->apiReturn($check);
        } 
        $word_num = $check;
        if ($count > 0) { 
            $learnWordModel->updateWord($unit_id, $word_id, $type_id);
        }
        //获取已学习单词个数
        $where['unit_id'] = $unit_id;
        $where['is_read'] = 1;
        $all_learn_num = $learnWordModel->getLearnUnitWordCount($where);
        $all_learn_num = $all_learn_num - $up_num;
        unset($where);
        //如果是第一次进入判断是不是已经学习完
        if ($is_continue == 1) {
            $where['unit_id'] = $unit_id;
            $where['user_id'] = UID;
            $learnWordModel->where($where)->setField('is_read', 0);
        }
        $info = $learnWordModel->getLearn($unit_id, $all_learn_num, $word_num, $type_id, $word_id, $up_num);

        $this->apiReturn($info);
    }

    /**
     * 记忆/回忆
     */
    public function memoryWord() {
        $learnWordModel = D('Home/LearnWord');
        $unit_id = I('unit_id', 0, 'intval');
        $count = I('count', 0, 'intval');
        $word_id = I('word_id', 0, 'intval'); 
        $type_id = I('type_id', 0, 'intval'); //0跟读 1记忆 2回忆 3听写 4默写
        $is_continue = I('is_continue', 0, 'intval'); //是否继续学习 1是 0否

        $check = $learnWordModel->checkIsTest($unit_id);
        if ($check['status'] == 0) {
            $this->apiReturn($check);
        } 
        $word_num = $check;
        $where['unit_id'] = $unit_id;
        //获取已学习单词个数
        if ($type_id == 1) {
            $where['is_memory'] = 1;
        }
        elseif ($type_id == 2) {
            $where['is_recall'] = 1;
        }
        
        if ($count > 0) { 
            $learnWordModel->updateWord($unit_id, $word_id, $type_id);
        }
        $all_learn_num = $learnWordModel->getLearnUnitWordCount($where);
        //如果是第一次进入判断是不是已经学习完
        if ($count == 0) {
            if ($all_learn_num >= $word_num) {
                $where['unit_id'] = $unit_id;
                $where['user_id'] = UID;
                if ($type_id == 1) {
                    $update_field['is_memory'] = 0;
                    $update_field['memory_tags'] = 0;
                    $update_field['memory_test'] = 0;
                }
                elseif ($type_id == 2) {
                    $update_field['is_recall'] = 0;
                    $update_field['recall_tags'] = 0;
                    $update_field['recall_test'] = 0;
                }
                $learnWordModel->where($where)->save($update_field);
            }
        }
        $info = $learnWordModel->getLearn($unit_id, $all_learn_num, $word_num, $type_id, $is_continue);

        $this->apiReturn($info);
    }

    /**
     * 听写/默写
     */
    public function writeWord() {
        $learnWordModel = D('Home/LearnWord');
        $unit_id = I('unit_id', 0, 'intval');
        $count = I('count', 0, 'intval');
        $word_id = I('word_id', 0, 'intval'); 
        $type_id = I('type_id', 0, 'intval'); //0跟读 1记忆 2回忆 3听写 4默写
        $is_continue = I('is_continue', 0, 'intval'); //是否继续学习 1是 0否

        $check = $learnWordModel->checkIsTest($unit_id);
        if ($check['status'] == 0) {
            $this->apiReturn($check);
        } 
        $word_num = $check;
        $where['unit_id'] = $unit_id;
        //获取已学习单词个数
        if ($type_id == 3) {
            $where['is_hearing'] = 1;
        }
        elseif ($type_id == 4) {
            $where['is_writing'] = 1;
        }
        
        if ($count > 0) { 
            $learnWordModel->updateWord($unit_id, $word_id, $type_id);
        }
        $all_learn_num = $learnWordModel->getLearnUnitWordCount($where);
        //如果是第一次进入判断是不是已经学习完
        if ($count == 0) {
            if ($all_learn_num >= $word_num) {
                $where['unit_id'] = $unit_id;
                $where['user_id'] = UID;
                if ($type_id == 3) {
                    $update_field['is_hearing'] = 0;
                }
                elseif ($type_id == 4) {
                    $update_field['is_writing'] = 0;
                }
                $learnWordModel->where($where)->save($update_field);
            }
        }
        $info = $learnWordModel->getLearn($unit_id, $all_learn_num, $word_num, $type_id, $is_continue);

        $this->apiReturn($info);
    }

    /**
     * 添加/取消标注
     */
    public function addTags() {
        $learnWordModel = D('Home/LearnWord');
        $is_tags = I('is_tags', 0, 'intval');
        $unit_id = I('unit_id', 0, 'intval');
        $word_id = I('word_id', 0, 'intval');
        $type_id = I('type_id', 0, 'intval'); 
        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        $where['word_id'] = $word_id;
        if ($type_id == 1) {
            $data['memory_tags'] = $is_tags;
        }
        elseif ($type_id == 2) {
            $data['recall_tags'] = $is_tags;
        }
        $learnWordModel->where($where)->save($data);
        $this->apiReturn(V(1, '操作成功'));
    }

    /**
     * 获取智能记忆测试
     */
    public function getMemoryWordTest() {
        $learnWordModel = D('Home/LearnWord');
        $unit_id = I('unit_id', 0, 'intval');
        $count = I('count', 0, 'intval');
        $word_id = I('word_id', 0, 'intval'); 
        $type_id = I('type_id', 1, 'intval'); //
        $result = I('result', ''); //选择结果 0错误 1正确
        $is_continue = I('is_continue', 0, 'intval'); //是否继续学习 1是 0否


        $check = $learnWordModel->checkIsTest($unit_id);
        if ($check['status'] == 0) {
            $this->apiReturn($check);
        } 
        $word_num = $check;
        $where['unit_id'] = $unit_id;
        //获取已学习单词个数
        $where_field = array(1 => 'memory_test', 2 => 'recall_test');
        $where[$where_field[$type_id]] = 1;
        
        if ($count > 0) { 
            if ($result == '') {
                $this->apiReturn(V(0, '请选择答案'));
            }
            $learnWordModel->updateWordTest($unit_id, $word_id, $type_id, $result);
        }
        $all_learn_num = $learnWordModel->getLearnUnitWordCount($where);
        if ($is_continue == 1 && $all_learn_num >= $word_num ) {
            $reset_field = array(1 => 'memory_test', 2 => 'recall_test');
            $where['unit_id'] = $unit_id;
            $where['user_id'] = UID;
            $learnWordModel->where($where)->setField($reset_field[$type_id], 0);
        }
        $info = $learnWordModel->getLearnTest($unit_id, $all_learn_num, $word_num, $type_id, $is_continue);
        $this->apiReturn($info);
    }

    /**
     * 首页数据
     */
    public function getUnitHomeData() {

        $learnWordModel = D('Home/LearnWord');
        $wordModel = D('Admin/CourseWord');
        $testResultModel = D('Home/UserTestResult');
        $testCountModel = D('Home/TestCount');
        $unit_id = I('unit_id', 0, 'intval');
        $where['unit_id'] = $map['unit_id'] = $unit_id;
        //获取单元单词总数
        $unit_word_count = $wordModel->where($where)->count('id');
        $data['unit_word_count'] = $unit_word_count;
        //已测评单词总数
        $has_test_count = $testResultModel->where($where)->where(array('type_id'=>0, 'user_id'=>UID))->count('result_id');
        $data['has_test_count'] = $has_test_count;
        //需要学习单词总数
        $learn_count = $learnWordModel->getLearnUnitWordCount($where);
        $data['is_learn_word'] = 1;
        if ($learn_count == 0) {
            $data['is_learn_word'] = 0;
        }
        //已学习单词总数
        $where['is_read'] = 1;
        $where['is_memory'] = 1;
        $where['is_recall'] = 1;
        $where['is_hearing'] = 1;
        $where['is_writing'] = 1;
        $has_learn_count = $learnWordModel->getLearnUnitWordCount($where);
        $data['need_learn_count'] = $learn_count;
        $data['has_learn_count'] = $has_learn_count;
        unset($where);
        //获取熟词个数
        $map['test_result'] = 1;
        $map['user_id'] = UID;
        $map['type_id'] = 0;
        $right_count = $testResultModel->where($map)->count('result_id');
        $data['right_count'] = $right_count;
        //今日熟词个数
        $startTime = strtotime(date('Y-m-d').'00:00:00');
        $endTime = strtotime(date('Y-m-d').'23:59:59');
        $map['test_time'] = array('between', array($startTime, $endTime));
        $today_right_count = $testResultModel->where($map)->count('result_id');
        $data['today_right_count'] = $today_right_count;
        //学习时间
        $data['learn_minute'] = 0;
        //是否完成了学前测试
        $test_count = $testCountModel->checkIsTest($unit_id);
        $data['is_tested'] = $test_count > 0 ? 1 : 0;
        unset($where);

        //获取学习情况
        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        $learn_info = $learnWordModel->field('sum(is_read) as is_read, sum(is_memory) as is_memory, sum(is_recall) as is_recall, sum(is_hearing) as is_hearing')->where($where)->find();
        $data['can_memory'] = intval($learn_info['is_read']) > 0 ? 1 : 0;
        $data['can_recall'] = intval($learn_info['is_memory']) > 0 ? 1 : 0;
        $data['can_hearing'] = intval($learn_info['is_recall']) > 0 ? 1 : 0;
        $data['can_writing'] = intval($learn_info['is_hearing']) > 0 ? 1 : 0;
        $this->apiReturn(V(1, '单元首页数据', $data));
    }

}