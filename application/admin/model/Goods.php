<?php
namespace app\admin\Model;

use think\Model;
use think\Db;

class Goods extends Model {
	protected $table = 'bar_goods';
	
	public function import_json($json){
		$result = array();
		$arr_temp = array();
		$arr = json_decode($json,true);
		foreach ($arr as $value) {
			$arr_temp['goods_code'] = $value['goodscode'];
			$arr_temp['goods_name'] = $value['goodsname'];
			array_push($result, $arr_temp);
		}
		if ($result){
			echo $this->insertAll($result);
		}
//		return $arr;	
	}
}