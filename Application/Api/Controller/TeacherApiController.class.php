<?php
/**
 * @Description    教师端接口
 * @Author         <527459901@qq.com>
 * @Date           2018/10/09
 */
namespace Api\Controller;
use Common\Controller\ApiUserCommonController;
use Think\Verify;
class TeacherApiController extends ApiUserCommonController{

    /**
     * @desc 教师端班级列表+作业列表
     * @param p           页数
     **/
    public function gradeList(){
        $where['teacher_id'] = TEACHER_ID;
        $where['status'] = 1;
        $where['disabled'] = 1;
        $field = 'id,teacher_id,grade_code,grade_name';
        $GradeModel = D('Home/Grade');
        $list = $GradeModel->getGradeLists($where,$field);
        $this->apiReturn($list);
    }

    /**
     * @desc 教师端班级详情
     * @param grade_id    班级ID
     **/
    public function gradeInfo(){
        $grade_id = I('post.grade_id',0, 'intval');
        if(!$grade_id) $this->apiReturn(V(0, '班级ID不能为空！'));

        $where['teacher_id'] = TEACHER_ID;
        $where['id'] = $grade_id;
        $field = 'id,teacher_id,grade_code,grade_name,period_id,student_number';
        $GradeModel = D('Home/Grade');
        $info = $GradeModel->getGradeInfo($where,$field);
        if($info['status'] == '0' || $info['disabled'] == '0') $this->apiReturn(V(0, '班级已被解散或被禁用！'));
        if($info){
            $info['period_name'] = M('course_period')->where(array('id'=>$info['period_id']))->getField('period_name');
            $this->apiReturn(V(1, '班级详情',$info));
        }
        else{
            $this->apiReturn(V(1, '未查询到'));
        }
    }

    /**
     * @desc 教师端修改班级名称
     * @param grade_id    班级ID
     * @param grade_name  班级名称
     **/
    public function saveGrade(){
        $grade_id = I('post.grade_id',0, 'intval');
        $grade_name = I('post.grade_name','', 'trim');
        if(!$grade_id) $this->apiReturn(V(0, '班级ID不能为空！'));
        if(!$grade_name) $this->apiReturn(V(0, '班级名称不能为空！'));

        $UserModel = D('Home/User');
        $GradeModel = D('Home/Grade');
        if($GradeModel->checkGradeName($grade_name) == false)$this->apiReturn(V(0, '班级名称不能重复！'));
        $where['teacher_id'] = TEACHER_ID;
        $where['id'] = $grade_id;
        $data['grade_name'] = $grade_name;
        $save = $GradeModel->setGrade($where,$data);
        $where1['grade_id'] = $grade_id;
        $data1['grade'] = $grade_name;
        $save1 = $UserModel->setuser($where1,$data1);
        if($save !==false && $save1 !==false){
            $this->apiReturn(V(1, '修改成功！'));
        }
        else{
            $this->apiReturn(V(0, '修改失败！'));
        }
    }

    /**
     * @desc 教师端解散班级
     * @param grade_id    班级ID
     **/
    public function disbandGrade(){
        $grade_id = I('post.grade_id',0, 'intval');
        if(!$grade_id) $this->apiReturn(V(0, '班级ID不能为空！'));

        $GradeModel = D('Home/Grade');
        $where['teacher_id'] = TEACHER_ID;
        $where['id'] = $grade_id;
        $gradeinfo = $GradeModel->getGradeInfo($where);
        if(!$gradeinfo) $this->apiReturn(V(0, '班级不存在或该班级不是您的班级！'));
        if($gradeinfo['status'] == 0) $this->apiReturn(V(0, '班级已被解散！'));
        $data['status'] = 0;
        $save = $GradeModel->setGrade($where,$data);
        if($save){
            $this->apiReturn(V(1, '解散班级成功！'));
        }
        else{
            $this->apiReturn(V(0, '解散班级失败！'));
        }
    }

