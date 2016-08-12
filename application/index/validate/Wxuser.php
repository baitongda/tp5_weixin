<?php
namespace app\index\validate;

use think\Validate;

class Wxuser extends Validate{
	protected $rule = [
		'openid'=>'require|unique:wxuser|length:28',
		'thumb'=>'require',
		'name'=>'require',
	];

	protected $message = [
		'openid.require'=>'用户openid不能为空',
		'openid.unique'=>'用户openid已经存在',
		'openid.length'=>'用户openid不正确',
		'thumb'=>'用户头像不能为空',
		'name'=>'用户昵称不能为空',
	];

}