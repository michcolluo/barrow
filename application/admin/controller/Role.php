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
		$this->get_authority_list();
		return view();	
	}
	
	public function insert()
	{
		$post = request()->post();
		dump($post);
	}
	
	public function get_authority_list()
	{
		$select = db('bar_authority')->select();
		$model = model('authority');
		$list = $model->getChildrenId($select);
		$data = $this->assign('list', $list);
		return view();
	}
}