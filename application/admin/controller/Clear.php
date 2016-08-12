<?php
namespace app\admin\controller;

class Clear extends Base{
	public function index(){
		\think\Cache::clear();
		return $this->success('清除成功');
	}
}