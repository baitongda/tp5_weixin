<?php
namespace app\admin\validate;
use think\Validate;

class AuthRule extends Validate{
	protected $rule = [
		'title'=>'require|unique:auth_rule',
	];

	protected $message = [
		'title.require'=>'名称不能为空',
		'title.unique'=>'名称已经存在了',
	];
}