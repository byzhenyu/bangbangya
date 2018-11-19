<?php
/**
 * 从lnzy项目移植过来
 * User: jipingzhao
 * Date: 6/27/17
 * Time: 2:05 PM
 */
namespace Common\Model;
use Think\Model;

class PushModel extends Model{

    protected $insertFields = array('id','title','url','content1','content2','content3','add_time','content4');
    protected $updateFields = array('id','title','url','content1','content2','content3','add_time','content4');
    protected $selectFields = array('id','title','url','content1','content2','content3','add_time','content4');

    protected $_validate = array(
        array('title', 'require', '公告推送名称不能为空', 1, 'regex', 3),
        array('title', 'checkTitle', '公告推送标题不能超过20个字', 2, 'callback', 3),
        array('content1', 'require', '公告推送描述不能为空', 1, 'regex', 3),
        array('content1', 'checkDesc', '公告推送描述不能超过50个字', 2, 'callback', 3),
        array('content2', 'require', '公告推送内容不能为空', 0, 'regex', 3),
        array('content3', 'require', '公告推送内容不能为空', 0, 'regex', 3),
        array('content4', 'require', '公告推送内容不能为空', 0, 'regex', 3),
    );

    protected function checkTitle($data) {
        $length = mb_strlen($data, 'utf-8');
        if ($length > 20) {
            return false;
        }
        return true;
    }
    protected function checkDesc($data) {
        $length = mb_strlen($data, 'utf-8');
        if ($length > 50) {
            return false;
        }
        return true;
    }

    protected function _before_insert(&$data,$options) {
        $data['add_time'] = time();

    }

    protected function _before_update(&$data,$options) {

    }

    // 查询推送列表
    public function getList($where, $field = null, $order = 'add_time desc'){
        if ($field == null) {
            $field = $this->selectFields;
        }

        $count = $this->where($where)->count();
        $page = get_page($count);
        $data = $this->field($field)->where($where)->limit($page['limit'])->order($order)->select();
        return array(
            'data' => $data,
            'page' => $page['page']
        );
    }

    /**
     * 极光推送通用消息
     * @param string $alert  提示标题
     * @param mixed $userId 用户id 可传数组
     * @param string $msg  信息内容
     * @param int $type  推送类型
     * @param int $record_id 业务表主键id
     * @param string $record_table 业务表名称
     * @param string $type 信息类型
     * @param int $pushType  推送用户类型 1商家端，2用户端
     * @return array
     */
    public function push($alert, $userId, $msg1,$msg2,$msg3,$msg4) {
        if ($userId == '' || empty($userId)) {
            return V(0, '请选择推送人群');
        } elseif ($userId == 'all') {
            $userId = '';
        }
        $result = jPush($alert, 'msg', $userId, $msg1);
        $result_json = json_decode($result);
        if ($result_json) {   // 推送成功
            // 把推送写进推送表
            if (is_array($userId)) {
                foreach ($userId as $key => $v) {
                    $this->_addPush($alert, $msg1,$msg2,$msg3,$msg4, $v);
                }

                return V(1, '推送成功');
            } else {
                $this->_addPush($alert, $msg1,$msg2,$msg3,$msg4, $userId);

                return V(1, '推送成功');
            }
        } else {  // 推送失败
            $this->_addPush($alert, $msg1,$msg2,$msg3,$msg4, $userId);

            return V(0, '推送失败', $result);
        }
    }

    /**
     * 写入推送
     * @param $title 推送的标题
     * @param $content 推送的内容
     * @param $userId 用户ID
     * @param $result 推送的结果, 0: 失败, 1:已推送
     */
    private function _addPush($title, $content1,$content2,$content3,$content4, $userId){
        $data = array(
            'user_id'   => $userId,
            'title'     => $title,
            'content1'   => $content1,
            'content2'   => $content2,
            'content3'   => $content3,
            'content4'   => $content4,
            'add_time'  => time(),

        );
        return $this->add($data);
    }

}