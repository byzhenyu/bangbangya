<?php
/**
 * Created by liuniukeji.com
 * 课程相关接口
*/
namespace Api\Controller;
use Common\Controller\ApiUserCommonController;
use Think\Verify;

class CourseApiController extends ApiUserCommonController{

    /**
     * 获取我的课程
     */
    public function getMyCourse() {
        $user_model = D('Home/User');
        $period_model = D('Admin/CoursePeriod');
        $class_model = D('Admin/CourseClass');
        $period_id = $user_model->getPeriod(UID);
        $where['id'] = $map['period_id'] = $period_id;
        $period_info = $period_model->getCoursePeriodInfo($where);
        if (empty($period_info)) {
            $this->apiReturn(V(0, '当前没有课程'));
        }
        $class_list = $class_model->getCourseClassList($map);
        $class_data = array();
        if (!empty($class_list)) {
            foreach ($class_list as $key => $value) {
                $class_data[$key]['id'] = $value['id'];
                $class_data[$key]['class_name'] = $value['class_name'];
                $class_data[$key]['progress'] = '0%';
            }
        }
        $data['period_name'] = $period_info['period_name'];
        $data['class_list'] = $class_data;
        $this->apiReturn(V(1, '获取课程', $data));
    }

    /**
     * 获取课程单元
     */
    public function getCourseUnit() {
        $unit_model = D('Admin/CourseUnit');
        $testCountModel = D('Home/TestCount');
        $class_id = I('class_id', 0);
        $class_id = 1;
        $where['class_id'] = $class_id;
        $data = $unit_model->getCourseUnitList($where);
        $unit_list = $data['info'];
        foreach ($unit_list as $key => $value) {
            $unit_list[$key]['is_learn'] = $testCountModel->checkUnitHasLearnd($value['id']);
        }
        $this->apiReturn(V(1, '单元列表', $unit_list));
    }

    /**
     * 获取单元测评单词内容
     */
    public function getTestWords() {
        $course_word_model = D('Home/CourseWord');
        $test_result_model = D('Home/UserTestResult');
        $testCountModel = D('Home/TestCount');
        $unit_id = I('unit_id', 0, 'intval');
        $count = I('count', 0, 'intval');
        $word_id = I('word_id', 0, 'intval'); //原单词，正确单词id
        $write_word = I('write_word', ''); //输入的单词
        $use_time = I('use_time', ''); //测试用时
        $type_id = I('type_id', 0, 'intval'); //类型 0单元测试 1听写 2默写
        $is_continue = I('is_continue', 0, 'intval'); //是否继续学习 1是 0否
     
        $checkIsTest = $testCountModel->checkIsTest($unit_id);
        if ($checkIsTest == 0 && ($type_id == 1 || $type_id == 2)) {
            $this->apiReturn(V(0, '请先完成学前测试'));
        } 
        if ($count > 0) {
            $add_result = $test_result_model->insertTestResult($unit_id, $word_id, $write_word, $type_id);            
            if ($add_result['status'] == 0) {
                $this->apiReturn($add_result);
            }
        }
        
        $info = $course_word_model->getTestWord($unit_id, $count, $use_time, $type_id);
        $this->apiReturn($info);
    }

    /**
     * 获取单元测试结果
     */
    public function getTestResult() {
        $testResultModel = D('Home/UserTestResult');
        $testCountModel = D('Home/TestCount');
        $unit_id = I('unit_id', 0, 'intval');
        $type_id = I('type_id', 0, 'intval');
        $where['user_id'] = UID;
        $where['unit_id'] = $unit_id;
        $where['type_id'] = $type_id;
        //获取测评统计
        $test_result = $testCountModel->getTestCountInfo($where, 'word_all_num, right_num, wrong_num, score, use_time');
        if (empty($test_result)) {
            $this->apiReturn(V(0, '还没有测试结果'));
        }
        //获取测评单词
        $test_word_list = $testResultModel->getTestResultList($where, 'result_id, words, input_words, test_result');
        $test_result['word_list'] = $test_word_list;
        $this->apiReturn(V(1, '测试结果列表', $test_result));
    }

