<?php
namespace app\admin\validate;
use think\Validate;

class Img extends Validate{
	protected $rule = [
		'keyword'=>'require|unique:img',
		'text'=>'require',
		'pic'=>'require',
		'info'=>'require',
		'title'=>'require',
	];

	protected $message = [
		'keyword.require'=>'关键词不能为空123',
		'keyword.unique'=>'关键词已经存在',
		'text.require'=>'简介不能为空',
		'pic.require'=>'封面图片不能为空',
		'info.require'=>'图文详细内容不能为空',
		'title.require'=>'标题不能为空',
	];

}