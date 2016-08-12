<?php
namespace app\admin\validate;
use think\Validate;

class UcenterMember extends Validate{
	protected $rule = [
		'username'=>'length:1,30|unique:ucenter_member|checkDenyMember:',
		'email'=>'email|length:1,32|unique:ucenter_member',
		'mobile'=>['regex'=>'/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{9}$|147[0-9]{8}$/','unique'=>'ucenter_member'],
	];

	protected $message = [
		'username.length'=>'用户名长度不合法',
		'username.unique'=>'用户名被占用',
		'email.email'=>'邮箱格式错误',
		'email.length'=>'邮箱长度不合法',
		'email.unique'=>'邮箱被占用',
		'mobile.regex'=>'手机格式不正确',
		'mobile.checkDenyMobile'=>'手机禁止注册',
		'mobile.unique'=>'手机号被占用',
	];

	protected $scene = [
		'edit'=>['username'=>'length:1,30']
	];


	/**
	 * 检测用户名是不是被禁止注册
	 * @param  string $username 用户名
	 * @return boolean          ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyMember($value){
		return true; //TODO: 暂不限制，下一个版本完善
	}

}