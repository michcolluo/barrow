<?php
namespace app\admin\Model;

use think\Model;

class UsersModel extends Model {
	protected $table = 'bar_users';
	protected $auth = [];
	
	public function login($username = '', $password){
		if($username == ''){
			return config('empty_username');
		}
		
		$data = $this->where(['username' => $username, 'password' => $password])->find();
		if($data != ''){
			if($data['islock']){
				return config('error_islock');
			}else{
				return $data;	
			}
		}else{
			return config('error_password');
		}
	}
	
	public function get_user_info($username = ''){
		$data = $this->where(['username' => $username])->find();	
		return $data;
	}
	
	public function get_authority($username = ''){
		$data = $this->where(['username' => $username])->find();
		$roleid = $data['roleid'];
		$data = Db('bar_role')->where('id', $roleid)->find();
		$this->auth = json_decode($data['authority']);
		$this->clert_temp_cache();
		return [
			'__TEST1__' => $this->get_auth(1),
			'__TEST2__' => $this->get_auth(2),
			'__TEST3__' => $this->get_auth(3),
			'__TEST4__' => $this->get_auth(4),
			'__TEST5__' => $this->get_auth(5),
		];	
	}
	
    private function get_auth($id){
    	if(strval(array_search($id, $this->auth)) == ''){
    		return "disabled";
    	}else{
    		return '';
    	}
    }
    
	private function clert_temp_cache()
	{
	    // array_map(‘unlink‘, glob(TEMP_PATH . ‘/*.php‘));
	    // rmdir(TEMP_PATH);
	    $a=glob(TEMP_PATH . '\*.php');
	    // halt($a);
	    if(!empty($a)){
	        array_map('unlink', $a);
	        rmdir(TEMP_PATH);
	    }
	    // $this->success(‘清除成功:)‘,‘admin/entry/index‘);
	}
}