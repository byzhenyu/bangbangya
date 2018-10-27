<?php
/**
 * Copyright (c) 山东六牛网络科技有限公司 https://www.liuniukeji.com
 *
 * @Description
 * @Author         
 * @Date           2018/10/25
 * @CreateBy       PhpStorm
 */
namespace Admin\Controller;
use Think\Controller;
/**
 * 任务类型控制器
 */
class HelpController extends CommonController {
      /**
       * @ 常见问题列表
       * @param  $[keyword] [<关键字>]
       * @param  $[type] [<类型 0 常见问题  1 使用帮助>]
       * @return [type = 0]  array()
       */
	  public function questionList($type = 0){
        if(is_null($type))
        {
        	$where['type'] = 0;
        }else{
        	$where['type'] = $type;
        }
        $keyword = I('keyword', '');
        $HelpModel = D('Admin/Help');
        if ($keyword) {
            $where['title'] = array('like','%'.$keyword.'%');
        }
        $data = $HelpModel->getHelpList($where);     
        $this->assign('list', $data['Helplist']);
        $this->assign('page', $data['page']);
        $this->assign('type', $type);
         /*判断模板引用*/
        if($type == 1)
        {
            $this->display('questionList');
        }else{
            $this->display();
        }       
    }
     /*删除*/
    public function recycle() {
        $this->_recycle('Help','id');
    }
    /**
     * @ 修改问题的状态
     * @param   $[id] [<主键>]
     * @return  json  1 successful  2 error
     */
    public function changeStatus()
    {
    	$id = I('id', 0, 'intval');
    	$changeStatusInfo = D('Admin/Help')->changeStatus($id);
    	$this->ajaxReturn($changeStatusInfo);
    }
    /**
     * 详情
     */
    public function editQuestion() {
        $id = I('id', 0, 'intval');
        $where['id'] = $id;
        $HelpModel = D('Admin/Help');
        if (IS_POST) {
            $data = I('post.');
            $data['add_time'] = NOW_TIME;
            if ($HelpModel->create($data) === false) {
                $this->ajaxReturn(V(0, $HelpModel->getError()));
            }
            if ($id) {
                if ($HelpModel->save() !== false) {
                    $this->ajaxReturn(V(1, '编辑成功'));
                }
            } else {
                if ($HelpModel->add() !== false) {
                    $this->ajaxReturn(V(1, '添加成功'));
                }
            }
            $this->ajaxReturn(V(0, $HelpModel->getDbError()));
        }
        $HelpInfo = $HelpModel->getQuestionInfo($where);
        // p($HelpInfo);
        $this->Info = $HelpInfo;
        $this->display();
    }
    public function del(){
        $this->_del('Help', 'id');
    }
    /*完成*/
    public function ComplaintComplete($id)
    {
    	$ComplaintTable = M('Help');
    	$result = $ComplaintTable -> where('id = '.$id) ->save(array('audit_status' => 1));
    	if($result)
    	{
            $this->success("成功！", U("Complaint/listComplaint"));
    	}else{
            $this->error("删除失败！", $ComplaintTable->getError());
    	}
    }
    /**
     * 使用帮助
     * @param   $where type = 1
     *
     */
    public function helplist()
    {
          $this->questionList(1);
    }

}