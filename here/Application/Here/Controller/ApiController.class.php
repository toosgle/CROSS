<?php
namespace Here\Controller;
use Think\Controller;
import('Here.BCS.bcs');
class ApiController extends Controller {

    public function __construct(){
        $result = array(
                            'no' => 1,
                            'data' => array()
                        );
        $action = preg_replace('/\/.*\//', '', __ACTION__);
        if(!method_exists($this, $action)){
            $result['data']['message'] = '非法操作';
            echo json_encode($result);
            die();
        }

        if(in_array($action, array('follow', 'comment'))){
            if(!isset($_SESSION['username'])){
                $result['data']['message'] = '请先登录';
                echo json_encode($result);
                die();
            }
        }

    }

    public function index(){
        echo 'Hello';
    }

    /**
     * 获取用户信息
     * @param  String $username 用户名
     * @return JSON             用户信息
     */
    public function get_user($username = ''){
        $result = array(
                            'no' => 1,
                            'data' => array()
                        );

        $user = M("User")->where('username="'.$username.'"')->find();

        if( $user === NULL ){
            $result['no'] = 0;
        }else{
            $result['data'] = $user;
        }

        echo json_encode($result);
    }

    /**
     * 获取照片的评论
     * @param  Integer $photoId 照片id
     * @return JSON             照片评论
     */
    public function get_comments($photoId = -1){
        $result = array(
                            'no' => 1,
                            'data' => array()
                        );

        $comments = M("Comment")->where('photoId="'.$photoId.'"')->select();

        if( $comments === false ){
            $result['no'] = 0;
        }else{
            $result['data'] = $comments;
        }

        echo json_encode($result);
    }

    /**
     * 获取照片关注
     * @param  Integer $photoId 照片id
     * @return JSON             照片关注
     */
    public function get_follows($photoId = -1){
        $result = array(
                            'no' => 1,
                            'data' => array()
                        );

        $follows = M("Follow")->where('photoId="'.$photoId.'"')->select();

        if( $follows === false ){
            $result['no'] = 0;
        }else{
            $result['data'] = $follows;
        }

        echo json_encode($result);
    }

    /**
     * 获取一组照片（只返回被所有者merge的照片）
     * @param  Integer $groupId 照片组的id
     * @return JSON             照片组
     */
    public function get_group($groupId = -1){
        $result = array(
                            'no' => 1,
                            'data' => array()
                        );

        $group = M("Group")->where('id="'.$groupId.'"')->find();
        $photos = M("Photo")->where('groupId="'.$groupId.'" AND accepted=1')->select();

        $group['photos'] = $photos;

        if( $group === false ){
            $result['no'] = 0;
        }else{
            $result['data'] = $group;
        }

        echo json_encode($result);
    }

    /**
     * 关注某个照片
     * @param  Integer $photoId 照片的id
     * @return JSON             是否关注成功
     */
    public function follow($photoId = -1){
        $result = array(
                            'no' => 1,
                            'data' => array()
                        );

        $userId = $_SESSION['userid'];

        $follow = M('Follow')->where('photoId="'.$photoId.'" AND userId="'.$userId.'"')->find();
        if($follow != NULL){
            $result['data']['message'] = '之前已关注';
            echo json_encode($result);
            die();
        }

        $result = M('Follow')->data(array('photoId' => $photoId, 'userId' => $userId))->add();

        if(false == $result){
            $result['no'] = 0;
            $result['data']['message'] = '关注失败';
        }else{
            $result['data']['message'] = '关注成功';
        }

        echo json_encode($result);
    }

    /**
     * 评论某个照片
     * @param  Integer $photoId 照片的id
     * @param  string  $content   评论内容
     * @return JSON           是否评论成功
     */
    public function comment($photoId = -1, $content = ''){
        $result = array(
                            'no' => 1,
                            'data' => array()
                        );


        $userId = $_SESSION['userid'];

        if('' == $content){
            $result['no'] = 0;
            $result['data']['message'] = '评论内容不能为空';
            echo json_encode($result);
            die();
        }

        $content = htmlentities(trim($content));
        
        $comment = M('Comment')->where('photoId="'.$photoId.'" AND userId="'.$userId.'"')->find();
        if($comment != NULL){
            $result['data']['message'] = '之前已评论';
            echo json_encode($result);
            die();
        }

        $result = M('Comment')->data(array('photoId' => $photoId, 'userId' => $userId, 'content' => $content))->add();

        if(false == $result){
            $result['no'] = 0;
            $result['data']['message'] = '评论失败';
        }else{
            $result['data']['message'] = '评论成功';
        }

        echo json_encode($result);
    }

    /**
     * 上传照片数据
     * @param  Integer userId       用户id，从session中取
     * @param  Integer groupId      照片组id
     * @param  File    file         文件句柄
     * @param  String  position     位置信息
     * @param  String  environment  环境信息
     * @param  String  measurement  照片尺寸
     * @param  Integer direction    横竖屏
     * @return JSON                 照片线上URL
     */
    public function upload($groupId = NULL, $position = NULL, $environment = NULL, $measurement = NULL, $direction = NULL ){
        $response = $this->uploadImageToBCS();
        $result = array(
                            'no' => 1,
                            'data' => array()
                        );

        if(!$response){
            $result['no'] = 0;
            $result['data']['message'] = '上传失败';
            echo json_encode($result);
            die();
        }

        $data = array(
                        'userId' => $_SESSION['userid'],
                        'groupId' => $groupId,
                        'position' => $position,
                        'environment' => $environment,
                        'measurement' => $measurement,
                        'direction' => $direction,
                        'hash' => $response
                     );
        $result = M('Photo')->data($data)->add();

        if(false == $result){
            $result['no'] = 0;
            $result['data']['message'] = '上传失败';
        }else{
            $result['data']['url'] = 'http://bcs.duapp.com/here-photo/'.$response.'?sign=MBO:qm7QzugDqd3b7D7Djkq5Q9ZY:MZnTlc7v%2BHKvbcQlAD0TrshQwqI%3D&response-cache-control=private';
        }

        echo json_encode($result);
    }

    public function uploadImageToBCS(){
        $type = array('image/jpeg', 'image/png');
        if(!in_array($_FILES['file']['type'], $type)){
            return false;
        }

        $host = 'bcs.duapp.com'; //online
        $ak = BCS_AK;
        $sk = BCS_SK;
        $bucket = 'here-photo';
        $object = '/'.$_SESSION['username'].'/'.md5(microtime(true)).preg_replace('/[^.]*\./', '.', $_FILES['file']['name']);
        $fileUpload = $_FILES['file']['tmp_name'];
        $baidu_bcs = new \BaiduBCS ( $ak, $sk, $host );

        $opt = array ();
        $opt ['acl'] = \BaiduBCS::BCS_SDK_ACL_TYPE_PUBLIC_WRITE;
        $opt [\BaiduBCS::IMPORT_BCS_LOG_METHOD] = "bs_log";
        $opt ['curlopts'] = array (
                CURLOPT_CONNECTTIMEOUT => 10, 
                CURLOPT_TIMEOUT => 1800 );
        $opt ['Headers'] = array(
                                    'Content-Type' => $type
                                );
        $response = $baidu_bcs->create_object ( $bucket, $object, $fileUpload, $opt );
        
        if($response->isOK()){
            return $object;
        }else{
            return false;
        }
    }

}