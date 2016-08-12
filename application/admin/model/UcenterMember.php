<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace app\admin\model;
use think\Model;
/**
 * 会员模型
 */
class UcenterMember extends Model{

	/* 用户模型自动完成 */
	protected $auto = ['update_time','status'];

	protected $insert = ['create_time','reg_ip','password'];

	protected function setRegIpAttr(){
		return get_client_ip();
	}

	protected function setStatusAttr(){
		return true;
	}

	protected function setPasswordAttr($value){
		return think_ucenter_md5($value, config('UC_AUTH_KEY'));
	}

	// 定义关联方法
	public function authGroupAccess(){
		return $this->hasOne('AuthGroupAccess','uid');
	}

	/**
	 * 根据配置指定用户状态
	 * @return integer 用户状态
	 */
	protected function getStatus(){
		return true; //TODO: 暂不限制，下一个版本完善
	}

	/**
	 * 注册一个新用户
	 * @param  string $username 用户名
	 * @param  string $password 用户密码
	 * @param  string $email    用户邮箱
	 * @param  string $mobile   用户手机号码
	 * @return integer          注册成功-用户信息，注册失败-错误编号
	 */
	public function register($username, $password, $email, $mobile){
		$data = array(
			'username' => $username,
			'password' => $password,
			'email'    => $email,
			'mobile'   => $mobile,
		);
		/* 添加用户 */
		if($uid = $this->validate(true)->save($data)){
			return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
		} else {
			return $this->getError(); //错误详情见自动验证注释
		}
	}

	/**
	 * 用户登录认证
	 * @param  string  $username 用户名
	 * @param  string  $password 用户密码
	 * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
	 * @return integer           登录成功-用户ID，登录失败-错误编号
	 */
	public function login($username, $password, $type = 1){
		$map = array();
		switch ($type) {
			case 1:
				$map['username'] = $username;
				break;
			case 2:
				$map['email'] = $username;
				break;
			case 3:
				$map['mobile'] = $username;
				break;
			case 4:
				$map['id'] = $username;
				break;
			default:
				return 0; //参数错误
		}
		/* 获取用户数据 */
		$user = $this->get($map);
		if($user){
			/* 验证用户密码 */
			if(think_ucenter_md5($password, config('UC_AUTH_KEY')) === $user->password){
				$this->updateLogin($user->id); //更新用户登录信息
				return $user->id; //登录成功，返回用户ID
			} else {
				return -2; //密码错误
			}
		} else {
			return -1; //用户不存在或被禁用
		}
	}

	/**
	 * 获取用户信息
	 * @param  string  $uid         用户ID或用户名
	 * @param  boolean $is_username 是否使用用户名查询
	 * @return array                用户信息
	 */
	public function info($uid, $is_username = false){
		$map = array();
		if($is_username){ //通过用户名获取
			$map['username'] = $uid;
		} else {
			$map['id'] = $uid;
		}

		$user = $this->where($map)->field('id,username,email,mobile,status')->find();
		if(is_array($user) && $user['status'] == 1){
			return array($user['id'], $user['username'], $user['email'], $user['mobile']);
		} else {
			return -1; //用户不存在或被禁用
		}
	}

	/**
	 * 检测用户信息
	 * @param  string  $field  用户名
	 * @param  integer $type   用户名类型 1-用户名，2-用户邮箱，3-用户电话
	 * @return integer         错误编号
	 */
	public function checkField($field, $type = 1){
		$data = array();
		switch ($type) {
			case 1:
				$data['username'] = $field;
				break;
			case 2:
				$data['email'] = $field;
				break;
			case 3:
				$data['mobile'] = $field;
				break;
			default:
				return 0; //参数错误
		}

		return $this->create($data) ? 1 : $this->getError();
	}

	/**
	 * 更新用户登录信息
	 * @param  integer $uid 用户ID
	 */
	protected function updateLogin($uid){
		$data = array(
			'id'              => $uid,
			'last_login_time' => time(),
			'last_login_ip'   => get_client_ip(1),
		);
		$this->update($data);
	}

	/**
	 * 更新用户信息
	 * @param int $uid 用户id
	 * @param string $password 密码，用来验证
	 * @param array $data 修改的字段数组
	 * @return true 修改成功，false 修改失败
	 * @author huajie <banhuajie@163.com>
	 */
	public function updateUserFields($uid, $password, $data){
		if(empty($uid) || empty($password) || empty($data)){
			$this->error = '参数错误！';
			return false;
		}

		//更新前检查用户密码
		if(!$this->verifyUser($uid, $password)){
			$this->error = '验证出错：密码不正确！';
			return false;
		}

		//更新用户信息
		if($this->validate(true)->save($data,['id'=>$uid])){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 更新用户信息，不需要进行密码验证
	 * @author ning
	 * @DateTime 2016-06-24T23:25:38+0800
	 * @param    [type]                   $uid  [description]
	 * @param    [type]                   $data [description]
	 * @return   [type]                         [description]
	 */
	public function updateUserFieldsNotCheck($uid, $data){
		if(empty($uid) || empty($data)){
			$this->error = '参数错误';
			return false;
		}

		// 更新用户信息
		if($this->validate('UcenterMember.edit')->save($data,['id'=>$uid])){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 验证用户密码
	 * @param int $uid 用户id
	 * @param string $password_in 密码
	 * @return true 验证成功，false 验证失败
	 * @author huajie <banhuajie@163.com>
	 */
	protected function verifyUser($uid, $password_in){
		$password = $this->getFieldById($uid, 'password');
		if(think_ucenter_md5($password_in, config('UC_AUTH_KEY')) === $password){
			return true;
		}
		return false;
	}

}
