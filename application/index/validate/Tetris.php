<?php
namespace app\index\validate;

use think\Validate;

class Tetris extends Validate{
	protected $rule = [
		'wxuid'=>'require|unique:tetris',
		'score'=>'number',
	];

	protected $message = [
		'wxuid.require'=>'微信ID不能为空',
		'wxuid.require'=>'微信ID已经存在',
		'score.number'=>'分数格式不正确',
	];

	protected $scene = [
		'edit'=>['score'],
	];
}