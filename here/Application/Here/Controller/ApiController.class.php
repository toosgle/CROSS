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
     * @param  [String] $username 用户名
     * @return [JSON]           用户信息
     */
    public function get_user($username = ''){
        $user = M("User")->where('username="'.$username.'"')->find();

        if( $user === NULL ){
            echo '0';
        }else{
            echo json_encode($user);
        }
    }

    /**
     * 获取照片的评论
     * @param  [Integer] $photoId 照片id
     * @return [JSON]          照片评论
     */
    public function get_comments($photoId = -1){
        $comments = M("Comment")->where('photoId="'.$photoId.'"')->select();

        if( $comments === false ){
            echo '0';
        }else{
            echo json_encode($comments);
        }
    }

    /**
     * 获取照片关注
     * @param  [Integer] $photoId 照片id
     * @return [JSON]          照片关注
     */
    public function get_follows($photoId = -1){
        $comments = M("Follow")->where('photoId="'.$photoId.'"')->select();

        if( $comments === false ){
            echo '0';
        }else{
            echo json_encode($comments);
        }
    }

    /**
     * 获取一组照片（只返回被所有者merge的照片）
     * @param  [Integer] $groupId 照片组的id
     * @return [JSON]          照片组
     */
    public function get_group($groupId = -1){
        $group = M("Group")->where('id="'.$groupId.'"')->find();
        $photos = M("Photo")->where('groupId="'.$groupId.'" AND accepted=1')->select();

        $group['photos'] = $photos;

        if( $group === false ){
            echo '0';
        }else{
            echo json_encode($group);
        }
    }

    /**
     * 关注某个照片
     * @param  [Integer] $photoId 照片的id
     * @param  [Integer] $userId  用户id
     * @return [JSON]           是否关注成功
     */
    public function follow($photoId = -1, $userId = -1){
        
        echo '关注';
    }
}