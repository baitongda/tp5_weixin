<?php
namespace app\admin\validate;
use think\Validate;

class Keyword extends Validate{
	protected $rule = [
		'keyword'=>'require|unique:keyword',
		'pid'=>'require',
		'module'=>'require',
	];

	protected $message = [
		'keyword.require'=>'关键词不能为空',
		'keyword.unique'=>'关键词已经存在',
		'pid.require'=>'PID不能为空',
		'module.require'=>'module不能为空',
	];

	protected $scene = [
		'edit'=>['keyword'],
	];

}