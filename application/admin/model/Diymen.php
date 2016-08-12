<?php
namespace app\admin\model;
use think\Model;

class Diymen extends Model{
	protected $auto = ['update_time','path','is_show'];
	protected $insert = ['create_time'];

	protected function setPathAttr(){
		$fid = input('post.fid');
		if($fid != 0){
			$data = $this->where('id', $fid)->find();
			$path = $data['path'] . '-' . $fid;
		}else{
			$path = 0;
		}
		return $path;
	}

	protected function setIsShowAttr(){
		return input('?post.is_show') ? 1 : 0;
	}
}