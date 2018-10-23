<?php
/**
 * @Description    学生端班级作业接口
 * @Author         <527459901@qq.com>
 * @Date           2018/10/17
 */
namespace Api\Controller;
use Common\Controller\ApiUserCommonController;
use Think\Verify;
class WorkApiController extends ApiUserCommonController{

    /**
     * @desc 班级作业列表（待完成）
     * @param p           页数
     */
    public function HomeworkListPageNot() {
        $UserModel = D('Home/User');
        $WorkModel = D('Home/Homework');
        $HomeworkCountModel = D('Home/HomeworkCount');
        $where['user_id'] = UID;
        $grade_id = $UserModel->getUserField($where,'grade_id');//获取班级ID
//        $homework_id = $WorkModel->where(array('grade_id'=>$grade_id))->getField('id',true);//获取作业ID集合
//        if(empty($homework_id)) $this->apiReturn(V(1, '未获取到作业信息！'));

        $where1['h.grade_id'] = $grade_id;
        $field1 = 'h.id,h.create_time,h.end_time,c.class_name,u.unit';
        $list = $WorkModel->getHomeworkListPageNot($where1,$field1);
        if($list['list']){
            foreach($list['list'] as $key=>$val){
                $val['create_time'] = date('m月d日',$val['create_time']);
                $val['end_time'] = date('m月d日',$val['end_time']);
                $where2['homework_id'] = $val['id'];
                $where2['user_id'] = UID;
                $count = $HomeworkCountModel->where($where2)->count();
                if($count <2){
                    $arr[] = $val;
                }
            }
            $this->apiReturn(V(1, '班级作业列表！',$arr));
        }
        else{
            $this->ajaxReturn(V(1, '未查询到!'));
        }
    }

    /**
     * @desc 班级作业列表（已完成）
     * @param p           页数
     */
    public function HomeworkListPageResult() {
        $UserModel = D('Home/User');
        $WorkModel = D('Home/Homework');
        $HomeworkCountModel = D('Home/HomeworkCount');
        $where['user_id'] = UID;
        $grade_id = $UserModel->getUserField($where,'grade_id');//获取班级ID

        $where1['h.grade_id'] = $grade_id;
        $field1 = 'h.id as homework_id,c.class_name,u.unit';
        $list = $WorkModel->getHomeworkListPageNot($where1,$field1);
        if($list['list']){
            foreach($list['list'] as $key=>$val){
                $where2['homework_id'] = $val['homework_id'];
                $where2['user_id'] = UID;
                $countlist = $HomeworkCountModel->getHomeworkCountList($where2,'right_num,wrong_num,score,is_pass,test_time');
                $count = count($countlist);
                if($count >= 2){
                    if($countlist[0]['right_num'] > $countlist[1]['right_num']){
                        $list['list'][$key]['right_num'] = $countlist[1]['right_num'];
                    }
                    else{
                        $list['list'][$key]['right_num'] = $countlist[0]['right_num'];
                    }
                    if($countlist[0]['wrong_num'] > $countlist[1]['wrong_num']){
                        $list['list'][$key]['wrong_num'] = $countlist[0]['wrong_num'];
                    }
                    else{
                        $list['list'][$key]['wrong_num'] = $countlist[1]['wrong_num'];
                    }
                    if($countlist[0]['test_time'] > $countlist[1]['test_time']){
                        $list['list'][$key]['test_time'] = date('m月d日',$countlist[0]['test_time']);
                    }
                    else{
                        $list['list'][$key]['test_time'] = date('m月d日',$countlist[1]['test_time']);
                    }
                    if($countlist[0]['is_pass'] == 1 && $countlist[1]['is_pass'] == 1){
                        $list['list'][$key]['is_pass'] = '1';
                    }
                    else{
                        $list['list'][$key]['is_pass'] = '0';
                    }
                    $list['list'][$key]['score'] = floor(($countlist[0]['score'] + $countlist[1]['score']) / 2);
                    $arr[] = $list['list'][$key];
                }
            }
            $this->apiReturn(V(1, '班级作业列表！',$arr));
        }
        else{
            $this->ajaxReturn(V(1, '未查询到!'));
        }


        /*$data['h.homework_id'] = array('in',$homework_id);
        $data['h.user_id'] = UID;
        $field2 = 'h.id,h.homework_id,h.right_num,h.wrong_num,h.score,h.is_pass,h.test_time,c.class_name,u.unit';
        $list = $HomeworkCountModel->getHomeworkCountListByPageResult($data,$field2);
        if($list['list']){
            foreach($list['list'] as $key=>$val){
                $list['list'][$key]['test_time'] = date('m月d日',$val['test_time']);
            }
            $this->apiReturn(V(1, '班级作业列表！',$list['list']));
        }
        else{
            $this->ajaxReturn(V(1, '未查询到!'));
        }*/
    }

