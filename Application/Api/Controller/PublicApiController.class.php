<?php
/**
 * Created by liuniukeji.com
 * 公用接口
 * @author
*/
namespace Api\Controller;
use Common\Controller\ApiCommonController;
use Think\Verify;

class PublicApiController extends ApiCommonController {

    /**
     * @desc 获取版本更新信息
     */
    public function getVersion(){
        $type = I('post.type', 1, 'intval');
        $type = 1; //TODO 默认1
        $where = array('version_type' => $type);
        $model = D('Admin/Version');
        $info = $model->getVersionInfo($where);
        if(is_array($info)){
            $order=array("\r\n","\n","\r");
            $replace='<br/>';
            $info['version_desc'] = str_replace($order,$replace,$info['version_desc']);
            $info['add_time'] = time_format($info['add_time']);
            $this->apiReturn(V(1, '版本更新信息获取成功！', $info));
        }
        else{
            $this->apiReturn(V(0, '没有可更新版本！'));
        }
    }

    /**
     * 获取图形验证码
     */
    public function getChkCode(){
        $Verify = new \Think\Verify(array(
            'length' => 4,
            'useNoise' => FALSE,
            'imageH' =>40,
            'imageW' => 100,
            'fontSize'=>14,
            'useCurve'=>false
        ));
        $Verify->entry(1);
       
    }
}
