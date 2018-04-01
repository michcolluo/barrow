<?php
namespace app\admin\controller;

use think\Controller;
use think\Cookie;
use think\Request;
use think\Log;

class User extends Controller{
	
    public function index(){
    	$username = Cookie::get('username','barrow_');
    	if($username == ''){
    		$this->redirect('login/index');
    	}
//  	$data = Db('bar_users')->select();
    	$user_model = model('user');
    	$user_data = $user_model->get_user_info($username);
    	$data = $user_model->get_user_list();
    	$this->assign('user', $user_data);
    	$this->assign('userlist', $data);
    	$this->assign('user_count', count($data));
    	return view('index', [], $user_model->get_authority($username));
    }
    
    public function info(){
    	$username = Cookie::get('username','barrow_');
    	$user_model = new UsersModel();
    	$user_data = $user_model->get_user_info($username);
    	$this->assign('user', $user_data);
    	return view();
    }
    
	public function user_stop($id)
	{
		$result = db('bar_users')->where('userid', $id)->update(['islock' => 1]);
		if ($result != ''){
			$jarr = [
				'success' => 'true',
				'msg'=> '',
				'body'=> ''
			];		
		}else{
			$jarr = [
				'success' => 'false',
				'msg'=> '停用失败',
				'body'=> ''
			];		
		}
		return json_encode($jarr);
	}
	
	public function user_start($id)
	{
		$result = db('bar_users')->where('userid', $id)->update(['islock' => 0]);
		if ($result != ''){
			$jarr = [
				'success' => 'true',
				'msg'=> '',
				'body'=> ''
			];		
		}else{
			$jarr = [
				'success' => 'false',
				'msg'=> '启用失败',
				'body'=> ''
			];		
		}
		return json_encode($jarr);	
	}
	
	public function add()
	{
		$data = Db('bar_role')->select();
		$this->assign('role', $data);
		return view();
	}
	
	public function insert()
	{
		$post = request()->post();
		$username = $post['username'];
		if ($username == ''){
			$jarr = [
				'success' => 'false',
				'msg'=> '角色名不能为空！',
				'body'=> ''
			];	
			return json_encode($jarr);		
		}
		$data = Db('bar_users')->where(['username' => $username])->find();
		if ($data != ''){
			$jarr = [
				'success' => 'false',
				'msg'=> '用户名不能重复！',
				'body'=> ''
			];	
			return json_encode($jarr);		
		}
		$arr = [
			'username' => $username,
			'password' => md5($post['password']),
			'nickname' => $post['nickname'],
			'regdate' => date('y-m-d h:i:s',time()),
			'regip' => '',
			'email' => $post['email'],
			'mobile' => $post['mobile'],
			'roleid' => $post['roleid'],
			'memo' => $post['memo']
		];
		$result = Db('bar_users')->insert($arr);
		if ($result == 1){
			$jarr = [
				'success' => 'true',
				'msg'=> '保存成功',
				'body'=> ''
			];			
		}else{
			$jarr = [
				'success' => 'false',
				'msg'=> '保存失败',
				'body'=> ''
			];		
		}
		return json_encode($jarr);
	}
	
	public function edit($userid = '')
	{
		$data = Db('bar_role')->select();
		$this->assign('role', $data);
		$data = Db('bar_users')->where('userid', $userid)->find();
		$this->assign('user', $data);
		return view();
	}
	
	public function update()
	{
		$post = request()->post();
		$username = $post['username'];
		if ($username == ''){
			$jarr = [
				'success' => 'false',
				'msg'=> '角色名不能为空！',
				'body'=> ''
			];	
			return json_encode($jarr);		
		}
		$arr = [
			'userid' => $post['userid'],
			'username' => $username,
			'nickname' => $post['nickname'],
			'email' => $post['email'],
			'mobile' => $post['mobile'],
			'roleid' => $post['roleid'],
			'memo' => $post['memo']
		];
		$result = Db('bar_users')->update($arr);
		if ($result == 1){
			$jarr = [
				'success' => 'true',
				'msg'=> '保存成功',
				'body'=> ''
			];			
		}else{
			$jarr = [
				'success' => 'false',
				'msg'=> '保存失败',
				'body'=> ''
			];		
		}
		return json_encode($jarr);
	}
	
	public function delete($id)
	{
		$result = db('bar_users')->delete($id);
		if ($result != ''){
			$jarr = [
				'success' => 'true',
				'msg'=> '保存成功',
				'body'=> ''
			];		
		}else{
			$jarr = [
				'success' => 'false',
				'msg'=> '保存失败',
				'body'=> ''
			];		
		}
		return json_encode($jarr);
	}
}