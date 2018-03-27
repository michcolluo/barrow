<?php
namespace app\index\controller;

use app\index\model\MachineModel;

class Machine extends \think\Controller{
	public function index(){
		//$data = db('bar_machine')->select();
		//dump($data);	
		$machine_model = new MachineModel();
		$data = $machine_model->getAllMachine();
		$this->assign('machine_list', $data);
		$data = db('bar_machine_container')->select();
		$this->assign('box', $data[0]);
		return view();
	}
	
	public function getList(){
		//$data = db('bar_machine')->select();
		$machine_model = new MachineModel();
		$data = $machine_model->getAllMachine();
		
		$this->assign('machine_list', $data);
		return view();
	}
	
	public function getList_api(){
		//$data = db('bar_machine')->select();
		$machine_model = new MachineModel();
		$data = $machine_model->getAllMachine();
		return json($data);
	}
	
	private function getType(){
		$data = db('bar_machine_type')->select();
		$this->assign('type', $data);	
	}
	
	private function getStatus(){
		$data = db('bar_type')->where('type="machine_status"')->select();
		$this->assign('status', $data);	
	}
	
	public function add(){
		$this->getType();
		$this->getStatus();
		return view();
	}
	
	public function insert(){
		$post = request()->post();
		$result = db('bar_machine')->insert($post);
		echo $result;
	}
	
	public function edit($id = ''){
		if($id == ''){
			$this->redirect('machine/index');
		}
		$data = db('bar_machine')->find($id);
		if($data == ''){
			$this->redirect('machine/index');
		}
		$this->assign('data', $data);
		$this->getType();
		$this->getStatus();
		return view();
	}
	
	public function update(){
		$post = request()->post();
//		$result = db('bar_machine')->update($post);
		if(db('bar_machine')->update($post) !== false){
			return 1;	
		}else{
			return 0;	
		}
	}
	
	public function delete($id = ''){
		if($id == ''){
			exit;
		}
		$result = db('bar_machine')->delete($id);
		return $result;
	}
	
	public function save_position(){
		$request = request();
		$arr = [];
		$arr = $request->post();
		$machine_model = new MachineModel();
//		$result = $machine_model->savePosition($arr['ms']);
		if($machine_model->savePosition($arr['ms']) == ''){
			return 0;
		}
		return 1;
	}
	
	public function box(){
		$data = db('bar_machine_container')->select();
		if($data == ''){
			$this->redirect('machine/index');
		}
		$this->assign('data', $data[0]);
		return view();
	}
	
	public function box_save(){
		$post = request()->post();
//		$result = db('bar_machine')->update($post);
		if(db('bar_machine_container')->update($post) !== false){
			return 1;	
		}else{
			return 0;	
		}
	}
	
	public function machine_start($machine_code =  ''){
		if($machine_code = ''){
			$arr = array(
				'result' => 0,
				'body' => '设备编码不能为空!'
			);
		}
		$machine_model = new MachineModel();
		$status = $machine_model->get_machine_status($machine_code);
		return $status;
	}
	
}
