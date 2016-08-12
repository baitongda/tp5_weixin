<?php
namespace app\admin\controller;
use app\admin\api\UserApi;

class User extends Base{
	/**
	 * 退出登录状态
	 * @author ning
	 * @DateTime 2016-06-22T13:50:25+0800
	 * @return   [type]                   [description]
	 */
	public function logout(){
		session(null);
		return $this->success('退出成功',url('index/index'));
	}

	/**
	 * 修改密码
	 * @author ning
	 * @DateTime 2016-06-24T11:25:28+0800
	 * @return   [type]                   [description]
	 */
	public function changePwd(){
		if(request()->isPost()){
			// if(session('user_auth')['uid'] == 1){
			// 	return $this->error('禁止修改管理员密码');
			// }
			$oldPassword = input('?post.oldPassword') ? input('post.oldPassword') : '';
			if(!$oldPassword){
				return $this->error('请填写原密码');
			}
			$password = input('?post.password') ? input('post.password') : '';
			if(!$password){
				return $this->error('请填写新密码');
			}
			$repassword = input('?post.repassword') ? input('post.repassword') : '';
			if(!$repassword){
				return $this->error('请填写确认密码');
			}
			if($password != $repassword){
				return $this->error('新密码和确认密码不一致');
			}
			$data = ['password'=>$password];
			$user = new UserApi;
	        $res    =   $user->updateInfo(session('user_auth')['uid'], $oldPassword, $data);
	        if($res['status']){
	            return $this->success('修改密码成功！');
	        }else{
	            return $this->error($res['info']);
	        }
		}else{
			return view('changePwd');
		}
	}

	/**
	 * 后台用户列表
	 * @author ning
	 * @DateTime 2016-06-24T22:33:16+0800
	 * @return   [type]                   [description]
	 */
	public function index(){
		$list = \think\Db::name('ucenter_member')->field('id,username')->where('status',1)->paginate(10);
		$this->assign('list', $list);
		return view('index');
	}

	/**
	 * 添加用户
	 * @author ning
	 * @DateTime 2016-06-24T22:54:28+0800
	 */
	public function add(){
		if(request()->isPost()){
			$username = input('?post.username') ? input('post.username') : '';
			if(!$username){
				return $this->error('请填写用户名');
			}
			$group_id = input('?post.group_id') ? input('post.group_id') : '';
			if(!$group_id){
				return $this->error('请创建角色',url('auth_group/add'));
			}
			$user = new UserApi;
			$res = $user->register($username, '123456');
			if($res>0){
				$insertData = [
					'uid'=>$res,
					'group_id'=>$group_id
				];	
				\think\Db::name('auth_group_access')->insert($insertData);
				return $this->success('添加成功',url('index'));
			}else{
				return $this->error($res);
			}

		}else{
			$authGroupData = \think\Db::name('auth_group')->field('id,title')->where('id','neq',1)->where('status',1)->select();
			$this->assign('authGroupData',$authGroupData);
			return view('add');
		}
	}

	/**
	 * 编辑用户
	 * @author ning
	 * @DateTime 2016-06-24T22:54:56+0800
	 * @return   [type]                   [description]
	 */
	public function edit(){
		if(request()->isPost()){
			$id = input('?post.id') ? input('post.id') : '';
			if(!$id || $id==1){
				return $this->error('参数错误');
			}
			$group_id = input('?post.group_id') ? input('post.group_id') : '';
			if(!$group_id){
				return $this->error('请创建角色',url('auth_group/add'));
			}
			$user = new UserApi;
			$data = ['username'=>input('post.username')];
			$res = $user->updateInfoNotCheck($id, $data);
			if($res['status']){
				\think\Db::name('auth_group_access')->where('uid',$id)->update(['group_id'=>$group_id]);
				return $this->success('修改成功',url('index'));
			}else{
				return $this->error($res['info']);
			}
		}else{
			$id = input('?param.id') ? input('param.id') : '';
			if(!$id || $id == 1){
				return $this->error('参数错误');
			}
			$data = \think\Db::name('ucenter_member')->where('id',$id)->find();
			$group_id = \think\Db::name('auth_group_access')->where('uid', $id)->find();
			$data['group_id'] = $group_id['group_id'];
			$authGroupData = \think\Db::name('auth_group')->field('id,title')->where('id','neq',1)->where('status',1)->select();
			$this->assign('authGroupData',$authGroupData);
			$this->assign('data',$data);
			return view('edit');
		}
	}

	/**
	 * 删除用户
	 * @author ning
	 * @DateTime 2016-06-24T22:55:15+0800
	 * @return   [type]                   [description]
	 */
	public function del(){
		$id = input('?param.id') ? input('param.id') : '';
		if(!$id || $id == 1){
			return $this->error('参数错误');
		}
		if(\think\Db::name('ucenter_member')->where('id',$id)->delete()){
			return $this->success('删除成功');
		}else{
			return $this->error('删除失败');
		}
	}

	/**
	 * 重置密码
	 * @author ning
	 * @DateTime 2016-07-08T09:50:13+0800
	 * @return   [type]                   [description]
	 */
	public function resetPwd(){
		$id = input('?param.id') ? input('param.id') : '';
		if(!$id){
			return $this->error('参数错误');
		}
		$user = new UserApi;
		$data = ['password'=>'123456'];
		$res = $user->updateInfoNotCheck($id,$data);
        if($res['status']){
            return $this->success('重置密码成功！');
        }else{
            return $this->error($res['info']);
        }
	}
}