    /**
     * @desc 教师端学生列表
     * @param grade_id    班级ID
     * @param p 页数
     **/
    public function studentList(){
        $grade_id = I('post.grade_id',0, 'intval');
        if(!$grade_id) $this->apiReturn(V(0, '班级ID不能为空！'));

        $where['grade_id'] = $grade_id;
        $field = 'user_id,user_name,truename,grade_id,trial_days';
        $UserModel = D('Home/User');
        $lsit = $UserModel->getUsersListByPage($where,$field);
        foreach($lsit['list'] as &$v){
            if(!$v['truename']) $v['truename'] = '暂无';
        }
        if($lsit['list']){
            $this->apiReturn(V(1, '学生列表',$lsit['list']));
        }
        else{
            $this->apiReturn(V(1, '未查询到'));
        }
    }

    /**
     * @desc 教师端修改学生名称
     * @param user_id     学生ID
     * @param truename    学生姓名
     **/
    public function saveStudent(){
        $user_id = I('post.user_id',0, 'intval');
        $truename = I('post.truename','', 'trim');
        if(!$user_id) $this->apiReturn(V(0, '学生ID不能为空！'));
        if(!$truename) $this->apiReturn(V(0, '学生姓名不能为空！'));

        $UserModel = D('Home/User');
        $where['teacher_id'] = TEACHER_ID;
        $where['user_id'] = $user_id;
        $data['truename'] = $truename;
        $save = $UserModel->setuser($where,$data);
        if($save !==false){
            $this->apiReturn(V(1, '修改成功！'));
        }
        else{
            $this->apiReturn(V(0, '修改失败！'));
        }
    }

    /**
     * @desc 教师端删除学生
     * @param user_id     学生ID
     **/
    public function delStudent(){
        $user_id = I('post.user_id',0, 'intval');
        if(!$user_id) $this->apiReturn(V(0, '学生ID不能为空！'));

        $UserModel = D('Home/User');
        $GradeModel = D('Home/Grade');
        $where['teacher_id'] = TEACHER_ID;
        $where['user_id'] = $user_id;
        $userinfo = $UserModel->getUser($where);
        if(!$userinfo) $this->apiReturn(V(0, '学生不存在或该学生不是您的学生！'));
        if($userinfo['status'] == 0) $this->apiReturn(V(0, '学生已被删除！'));
        $data['status'] = 0;
        //开启事务
        $trans = M();
        $trans->startTrans();
        $save = $UserModel->setuser($where,$data);
        $save1 = $GradeModel->where(array('id'=>TEACHER_ID))->setDec('student_number',1);//更改班级表学生总数
        if($save && $save1){
            $trans->commit();
            $this->apiReturn(V(1, '删除成功！'));
        }
        else{
            $trans->rollback();
            $this->apiReturn(V(0, '删除失败！'));
        }
    }

    /**
     * @desc 学段列表
     **/
    public function periodList(){
        $list = M('course_period')->select();
        $this->apiReturn(V(1, '学段列表',$list));
    }

    /**
     * @desc 教师端创建班级
     * @param grade_code  班级编号
     * @param grade_name  班级名称
     * @param period_id   学段ID
     **/
    public function createGrade(){
        $grade_code = I('post.grade_code','', 'trim');
        $grade_name = I('post.grade_name','', 'trim');
        $period_id = I('post.period_id',0, 'intval');
        if(!$grade_code) $this->apiReturn(V(0, '班级编号不能为空！'));
        if(!$grade_name) $this->apiReturn(V(0, '班级名称不能为空！'));
        if(!$period_id) $this->apiReturn(V(0, '请选择班级学段！'));

        $GradeModel = D('Home/Grade');
        if($GradeModel->checkGradeName($grade_name) == false)$this->apiReturn(V(0, '班级名称不能重复！'));
        //根据TEACHER_ID获取代理商ID
        $agentid = M('teacher')->where(array('id'=>TEACHER_ID))->getField('agent_id');
        $data = array(
            'teacher_id'=>TEACHER_ID,
            'grade_code'=>$grade_code,
            'grade_name'=>$grade_name,
            'disabled'=>1,
            'period_id'=>$period_id,
            'agent_id'=>$agentid,
            'student_number'=>0,
        );
        $add = $GradeModel->add($data);
        if($add){
            $arr['grade_id'] = $add;
            $this->apiReturn(V(1, '班级创建成功！',$arr));
        }
        else{
            $this->apiReturn(V(0, '班级创建失败！'));
        }
    }

