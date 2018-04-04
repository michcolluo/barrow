<?php
namespace app\admin\controller;

use think\Controller;
use think\Cookie;
use think\Request;
use think\Log;

class Goods extends Controller
{
		
	public function index()
	{
    	$goods = model('goods');
    	$data = $goods->select();
    	$this->assign('goodslist', $data);
    	$this->assign('goods_count', count($data));
    	
    	$username = Cookie::get('username','barrow_');
    	if($username == ''){
    		$this->redirect('login/index');
    	}
    	$user_model = model('user');
    	$user_data = $user_model->get_user_info($username);
    	$this->assign('user', $user_data);
    	return view('index', [], $user_model->get_authority($username));	
	}
	
	public function get_list(){
		$post = request()->post();
    	$goods = model('goods');
    	$fields='*,"" as checkbox,"" as action';
    	if($post){
	    	if($post['goods_name'] != ''){
    			$datacount = $goods->where('goods_name','like', '%'.$post['goods_name'].'%')->count();
	    		$data = $goods->field($fields)->where('goods_name','like', '%'.$post['goods_name'].'%')->select();
			}else{
				$datacount = $goods->count();
	    		$data = $goods->field($fields)->select();
		}
		}else{
			$datacount = $goods->count();
    		$data = $goods->field($fields)->select();
		}
		$result = [
			"draw" => 1,
			"recordsTotal" => $datacount,
			"recordsFiltered" => $datacount,
			"data" => $data
		];
    	echo json_encode($result);
	}
	
	public function add(){
	    $type = model('type');
	    $this->assign('typelist', $type->get_goods_type_list());
        $this->assign('colorlist', $type->get_color_list());
		return view();
	}

	public function insert(){
	    $post = request()->post();
	    $goods = model('goods');
	    $result = $goods->insert($post);
	    $jarr = array();
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

	public function edit($id = ''){
        return view();
    }

    public function update(){

    }
}