    /**
     * 获取单元测试结果(教师端)
     * user_id 学生ID
     * unit_id 课程单元ID
     */
    public function getTestStudentResult() {
        $user_id = I('user_id', 0, 'intval');
        $unit_id = I('unit_id', 0, 'intval');
        if(!$user_id) $this->apiReturn(V(0, '学生ID不能为空！'));
        if(!$unit_id) $this->apiReturn(V(0, '课程单元ID不能为空！'));
        $CourseWordModel = D('Home/CourseWord');
        $testResultModel = D('Home/UserTestResult');
        $testCountModel = D('Home/TestCount');
        //获取测评统计
        $where1 = array('user_id'=>$user_id,'unit_id'=>$unit_id,'type_id'=>1);
        $where2 = array('user_id'=>$user_id,'unit_id'=>$unit_id,'type_id'=>2);
        $field = 'right_num, wrong_num, score, use_time';
        $test_result1 = $testCountModel->getTestCountInfo($where1, $field);
        $test_result2 = $testCountModel->getTestCountInfo($where2, $field);
        if (empty($test_result1)) {
            $this->apiReturn(V(0, '还没有测试结果'));
        }
        //获取测评单词 1,2
        $where3 = array('user_id'=>$user_id,'unit_id'=>$unit_id,'type_id'=>array('neq',0));
        $field1 = 'result_id,word_id, words, input_words, test_result,type_id';
        $test_word_list = $testResultModel->getTestResultList($where3, $field1);
        foreach($test_word_list as $key => $val){
            $arr[$val['words']][] = $val;
        }

        $daa = array();
        $right_num = 0;//正确数
        foreach($arr as $key=>$val){
            $aa['result_id'] = $val[0]['result_id'];
            $aa['word_id'] = $val[0]['word_id'];
            $aa['words'] = $val[0]['words'];
            $aa['input_words'] = $val[0]['input_words'];
            $aa['test_result'] = $val[0]['test_result'];
            $aa['type_id'] = $val[0]['type_id'];

            $aa['result_id1'] = $val[1]['result_id'];
            $aa['word_id1'] = $val[1]['word_id'];
            $aa['words1'] = $val[1]['words'];
            $aa['input_words1'] = $val[1]['input_words'];
            $aa['test_result1'] = $val[1]['test_result'];
            $aa['type_id1'] = $val[1]['type_id'];
            array_push($daa,$aa);
            if($val[0]['test_result'] == 1 && $val[1]['test_result'] == 1){
                $right_num++;
            }
        }
        //p($daa);
        $word_all_num = $CourseWordModel->where(array('unit_id'=>$unit_id))->count();//本单元词汇量
        $wrong_num = $word_all_num - $right_num;//错误数
        $score = round(($right_num / $word_all_num) * 100);
        $use_time1 = $test_result1['use_time'];//听力时间
        $use_time2 = $test_result2['use_time'];//默写时间
        $result = array('word_all_num'=>$word_all_num,'use_time1'=>$use_time1,'right_num'=>$right_num,'wrong_num'=>$wrong_num,'score'=>$score,'use_time2'=>$use_time2,'word_list'=>$daa);
        $this->apiReturn(V(1, '测试结果列表', $result));
    }

    /**
     * 获取单元闯关单词内容
     */
    public function getThroughWords() {
        $courseWordModel = D('Home/CourseWord');
        $hearingPassModel = D('Home/HearingPass');
        $unit_id = I('unit_id', 0, 'intval');
        $count = I('count', 0, 'intval');
        $word_id = I('word_id', 0, 'intval'); //正确单词id
        $use_time = I('use_time', 0); //用时，单位s
        $type_id = I('type_id', 0, 'intval'); //类型 0新手 1高手 2学霸
        $result = I('result', 1, 'intval'); //选择结果 0错误 1正确

        if ($count== 0) { // 删除单词
            $hearingPassModel->where(array('unit_id'=>$unit_id, 'user_id'=>UID))->delete();
        } else { //插入单词测试结果
            $hearingPassModel->insertHearingPass($unit_id, $word_id, $use_time, $result, $type_id);
        }
        $info = $courseWordModel->getHearingPassWord($unit_id, $count, $type_id);

        $this->apiReturn($info);
    }

    /**
     * 获取单元闯关结果
     */
    public function getThroughResult() {
        $hearingPassModel = D('Home/HearingPass');
        $courseWordModel = D('Home/CourseWord');
        $unit_id = I('unit_id', 0, 'intval');
        $pass_score = C('THROUGH_PASS_SCORE'); //合格分数
        $where['unit_id'] = $unit_id;
        $word_count = $courseWordModel->where($where)->count();
        $data['test_score'] = $hearingPassModel->getPass($unit_id, $word_count); //测试成绩
        $data['is_qualified'] = 0;
        if ($data['test_score'] >= $pass_score) {
            $data['is_qualified'] = 1;
        }
        $this->apiReturn(V(1, '测试结果', $data));
    }

