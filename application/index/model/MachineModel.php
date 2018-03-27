<?php
namespace app\index\model;

use think\Model;

class MachineModel extends Model{
	protected $table = 'bar_machine';
	
	public function getAllMachine(){
		$data = $this->select();
		return $data;
	}
	
	public function savePosition($arr){
		return $this->saveAll($arr);
	}
	
	public function get_machine_status($machine_code = ''){
		if ($machine_code = ''){
			return '';
		}
		$data = $this::get(function($query){
			$query->where('machine_code', 
				$machine_code);
		});
		return $data->status;
	}
}
