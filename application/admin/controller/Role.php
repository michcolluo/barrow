<?php
namespace app\admin\controller;

use think\Controller;
use think\Log;
use think\Db;
use app\admin\model\Authority;
use app\admin\model\UsersModel;
use think\Cookie;

class Role extends Controller{
    public function index()
    {
    	$data = Db('bar_role')->select();
    	$this->assign('role', $data);
    	$this->assign('role_count', count($data));
    	$this->assign('userlist', Db('bar_users')->select());
    	$username = Cookie::get('username','barrow_');
    	if($username == ''){
    		$this->redirect('login/index');
    	}
    	$user_model = new UsersModel();
    	$user_data = $user_model->get_user_info($username);
    	$this->assign('user', $user_data);
		return view();
    }
    
	public function add()
	{
		$this->get_authority_list([]);
		return view();	
	}
	
	public function insert()
	{
		$post = request()->post();
		$role_name = $post['role_name'];
		if ($role_name == ''){
			$jarr = [
				'success' => 'false',
				'msg'=> '角色名不能为空！',
				'body'=> ''
			];	
			return json_encode($jarr);		
		}
		if(empty($post['authority'])){
			$jarr = [
				'success' => 'false',
				'msg'=> '请勾选一个权限！',
				'body'=> ''
			];	
			return json_encode($jarr);		
		}
		$data = Db('bar_role')->where(['role_name' => $role_name])->find();
		if ($data != ''){
			$jarr = [
				'success' => 'false',
				'msg'=> '角色名不能重复！',
				'body'=> ''
			];	
			return json_encode($jarr);		
		}
		$arr = [
			'role_name' => $role_name,
			'authority' => json_encode($post['authority'])
		];
		$result = Db('bar_role')->insert($arr);
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
	
	public function edit($id = '')
	{
		if ($id == ''){
			
		}
		$data = Db('bar_role')->where(['id' => $id])->find();
		$this->assign('role_name', $data['role_name']);
		$this->assign('description', $data['description']);
		$this->assign('id', $id);
		$arr = json_decode($data['authority']);
		$this->get_authority_list($arr);
		return view();
	}
	
	public function update()
	{
		$post = request()->post();
		$role_name = $post['role_name'];
		if ($role_name == ''){
			$jarr = [
				'success' => 'false',
				'msg'=> '角色名不能为空！',
				'body'=> ''
			];	
			return json_encode($jarr);		
		}
//		$data = Db('bar_role')->where(['role_name' => $role_name])->find();
//		if ($data != ''){
//			$jarr = [
//				'success' => 'false',
//				'msg'=> '角色名不能重复！',
//				'body'=> ''
//			];	
//			return json_encode($jarr);		
//		}
		if(empty($post['authority'])){
			$jarr = [
				'success' => 'false',
				'msg'=> '请勾选一个权限！',
				'body'=> ''
			];	
			return json_encode($jarr);		
		}
		$arr = [
			'id' => $post['id'],
			'role_name' => $role_name,
			'description' => $post['description'],
			'authority' => json_encode($post['authority'])
		];
		$result = Db('bar_role')->update($arr);
		
		if ($result !== false){
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
	
	public function get_authority_list($arr)
	{
		$select = Db('bar_authority')->select();
		$model = model('authority');
		$list = $model->getChildrenId($select, $arr);
		$data = $this->assign('list', $list);
		return view();
	}
	
	public function delete($id)
	{
		$result = db('bar_role')->delete($id);
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