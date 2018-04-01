<?php
namespace app\admin\Model;

use think\Model;
use think\Db;

class User extends Model {
	protected $table = 'bar_users';
	protected $auth = [];
	
	/**
	 * 系统加密方法
	 * @param string $data 要加密的字符串
	 * @param string $key  加密密钥
	 * @param int $expire  过期时间 单位 秒
	 * return string
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	private function think_encrypt($data, $key = '', $expire = 0) {
	    $key  = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
	    $data = base64_encode($data);
	    $x    = 0;
	    $len  = strlen($data);
	    $l    = strlen($key);
	    $char = '';
	    for ($i = 0; $i < $len; $i++) {
	        if ($x == $l) $x = 0;
	        $char .= substr($key, $x, 1);
	        $x++;
	    }
	    $str = sprintf('%010d', $expire ? $expire + time():0);
	    for ($i = 0; $i < $len; $i++) {
	        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
	    }
	    return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
	}
	/**
	 * 系统解密方法
	 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
	 * @param  string $key  加密密钥
	 * return string
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	private function think_decrypt($data, $key = ''){
	    $key    = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
	    $data   = str_replace(array('-','_'),array('+','/'),$data);
	    $mod4   = strlen($data) % 4;
	    if ($mod4) {
	       $data .= substr('====', $mod4);
	    }
	    $data   = base64_decode($data);
	    $expire = substr($data,0,10);
	    $data   = substr($data,10);
	    if($expire > 0 && $expire < time()) {
	        return '';
	    }
	    $x      = 0;
	    $len    = strlen($data);
	    $l      = strlen($key);
	    $char   = $str = '';
	    for ($i = 0; $i < $len; $i++) {
	        if ($x == $l) $x = 0;
	        $char .= substr($key, $x, 1);
	        $x++;
	    }
	    for ($i = 0; $i < $len; $i++) {
	        if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
	            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
	        }else{
	            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
	        }
	    }
	    return base64_decode($str);
	}
	
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
	    $a=glob(TEMP_PATH . '\*.php');
	    if(!empty($a)){
	        array_map('unlink', $a);
	        rmdir(TEMP_PATH);
	    }
	}
	
	public function get_user_list(){
		return $this->query('select *,(select role_name from bar_role where bar_role.id = bar_users.roleid) as role_name from bar_users');	
	}
}