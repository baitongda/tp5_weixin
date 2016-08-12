<?php
namespace app\admin\validate;
use think\Validate;

class Text extends Validate{
	protected $rule = [
		'keyword'=>'require|unique:text',
		'text'=>'require'
	];

	protected $message = [
		'keyword.require'=>'关键词不能为空',
		'keyword.unique'=>'关键词已经存在',
		'text.require'=>'内容不能为空',
	];

}