    /**
     * @desc 试用天数列表
     **/
    public function trialDay(){
        $list = C('TRIAL_DAY');
        $this->apiReturn(V(1, '试用天数列表',$list));
    }

    /**
     * @desc 教师端批量生成学生账号
     * @param number      学生人数
     * @param trial_days  激活天数
     * @param grade_id    班级ID
     */
    public function addStudent(){
        $number = I('post.number', 0, 'intval');
        $days = I('post.trial_days', 0, 'intval');
        $grade_id = I('post.grade_id', 0, 'intval');
        if($number < 1 || $number > 100) $this->apiReturn(V(0, '学生生成数量为1-100'));
        if(!$days) $this->apiReturn(V(0, '激活天数不能为空！'));
        if(!$grade_id) $this->apiReturn(V(0, '班级id不能为空！'));

        //获取班级，教师信息
        $grade_model = D('Home/Grade');
        $teacher_model = D('Admin/Teacher');
        $user_model = D('Admin/User');
        $where['id'] = $grade_id;
        $grade_info = $grade_model->getGradeInfo($where);
        if(!$grade_info) $this->apiReturn(V(0, '班级信息获取失败！'));
        $teacher_info = $teacher_model->getTeacherDetail(array('user_id' => UID));
        $rand_code = randChar(6);
        $add_data = array();
        for($i = 1;$i<=$number;$i++){
            $add_data[] = array(
                'user_name' => $rand_code.$i,
                'password' => pwdHash('123456'),
                'school_name' => $teacher_info['school_name'],
                'grade_id' => $grade_id,
                'grade' => $grade_info['grade_name'],
                'register_time'=>NOW_TIME,
                'trial_days' => $days,
                'period_id'=>$grade_info['period_id'],
                'agent_id' => $teacher_info['agent_id'],
                'teacher_id' => TEACHER_ID,
                'user_type'=>0,
            );
        }
        //开启事务
        $trans = M();
        $trans->startTrans();
        $add = $user_model->addAll($add_data);//添加学生
        $save = $grade_model->where($where)->setInc('student_number',$number);//更改班级表学生总数
        if($add && $save !== false){
            $trans->commit();
            $this->apiReturn(V(1, '学生账号生成成功！'));
        }
        else{
            $trans->rollback();
            $this->apiReturn(V(0, '学生账号生成失败！'));
        }
    }


    /**
     * @desc 教师端批量生成教师账号
     * @param number      教师人数
     **/
    public function addTeacher(){
        $number = I('post.number', 0, 'intval');
        if($number < 1 || $number > 100) $this->apiReturn(V(0, '教师生成数量为1-100'));

        $UserModel = D('Home/User');
        $TeacherModel = D('Home/Teacher');
        $where['id'] = TEACHER_ID;
        $teacherinfo = $TeacherModel->getTeacherInfo($where);
        if(!$teacherinfo) $this->apiReturn(V(0, '用户信息获取失败！'));
        //开启事务
        $trans = M();
        $trans->startTrans();
        $rand_code = randChar(8);
        for($i = 1;$i<=$number;$i++){
            $add_data = array(
                'user_name' => $rand_code.$i,
                'password' => pwdHash(123456),
                'register_time'=>NOW_TIME,
                'period_id'=>$teacherinfo['period'],
                'agent_id' => $teacherinfo['agent_id'],
                'teacher_id' => TEACHER_ID,
                'user_type'=>1,
            );
            $addUser = $UserModel->add($add_data);//添加用户表
            $tea_data = array(
                'user_id'=>$addUser,
                'agent_id'=>$teacherinfo['agent_id'],
                'period'=>$teacherinfo['period'],
                'reg_time'=>NOW_TIME,
            );
            $addTea = $TeacherModel->add($tea_data);//添加教师表
        }
        if($addUser && $addTea){
            $trans->commit();
            $this->apiReturn(V(1, '教师账号生成成功！'));
        }
        else{
            $trans->rollback();
            $this->apiReturn(V(0, '教师账号生成失败！'));
        }
    }

