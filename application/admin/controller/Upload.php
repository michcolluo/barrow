<?php
namespace app\admin\controller;

use think\Controller;
use think\Cookie;
use think\Request;
use think\Log;

class Upload extends Controller
{
	public function upload(){
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('file');
		// 移动到框架应用根目录/public/uploads/ 目录下	
		if($file){
			$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			if($info){
				return ROOT_PATH . 'public' . DS . 'uploads\\' . $info->getSaveName();
			}else{
			// 上传失败获取错误信息
				return $file->getError();
			}
		}
	}
	
	public function import_goods(){
		$filename = $this->upload();
		$text = file_get_contents($filename);   
		define('UTF32_BIG_ENDIAN_BOM', chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));  
		define('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));  
		define('UTF16_BIG_ENDIAN_BOM', chr(0xFE) . chr(0xFF));  
		define('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));  
		define('UTF8_BOM', chr(0xEF) . chr(0xBB) . chr(0xBF));  
		$first2 = substr($text, 0, 2);  
		$first3 = substr($text, 0, 3);  
		$first4 = substr($text, 0, 3);  
		$encodType = "";  
		if ($first3 == UTF8_BOM)  
		    $encodType = 'UTF-8 BOM';  
		else if ($first4 == UTF32_BIG_ENDIAN_BOM)  
		    $encodType = 'UTF-32BE';  
		else if ($first4 == UTF32_LITTLE_ENDIAN_BOM)  
		    $encodType = 'UTF-32LE';  
		else if ($first2 == UTF16_BIG_ENDIAN_BOM)  
		    $encodType = 'UTF-16BE';  
		else if ($first2 == UTF16_LITTLE_ENDIAN_BOM)  
		    $encodType = 'UTF-16LE';  
		  
		                        //下面的判断主要还是判断ANSI编码的·  
		if ($encodType == '') {//即默认创建的txt文本-ANSI编码的  
			$content = iconv("GBK", "UTF-8", $text);  
		} else if ($encodType == 'UTF-8 BOM') {//本来就是UTF-8不用转换  
		    $content = $text;  
		} else {//其他的格式都转化为UTF-8就可以了  
			$content = iconv($encodType, "UTF-8", $text);  
		}

		$goods = model('goods');
		return $goods->import_json($content);
	}
}

