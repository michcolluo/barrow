<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\UsersModel;
use think\Cookie;
use think\Request;
use think\Log;

class User extends Controller{
    public function index(){
		return view();
    }
    
    public function info(){
    	$username = Cookie::get('username','barrow_');
    	$user_model = new UsersModel();
    	$user_data = $user_model->get_user_info($username);
    	$this->assign('user', $user_data);
    	return view();
    }
}