    /**
     * @desc 教师端教师列表
     * @param p           页数
     **/
    public function teacherList(){
        $where['teacher_id'] = TEACHER_ID;
        $where['user_type'] = 1;
        $field = 'user_id,user_name,mobile,truename,register_time';
        $UserModel = D('Home/User');
        $lsit = $UserModel->getUsersListByPage($where,$field);
        if($lsit['list']){
            foreach($lsit['list'] as &$v){
                if(!$v['mobile']) $v['mobile'] = '暂无';
                if(!$v['truename']) $v['truename'] = '暂无';
                $v['register_time'] = time_format($v['register_time'],'Y-m-d');
            }
            $this->apiReturn(V(1, '教师列表',$lsit['list']));
        }
        else{
            $this->apiReturn(V(1, '未查询到'));
        }
    }

    /**
     * @desc 教师端更改学校名称
     * @param school_name 学校名称
     **/
    public function sveSchool(){
        $school_name = I('post.school_name','','trim');
        if(!$school_name) $this->apiReturn(V(0,'学校名称不能为空！'));
        $TeacherModel = D('Home/Teacher');
        $where['id'] = TEACHER_ID;
        $data['school_name'] = $school_name;
        $save = $TeacherModel->setTeacher($where,$data);
        if($save !==false){
            $this->apiReturn(V(1,'更改成功！'));
        }
        else{
            $this->apiReturn(V(0,'更改失败！'));
        }
    }

    /**
     * @desc 教师端我的学校
     **/
    public function mySchool(){
        $where['id'] = TEACHER_ID;
        $field = 'school_name';
        $TeacherModel = D('Home/Teacher');
        $info = $TeacherModel->getTeacherField($where,$field);
        if($info){
            $arr['school_name'] = $info;
            $this->apiReturn(V(1, '我的学校！',$arr));
        }
        else{
            $this->apiReturn(V(0, '没有！'));
        }
    }


    /**
     * (我的课程列表CourseApi/getMyCourse，课程单元列表CourseApi/getCourseUnit)
     * @desc 教师端发布作业
     * @param class_id    课程ID
     * @param unit_id     单元ID
     * @param end_time    截止时间
     * @param grade_id    班级ID（逗号隔开）
     * @param title       作业名称
     * @param desc        留言
     **/
    public function issueWork(){
        $class_id = I('post.class_id',0, 'intval');
        $unit_id = I('post.unit_id',0, 'intval');
        $end_time = I('post.end_time');
        $grade_id = I('post.grade_id');
        $title = I('post.title','', 'trim');
        $desc = I('post.desc','', 'trim');
        if(!$class_id) $this->apiReturn(V(0, '课程ID不能为空！'));
        if(!$unit_id) $this->apiReturn(V(0, '单元ID不能为空！'));
        if(!$end_time) $this->apiReturn(V(0, '截止时间不能为空！'));
        if(!$grade_id) $this->apiReturn(V(0, '请选择班级！'));
        if(!$title) $this->apiReturn(V(0, '任务名称不能为空！'));

        $grade_ids = explode(',',$grade_id);
        for($i=0;$i<count($grade_ids);$i++){
            $add_data[] = array(
                'class_id' => $class_id,
                'unit_id' => $unit_id,
                'end_time' => strtotime($end_time),
                'grade_id' => $grade_ids[$i],
                'title' => $title,
                'desc'=> $desc,
                'teacher_id'=> TEACHER_ID,
                'create_time'=> NOW_TIME,
            );
        }
        $WorkModel = D('Home/Homework');
        if ($WorkModel->addAll($add_data)) {
            $this->apiReturn(V(1, '布置成功！'));
        }
        else{
            $this->ajaxReturn(V(0, '布置失败!'));
        }
    }

