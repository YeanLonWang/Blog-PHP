<?php
namespace Lib;
class Upload{
	private $error;  //保存错误信息
	private $path;   //上传的路径
	private $size;   //上传大小
	private $type;   //上传类型

	public function __construct($path,$size,$type){
		$this->path=$path;
		$this->size=$size;
		$this->type=$type;
	}
	// 显示错误
	public function getError(){
		return $this->error;
	}
	/*
	 *文件上传的方法
	 *@param $files array $_FILES[]
	 *@return string | false 成功返回图片地址,失败返回false
	 */
	public function uploadOne($files){
		if($files['error']!=0){
			switch($files['error']){
				case 1:
                    $this->error='超过了配置文件允许的大小'.ini_get('upload_max_filesize');
                    break;
                case 2:
                    $this->error='超过了表单允许的最大值';
                    break;
                case 3:
                    $this->error='只有部分文件上传，没有完全上传';
                    break;
                case 4:
                    $this->error='没有文件上传';
                    break;
                case 6:
                    $this->error='找不到临时文件';
                    break;
                case 7:
                    $this->error='文件写入失败';
                    break;
                default:
                    $this->error='未知错误';
			}
			return false;
		}
		// 验证格式
		$finfo=finfo_open(FILEINFO_MIME_TYPE);
		$info=finfo_file($finfo,$files['tmp_name']);
		if(!in_array($info,$this->type)){
			$this->error='文件类型有错,允许的类型有: '.implode(',',$this->type);
			return false;
		}
		// 验证大小
		if($files['size']>$this->size){
			$this->error='文件不能超过'.number_format($this->size/1024,2).'k';
			return false;
		}
		// 验证是否是http上传
		if(!is_uploaded_file($files['tmp_name'])){
			$this->error='文件必须是HTTP上传';
			return false;
		}
		// 创建文件夹
		$foldername=date('Y-m-d');//文件夹名称
		$folderpath=$this->path.$foldername;  //文件夹路径
		if(!file_exists($folderpath)){
			// $folderpath='.'.$folderpath;
			// echo $folderpath;die();
			mkdir($folderpath);
		}

		// 文件上传
		$filename=uniqid().strchr($files['name'],'.');
		$filepath=$folderpath.'/'.$filename;
			// var_dump($filepath);die();
		if(move_uploaded_file($files['tmp_name'],$filepath)){
			return "{$foldername}/{$filename}";
		}else{
			$this->error='文件上传失败';
			return false;
		}
	}

}