    /**
     * 获取单元闯关是否闯关成功
     */
    public function getThroughStatus() {
        $hearingPassModel = D('Home/HearingPass');
        $courseWordModel = D('Home/CourseWord');
        $unit_id = I('unit_id', 0, 'intval');
        $pass_score = C('THROUGH_PASS_SCORE'); //合格分数
        $where['unit_id'] = $unit_id;
        //获取单词总数
        $word_count = $courseWordModel->where($where)->count();
        //新手成绩
        $new_score = $hearingPassModel->getPass($unit_id, 0, $word_count); 
        $killer_score = $hearingPassModel->getPass($unit_id, 1, $word_count); 
        $straight_score = $hearingPassModel->getPass($unit_id, 2, $word_count); 
        $data['new_test_score'] = 0;
        $data['killer_test_score'] = 0;
        $data['straight_test_score'] = 0;
        if ($new_score >= $pass_score) {
            $data['new_test_score'] = 1;
        }
        if ($killer_score >= $pass_score) {
            $data['killer_test_score'] = 1;
        }
        if ($straight_score >= $pass_score) {
            $data['straight_test_score'] = 1;
        }
        $this->apiReturn(V(1, '测试结果', $data));
    }

    /**
     * 获取测试记录
     */
    public function getTestRecordsList() {
        $testCountModel = D('Home/TestCount');
        $userModel = D('Home/User');
        $class_list = $userModel->getPeriodClass(UID);
        $where['user_id'] = UID;
        $list = $testCountModel->getTestListAndUnitName($where);
        $list = $list['list'];
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $list[$key]['test_time'] = time_format($value['test_time'], 'm月d日 H:i');
                $list[$key]['course_name'] = $class_list[$value['class_id']].$value['unit'];
            }
        }
        $this->apiReturn(V(1, '测试记录', $list));
    }

    /**
     * 获取智能复习
     */
    public function getReview() {
        $reviewModel = D('Home/Review');
        $unit_id = I('unit_id', 0, 'intval');
        $count = I('count', 0, 'intval');
        $word_id = I('word_id', 0, 'intval'); //正确单词id
        $result = I('result', 1, 'intval'); //选择结果 0错误 1正确
        $p = I('p', 1); //分组

        //获取已学习单词个数
        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        $all_learn_num = $reviewModel->where($where)->count('id'); //所有已学习单词个数
        $where['group'] = $p;
        $has_learn_num = $reviewModel->where($where)->count('id'); //本组已学习单词个数
        if ($count > 0) { 
            $reviewModel->insertReview($unit_id, $word_id, $result, $p);
            $has_learn_num += 1;
            $all_learn_num += 1;
        }
        $info = $reviewModel->getReview($unit_id, $has_learn_num, $all_learn_num);
        $this->apiReturn($info);
    }

    /**
     * 获取智能复习已学组数
     */
    public function getReviewGroup() {
        $reviewModel = D('Home/Review');
        $userModel = D('Home/User');
        $unit_id = I('unit_id', 0, 'intval');
        $review_number = $userModel->getLearnSetting();
        $review_number = $review_number['intelligent_number'];
        $where['unit_id'] = $unit_id;
        $where['user_id'] = UID;
        $all_learn_num = $reviewModel->where($where)->count('id'); //所有已学习单词个数
        $data['group'] = ceil($all_learn_num / $review_number);
        if ($all_learn_num == 0) {
            $data['group'] = 1;
        }
        $this->apiReturn(V(1, '已学组数', $data));
    }

    /**
     * @desc 获取课程/单元下的单词熟识程度
     */
    public function userCourseInfo(){
        $course_id = I('course_id', 0, 'intval');
        $unit_id = I('unit_id', 0, 'intval');
        $where = array('user_id' => UID);
        if(!$course_id) $this->apiReturn(V(0, '请传入合适的课程id'));
        if(!$unit_id) $this->apiReturn(V(0, '请传入合适的单元id'));
        $model = D('Admin/UserCourse');
        $where['class_id'] = $course_id;
        $where['unit_id'] = $unit_id;
        $result = $model->userCourseInfo($where);
        if($result){
            $this->apiReturn(V(1, '学习进度获取成功！', $result));
        }
        else{
            $this->apiReturn(V(0, '学习进度获取失败！'));
        }
    }

    /**
     * @desc 用户单词熟识度排名
     * @param type int 1、年度排名  2、当月排名  3、今日排名
     */
    public function rankingUserFamiliar(){
        $type = I('type', 1, 'intval');
        if(!in_array($type, array(1,2,3))) $this->apiReturn(V(0, '请传入合法的排名类型！'));
        $timeArray = time_rand($type);
        $ranking = 10;
        $where = array('test_result' => 1, 'test_time' => array('between', array($timeArray['start'], $timeArray['end'])));
        $rankModel = D('Admin/UserTestResult');
        $currentRowNumber = $rankModel->getCurrentRowNumber($type);
        $currentRowNumber = revRowNumber($currentRowNumber);
        $currentWord = $rankModel->statisticWordsNum(array('user_id'=>UID));
        $rankList = $rankModel->rankingList($where, false, $ranking);
        $return = array('current_row_number' => $currentRowNumber, 'current_word'=>$currentWord, 'ranking' => $rankList);
        $this->apiReturn(V(1, '排行数据获取成功！', $return));
    }

    public function compareTest(){
        $trueWords = I('truewords');
        $wrongWords = I('wrongwords');
        $compare = compareUnitWords($trueWords, $wrongWords);
        if(is_array($compare)) $this->apiReturn(V(1, '比较成功！', $compare));
        $this->apiReturn(V(0, '无须比较！'));
    }

    /**
     *  单元词汇
     *  id 单元id
     *  $item_id 类型 0全部 1未学 2生词 3熟词
     * $order_type 排序 desc asc
     */
    public function getUnitWord() {
        $id = I('id', 0, 'intval');
        $order_type = I('order_type', 'asc');
        $where['unit_id'] = array('eq', $id);
        $item_id = I('item_id', 0, 'intval');
        switch ($item_id) {
            case 0:
                $order = 'cw.sort '.$order_type;
                $model = D('Home/CourseWord');
                break;
            case 1:
                $where['is_learn'] = array('eq', 0);
                $where['user_id']= array('eq', UID);
                $order = 'test_time '.$order_type;
                $model = D('Home/UserTestResult');
                break;
            case 2:
                $where['test_result'] = array('eq', 0);
                $where['user_id']= array('eq', UID);
                $order = 'test_time '.$order_type;
                $model = D('Home/UserTestResult');
                break;
            case 3:
                $where['test_result'] = array('eq', 1);
                $where['user_id']= array('eq', UID);
                $order = 'test_time '.$order_type;
                $model = D('Home/UserTestResult');
                break;
            default:
                $this->apiReturn(V(0, '类型有误'));
        }
        $data = $model->getUnitWordByPage($where, '', $order);

        $this->apiReturn(V(1 ,'单元词汇', $data['list']));
    }

    /**
     * 错题集
     */
    public function getWrongWords() {
        $item_id = I('item_id', 0, 'intval');
        $where['s.user_id'] = array('eq', UID);
        $order_type = I('order_type', 'desc');
        switch ($item_id) {
            case 0:
                break;
            case 1:
                $begin = strtotime('-1 days');
                $end = NOW_TIME;
                $where['s.add_time'] = array('between', array($begin, $end));
                break;
            case 2:
                $begin = strtotime('-7 days');
                $end = NOW_TIME;
                $where['s.add_time'] = array('between', array($begin, $end));
                break;
            case 3:
                $begin = strtotime('-15 days');
                $end = NOW_TIME;
                $where['s.add_time'] = array('between', array($begin, $end));
                break;
        }
        $order = 's.add_time '.$order_type;
        $data= D('Home/WrongWords')->getWrongWords($where, '', $order);
        $this->apiReturn(V(1, '错题集', $data));
    }

    /**
     * id 单元id
     * item_type 类型 1 错题集 0单元词汇
     * 获取标题单词数量
     */
    public function getItemNum() {
        $item_type = I('item_type', 0, 'intval');
        $where = [];
        $where['user_id'] = array('eq', UID);
        if ($item_type == 1) { //错题集
            $model = D('Home/WrongWords');
        } else { //单元词汇
            $id = I('id', 0, 'intval');
            if (!$id) {
                $this->apiReturn(V(0,'单元id缺失'));
            }
            $where['unit_id'] = array('eq', $id);
            $model = D('Home/UserTestResult');
        }
        $data = $model->getItemNum($where);
        $this->apiReturn(V(1, '标题数量', $data));
    }
}