    /**
     * @desc 教师端我的课程列表
     **/
    public function MyCourse(){
        $Course = D('Admin/CourseClass');
        $where['display'] = 1;
        $field = 'id,period_id,class_name';
        $list = $Course->getCourseClassList($where,$field);
        $this->apiReturn(V(1, '获取课程列表', $list));
    }

    /**
     * @desc 教师端我的班级列表
     * @param p           页数
     **/
    public function gradeListBy(){
        $where['teacher_id'] = TEACHER_ID;
        $where['status'] = 1;
        $where['disabled'] = 1;
        $field = 'id,teacher_id,grade_code,grade_name,student_number';
        $GradeModel = D('Home/Grade');
        $list = $GradeModel->getGradeList($where,$field);
        if($list['list']){
            $this->apiReturn(V(1, '班级列表！',$list['list']));
        }
        else{
            $this->ajaxReturn(V(1, '未查询到!'));
        }
    }

    /**
     * @desc 教师端全部作业列表(班级作业列表)
     * @param grade_id    班级ID（不传参是全部，传参是班级）
     * @param p           页数
     **/
    public function HomeworkListPage(){
        $grade_id = I('post',0,'intval');
        if($grade_id) $where['h.grade_id'] = $grade_id;
        $where['h.teacher_id'] = TEACHER_ID;
        $WorkModel = D('Home/Homework');
        $field = 'h.id,h.teacher_id,h.class_id,h.unit_id,h.end_time,h.grade_id,c.class_name,u.unit,g.grade_name,g.student_number';
        $list = $WorkModel->getHomeworkListPage($where,$field);
        if($list['list']){
            $this->apiReturn(V(1, '作业列表！',$list['list']));
        }
        else{
            $this->ajaxReturn(V(1, '未查询到!'));
        }
    }

    /**
     * @desc 教师端查看作业(未完成)
     * @param homework_id 班级作业ID
     * @param grade_id    班级ID
     **/
    public function seeHomeworkNot(){
        $homework_id = I('post.homework_id',0, 'intval');
        $grade_id = I('post.grade_id',0, 'intval');
        if(!$homework_id) $this->apiReturn(V(0, '班级作业ID不能为空！'));
        if(!$grade_id) $this->apiReturn(V(0, '班级ID不能为空！'));
        $UserModel = D('Home/User');
        $GradeModel = D('Home/Grade');
        $HomeworkCountModel = D('Home/HomeworkCount');
        $number = $GradeModel->getGradeField(array('id'=>$grade_id),'student_number');
        $where['grade_id'] = $grade_id;
        $user_id = $UserModel->where($where)->getField('user_id',true);
        if(empty($user_id)) $this->apiReturn(V(0, '未获取到学生信息！'));

        $where1['user_id'] = array('in',$user_id);
        $field = 'user_id,user_name';
        $list = $UserModel->getUsersListBy($where1,$field);
        $field1 = 'user_id';
        $where1['homework_id'] = $homework_id;
        $where1['is_pass'] = array('eq',1);//测试是否通过 0否 1是
        $list1 = $HomeworkCountModel->getHomeworkCountField($where1,$field1);
        foreach($list as $key=>$val){
            if (!in_array($val['user_id'], $list1)) {
                $dddd[]=$val;
            }
        }
        $count = count($dddd);
        $test_score =  round(($count / $number) * 100);
        $arr= array('test_score'=>$test_score, 'count'=> $count, 'list'=> $dddd);
        $this->apiReturn(V(1, '查询完成！',$arr));
    }
    /**
     * @desc 教师端查看作业(已完成)
     * @param homework_id 班级作业ID
     * @param grade_id    班级ID
     **/
    public function seeHomeworkResult(){
        $homework_id = I('post.homework_id',0, 'intval');
        $grade_id = I('post.grade_id',0, 'intval');
        if(!$homework_id) $this->apiReturn(V(0, '班级作业ID不能为空！'));
        if(!$grade_id) $this->apiReturn(V(0, '班级ID不能为空！'));
        $UserModel = D('Home/User');
        $GradeModel = D('Home/Grade');
        $HomeworkCountModel = D('Home/HomeworkCount');
        $number = $GradeModel->getGradeField(array('id'=>$grade_id),'student_number');
        $where['grade_id'] = $grade_id;
        $user_id = $UserModel->where($where)->getField('user_id',true);
        if(empty($user_id)) $this->apiReturn(V(0, '未获取到学生信息！'));

        $data['h.user_id'] = array('in',$user_id);
        $data['h.homework_id'] = $homework_id;
        $data['h.is_pass'] = 1;//测试是否通过 0否 1是
        $field2 = 'h.id,h.score,u.user_id,u.user_name,u.truename,u.head_pic';
        $list = $HomeworkCountModel->getHomeworkCountListByPage($data,$field2);
        foreach($list['list'] as &$v){
            if(empty($v['truename'])) $v['truename'] = '';
            if(empty($v['head_pic'])) $v['head_pic'] = '';
        }
        $dddd = list_sort_by($list['list'],'score','desc');
        $count = count($dddd);
        $test_score =  round(($count / $number) * 100);
        $arr= array('test_score'=>$test_score, 'count'=> $count, 'list'=> $dddd);
        $this->apiReturn(V(1, '查询完成！',$arr));
    }

