<?php
namespace Here\Controller;
use Think\Controller;
class ApiController extends Controller {

    public function __construct(){
        $action = preg_replace('/\/.*\//', '', __ACTION__);
        if(!method_exists($this, $action)){
            echo '非法操作';
            die();
        }

        if(in_array($action, array('follow', 'comment'))){
            if(!isset($_SESSION['username'])){
                echo '请先登录';
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
}