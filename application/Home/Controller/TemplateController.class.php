<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

    public function index(){
        $this->show('hehehe','utf-8');
    }

    public function hehe() {
    	echo 'xxx';
    }
}