    /**
     * @desc 教师端班级的学生列表
     * @param grade_id    班级ID
     * @param p           页数
     **/
    public function studentListResult(){
        $grade_id = I('post.grade_id',0, 'intval');
        if(!$grade_id) $this->apiReturn(V(0, '班级ID不能为空！'));

        $where['grade_id'] = $grade_id;
        $field = 'user_id,user_name,truename';
        $UserModel = D('Home/User');
        $list = $UserModel->getUsersListBy($where,$field);
        if($list){
            $mm = M('homework_result');
            $time = time();
            $to_time = strtotime('-7 days');//七天前的时间戳
            foreach($list as &$v){
                if(!$v['truename']) $v['truename'] = '暂无';
                $arr['user_id'] = $v['user_id'];
                $arr['test_result'] = 1;
                $arr['test_time'] = array('between',array($to_time,$time));
                $v['testresult'] =$mm->where($arr)->count();
            }
            $result = list_sort_by($list,'testresult','desc');
            $this->apiReturn(V(1, '班级的学生列表',$result));
        }
        else{
            $this->apiReturn(V(1, '未查询到'));
        }
    }

    /**
     * @desc 教师端学生学情的课程和单元列表
     * @param user_id     学生ID
     **/
    public function studyStudent(){
        $user_id = I('post.user_id',0, 'intval');
        if(!$user_id) $this->apiReturn(V(0, '学生ID不能为空！'));

        $UserModel = D('Home/User');
        $GradeModel = D('Home/Grade');
        $CourseClassModel = D('Home/CourseClass');
        $where['user_id'] = $user_id;
        $grade_id = $UserModel->getUserField($where,'grade_id');
        $where1['id'] = $grade_id;
        $period_id = $GradeModel->getGradeField($where1,'period_id');
        if(!$period_id) $this->apiReturn(V(0, '学段信息获取失败！'));
        $where2['period_id'] = $period_id;
        $where2['display'] = 1;
        $list = $CourseClassModel->getCourseClassLists($where2,$user_id);
        if($list){
            $this->apiReturn(V(1, '学生学情的课程和单元列表',$list));
        }
        else{
            $this->apiReturn(V(1, '未查询到'));
        }
    }

