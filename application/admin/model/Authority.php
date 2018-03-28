<?php
namespace app\admin\Model;

use think\Model;

class Authority extends Model {
	public function getChildrenId($list, $pid=0, $level=1){
		//由父节点id获取所有子节点
		static $arr = array();
		foreach ($list as $key => $value){
			if ($value['pid'] == $pid){
				$value['level'] = $level;
//				$value['str'] = str_repeat('——', $value['cate_level'] - 1);
				$arr[] = $value;
				$this->getChildrenId($list, $value['id'], $level+1);
			}	
		}	
		return $arr;
	}
}