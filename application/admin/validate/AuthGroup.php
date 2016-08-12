<?php
namespace app\admin\validate;
use think\Validate;

class AuthGroup extends Validate{
	protected $rule = [
		'title'=>'require|unique:auth_group',
	];

	protected $message = [
		'title.require'=>'角色名称不能为空',
		'title.unique'=>'角色名称已经存在',
	];
}