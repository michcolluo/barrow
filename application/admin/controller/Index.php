<?php
namespace app\admin\controller;
use think\Controller;
use think\Cookie;
use app\admin\model\UsersModel;

class Index extends Controller{
	
    public function index(){
    	$username = Cookie::get('username','barrow_');
    	if($username == ''){
    		$this->redirect('login/index');
    	}
    	$user_model = new UsersModel();
    	$user_data = $user_model->get_user_info($username);
    	$this->assign('user', $user_data);
		return view();
    }
    
    public function logout(){
    	Cookie::clear('barrow_');
    }
    
    
}
