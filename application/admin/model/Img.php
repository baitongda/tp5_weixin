<?php
namespace app\admin\model;
use think\Model;

class Img extends Model{
	protected $auto = ['update_time','showpic'];
	protected $insert = ['create_time'];

	protected function setShowpicAttr(){
		return input('?post.showpic') ? 1 : 0;
	}
}