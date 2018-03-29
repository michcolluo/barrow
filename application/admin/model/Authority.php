<?php
namespace app\admin\Model;

use think\Model;

class Authority extends Model {
	public function getChildrenId($list, $auth=array(), $pid=0, $level=1){
		//由父节点id获取所有子节点
		static $arr = array();
		foreach ($list as $key => $value){
			if ($value['pid'] == $pid){
				
				$value['level'] = $level;
				if (empty($auth) === false){
					if(strval(array_search($value['id'],$auth)) == ''){
						$value['check'] = '';
					}else{
						$value['check'] = 'checked';
					}
				}else{
					$value['check'] = '';	
				}
				$arr[] = $value;
				$this->getChildrenId($list, $auth, $value['id'], $level+1);
			}	
		}	
		return $arr;
	}
}