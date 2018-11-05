<?php
/**
 * 轮播图操作类
 */
namespace Admin\Controller;
use Think\Controller;
class BannerController extends CommonController{
     /**
     * 列表
     */
     public function listBanner()
     {
        $keyword = I('keyword', '');
        $BannerModel = D('Admin/Banner');
        if ($keyword) {
            $where['title'] = array('like','%'.$keyword.'%');
        }
//        print_r($keyword);
        /*查询轮播图field*/
        $field = 'id, title,img_url, jump_url, sort,add_time,status,type';
        $data = $BannerModel->getBannerListByPage($where, $field);
        $this->bannerlist = $data['bannerlist'];
        $this->page = $data['page'];
        $this->display();
     }
     /*
       添加  编辑  banner
      */
     public function editBanner()
     {
     	$id = I('id',0,'intval');
        $BannerModel = D('Admin/Banner');     
        if (IS_POST) {
            if ($BannerModel->create() === false) {
                $this->ajaxReturn(V(0, $BannerModel->getError()));
            }
            if ($id) {
                if ($BannerModel->save() !== false) {
                    $this->ajaxReturn(V(1, '编辑成功'));
                }
            } else {
                if ($BannerModel->add() !== false) {
                    $this->ajaxReturn(V(1, '添加成功'));
                }
            }
            $this->ajaxReturn(V(0, $BannerModel->getDbError()));
        }       
        $info = $BannerModel->find($id);
        $this->assign('info', $info);
        $this->display();
     }
    public function recycle() {
        $this->_recycle('Banner', 'id');
    }
    // 删除图片
    public function delFile(){
        $this->_delFile();  //调用父类的方法
    }

    // 上传图片
    public function uploadImg(){
        $this->_uploadImg();  //调用父类的方法
    }
        public function del(){
        $this->_del('Banner', 'id');
    }
    /**
     *禁用方法
     */
    public function changeDisabled() {
        $id = I('id', 0, 'intval');
        $updateInfo = D('Admin/Banner')->changeDisabled($id);
        $this->ajaxReturn($updateInfo);
    }

}