    /**
     * @desc 班级排名
     * @param grade_id    班级ID
     * @param homework_id 作业ID
     **/
    public function rankingUser(){
        $grade_id = I('post.grade_id',0,'intval');
        $homework_id = I('post.homework_id',0,'intval');
        if(!$grade_id) $this->apiReturn(V(0,'班级ID不能为空！'));
        if(!$homework_id) $this->apiReturn(V(0,'作业ID不能为空！'));

        $UserModel = D('Home/User');
        $HomeworkCountModel = D('Home/HomeworkCount');
        $where['grade_id'] = $grade_id;
        $where['homework_id'] = $homework_id;
        $list = $UserModel->getUsersListBy($where,'user_id,truename,head_pic');
        if($list){
            foreach($list as $key=>$val){
                $where2['homework_id'] = $homework_id;
                $where2['user_id'] = $val['user_id'];
                $countlist = $HomeworkCountModel->getHomeworkCountList($where2,'id,score');
                $count = count($countlist);
                if($count >= 2){
                    $list[$key]['score'] = floor(($countlist[0]['score'] + $countlist[1]['score']) / 2);
                    $arr1[] = $list[$key];
                }
            }
            $arr = list_sort_by($arr1,'score','desc');
            $data = array();
            foreach($arr as $key=>$val){
                if(!$arr[$key]['truename']) $arr[$key]['truename']='';
                if(!$arr[$key]['head_pic']) $arr[$key]['head_pic']='';
                if($arr[$key]['user_id'] == UID){
                    $data = array('number'=>$key+1,'user_id'=>$arr[$key]['user_id'],'truename'=>$arr[$key]['truename'],'head_pic'=>$arr[$key]['head_pic'],'score'=>$arr[$key]['score']);
                }
            }
            $return = array('info' => $data,'ranking' => $arr);
            $this->apiReturn(V(1, '排行数据获取成功！', $return));
        }
        else{
            $this->ajaxReturn(V(1, '未查询到!'));
        }
    }

    /**
     * @desc 获取班级作业测试结果
     * @param homework_id 作业ID
     **/
    public function getHomeworkResult(){
        $homework_id = I('homework_id', 0, 'intval');
        if(!$homework_id) $this->apiReturn(V(0, '班级作业ID不能为空！'));
        $CourseWordModel = D('Home/CourseWord');
        $HomeworkModel = D('Home/Homework');
        $HomeworkCountModel = D('Home/HomeworkCount');
        $HomeworkResultModel = D('Home/HomeworkResult');
        //获取测评统计
        $where1 = array('user_id'=>UID,'homework_id'=>$homework_id,'type_id'=>1);
        $where2 = array('user_id'=>UID,'homework_id'=>$homework_id,'type_id'=>2);
        $field = 'right_num, wrong_num, score, use_time';
        $test_result1 = $HomeworkCountModel->getHomeworkCountInfo($where1, $field);
        $test_result2 = $HomeworkCountModel->getHomeworkCountInfo($where2, $field);
        if (empty($test_result1) && empty($test_result2)) {
            $this->apiReturn(V(0, '还没有测试结果'));
        }
        //获取测评单词 1,2
        $where3 = array('user_id'=>UID,'homework_id'=>$homework_id);
        $field1 = 'result_id,word_id, words, input_words, test_result,type_id';
        $test_word_list = $HomeworkResultModel->getHomeworkResultList($where3, $field1);
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
        $unit_id = $HomeworkModel->getHomeworkField(array('id'=>$homework_id),'unit_id');
        $word_all_num = $CourseWordModel->where(array('unit_id'=>$unit_id))->count();//本单元词汇量
        $wrong_num = $word_all_num - $right_num;//错误数
        $score = round(($right_num / $word_all_num) * 100);
        $use_time1 = $test_result1['use_time'];//听力时间
        $use_time2 = $test_result2['use_time'];//默写时间
        $result = array('word_all_num'=>$word_all_num,'use_time1'=>$use_time1,'right_num'=>$right_num,'wrong_num'=>$wrong_num,'score'=>$score,'use_time2'=>$use_time2,'word_list'=>$daa);
        $this->apiReturn(V(1, '班级作业测试结果列表', $result));
    }
}