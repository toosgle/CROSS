<?php
namespace Here\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display('edit');
        echo 'Hello';
    }
}