    /**
     * @desc 教师端单元测试结果(Api/CourseApi/getTestResult)
     * @param user_id 学生ID
     * @param unit_id 课程单元ID
     **/

    
    /**
     * @desc 教师端我的学习圈
     * @param type 类型1一级 2二级
     **/
    public function studyCircle(){
        $type = I('post.type',0,'intval');
        if($type>2 || $type<1) $this->apiReturn(V(0,'类型错误！'));
        $TeacherModel = D('Home/Teacher');
        $UserModel = D('Home/User');
        $where['id'] = TEACHER_ID;
        $points = $TeacherModel->getTeacherField($where,'points');

        //查询出一级教师
        $where1['u.teacher_id'] = TEACHER_ID;
        $where1['u.user_type'] = 1;
        $teacher = $UserModel->getUsersListTeacher($where1,'u.user_id,t.id as teacher_id');
        if(empty($teacher)){
            $list['list'] = array();
        }
        else{
            $sky = '';
            foreach($teacher as &$v){
                $sky.= $v['teacher_id'].',';
            }

            $field1 = 'user_id,user_name,used_time,expire_time,used_number';
            if($type == 1){
                $where2['teacher_id'] = $sky;
                $list = $UserModel->getUsersListByPage($where2,$field1,'register_time,user_id asc');
            }
            else{
                //查询出二级
                $where3['u.teacher_id'] = $sky;
                $where3['u.user_type'] = 1;
                $teacher1 = $UserModel->getUsersListTeacher($where3,'u.user_id,t.id as teacher_id');
                $sky1 = '';
                foreach($teacher1 as &$v){
                    $sky1.= $v['teacher_id'].',';
                }
                $where4['teacher_id'] = $sky1;
                $list = $UserModel->getUsersListByPage($where4,$field1,'register_time,user_id asc');
            }
        }
        $data = array('points'=>$points,'list'=>$list['list']);
        $this->apiReturn(V(1, '我的学习圈',$data));
    }

    /**
     * @desc 教师端兑换学习币
     * @param num      兑换个数
     * @param desc        课程描述
     **/
    public function changeDistribution(){
        $num = I('post.num',0, 'intval');
        $desc = I('post.desc','');
        if(!$num) $this->apiReturn(V(0, '兑换个数不能为空！'));
        if(!$desc) $this->apiReturn(V(0, '课程描述不能为空！'));
        $TeacherModel = D('Home/Teacher');
        $where['id'] = TEACHER_ID;
        $points = $TeacherModel->getTeacherField($where,'points');
        if($points < $num) $this->apiReturn(V(0, '学习币不足！'));

        $data['exchange_num'] = $num;
        $data['desc'] = $desc;
        $data['exchange_time'] = time();
        $data['exchange_state'] = 0;//兑换状态 0待审核 1兑换完成 2兑换失败
        $data['teacher_id'] = TEACHER_ID;
        $result = M('exchange')->add($data);
        if($result){
            $this->apiReturn(V(1, '申请成功，请等待审核！'));
        }
        else{
            $this->apiReturn(V(0, '操作失败！'));
        }
    }

    /**
     * @desc 教师端兑换记录
     **/
    public function changeList(){
        $where['teacher_id'] = TEACHER_ID;
        $ExchangeModel = D('Home/Exchange');
        $field = 'id,exchange_num,desc,exchange_time,exchange_state';
        $list = $ExchangeModel->getExchangeListByPage($where,$field);
        if($list['list']){
            foreach($list['list'] as &$v){
                $v['exchange_time'] = date('Y-m-d H:i:s',$v['exchange_time']);
            }
            $this->apiReturn(V(1, '兑换记录列表！',$list['list']));
        }
        else{
            $this->apiReturn(V(1, '未查询到！'));
        }
    }






















    /**
     * @desc 教师端学生绑定接口
     */
    /*public function bindStudent(){
        $user_name = I('post.user_name', '', 'trim');
        $user_password = I('post.password', '', 'trim');
        $user_model = D('Home/UserModel');
        $user_info_where = array('user_name' => $user_name);
        $user_info = $user_model->getUserInfo($user_info_where, 'password,user_id');
        $teacher_model = D('Admin/Teacher');
        if(!$user_info) $this->apiReturn(V(0, '该账号不存在或已被禁用！'));
        if(pwdHash($user_info['password'], $user_password, true) != true) $this->apiReturn(V(0, '密码不正确！'));
        $res = $user_model->where(array('user_id' => $user_info['user_id']))->save(array('parent_teacher_id' => UID));
        $t_res = $teacher_model->where(array('user_id' => UID))->save(array('student_id' => $user_info['user_id']));
        if(false !== $res && false !== $t_res){
            $this->apiReturn(V(1, '绑定成功！'));
        }
        else{
            $this->apiReturn(V(0, '绑定失败！'));
        }
    }*/
}