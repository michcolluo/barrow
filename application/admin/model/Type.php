<?php
namespace app\admin\Model;

use think\Model;

class Type extends Model {
	protected $table = 'bar_type';
	
    public function get_color_list(){
        try {
            return $this->where('type', 'color')->select();
        } catch (Exception $e) {
            return [];
        }
    }

    public function get_goods_type_list(){
        return $this->where('type', 'goods_type')->select();
    }
}