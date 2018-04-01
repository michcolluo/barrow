<?php
namespace app\admin\controller;

use think\Controller;
use think\Cookie;
use think\Request;
use think\Log;

class Login extends Controller{
    public function index(){
		return view();
    }
    
    public function login(){
    	Log::record('测试日志信息');
    	$post = request()->post();
    	$user_model = model('user');
    	$result = $user_model->login($post['username'], md5($post['password']));
    	if($result === config('empty_username')){
			$jarr = [
				'success' => 'false',
				'msg'=> '用户名不能为空！',
				'body'=> ''
			];		
    	}else if($result === config('error_password')){
			$jarr = [
				'success'=> 'false',
				'msg'=> '账号或密码错误！',
				'body'=> ''
			];		
    	}else if($result === ''){
			$jarr = [
				'success'=> 'false',
				'msg'=> '登录异常！',
				'body'=> ''
			];	
    	}else if($result === config('error_islock')){
			$jarr = [
				'success'=> 'false',
				'msg'=> '账号被锁定！',
				'body'=> ''
			];	
    	}else{
			$jarr = [
				'success'=> 'true',
				'msg'=> '登录成功！',
				'body'=> $result
			];	
			$arr = json_decode ($result, true);
			if(isset($post['online'])){
				$time = $post['online'];
			}else{
				$time = 0;
			}
			$id = $result['userid'];
			Cookie::init(['prefix'=>'barrow_','path'=>'/']);
			Cookie::set('username', $arr['username'], $time);
			$request = Request::instance();
			$user_model->where('userid', $id)->update([
				'lastdate' => date('Y-m-d H:i:s'), 
				'lastip' => $request->ip()
			]);
    	}
    	return json_encode($jarr);
	}
	
	public function test(){
		$request = Request::instance();
    	$user_model = new UsersModel();
		$user_model->where('userid', 5)->update([
			'lastdate' => date('Y-m-d H:i:s'), 
			'lastip' => $request->ip()
		]);
	}
}