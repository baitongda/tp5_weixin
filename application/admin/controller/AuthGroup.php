<?php
namespace app\admin\controller;
use app\admin\model\AuthGroup as AuthGroupModel;

class AuthGroup extends Base{
	/**
	 * 角色列表
	 * @author ning
	 * @DateTime 2016-06-22T22:37:27+0800
	 * @return   [type]                   [description]
	 */
	public function index(){
		$roles = \think\Db::name('auth_group')->where('status',1)->paginate('10');
		$this->assign('roles', $roles);
		return view('index');
	}

	/**
	 * 添加角色
	 * @author ning
	 * @DateTime 2016-06-22T23:15:25+0800
	 */
	public function add(){
		if(request()->isPost()){
			$authGroupModel = new AuthGroupModel;
			if($id = $authGroupModel->validate(true)->save(input('post.'))){
				return $this->success('添加成功',url('auth_group/index'));
			}else{
				return $this->error($authGroupModel->getError());
			}
		}else{
			return view('add');
		}
	}

	/**
	 * 编辑角色
	 * @author ning
	 * @DateTime 2016-06-22T23:15:58+0800
	 * @return   [type]                   [description]
	 */
	public function edit(){
		$authGroupModel = new AuthGroupModel;
		if(request()->isPost()){
			$id = input('?param.id') ? input('param.id') : '';
			if(!$id || $id==1){
				return $this->error('参数错误');
			}
			if($authGroupModel->validate(true)->save(input('post.'),['id'=>input('post.id')])){
				return $this->success('修改成功',url('index'));
			}else{
				return $this->error('修改失败');
			}
		}else{
			$id = input('?param.id') ? input('param.id') : '';
			if(!$id){
				return $this->error('参数错误');
			}
			$data = $authGroupModel->field('id,title,description')->where('id',$id)->find();
			$this->assign('data',$data);
			return view('edit');
		}
	}

	/**
	 * 删除角色
	 * @author ning
	 * @DateTime 2016-06-23T10:23:24+0800
	 * @return   [type]                   [description]
	 */
	public function del(){
		$id = input('?param.id') ? input('param.id') : '';
		if(!$id || $id == 1){
			return $this->error('参数错误');
		}
		$authGroupModel = new AuthGroupModel;
		if($authGroupModel->where('id',$id)->delete()){
			return $this->success('删除成功');
		}else{
			return $this->error('删除失败');
		}
	}

	/**
	 * 资源管理
	 * @author ning
	 * @DateTime 2016-06-23T21:34:00+0800
	 * @return   [type]                   [description]
	 */
	public function resource(){
		if(request()->isPost()){
			$id = input('?post.id') ? input('post.id') : '';
			if(!$id || $id == 1){
				return $this->error('参数错误');
			}
			$authGroupModel = new AuthGroupModel;
			if($authGroupModel->isUpdate(true)->save(['rules'=>''],['id'=>$id])){
				$this->getSidebar();
				session('_auth_list_'.session('user_auth')['uid'].'1', null);
				return $this->success('修改成功');
			}else{
				return $this->error($authGroupModel->getError());
			}
		}else{
			// dump(session('sidebar'));exit;
			$id = input('?param.id') ? input('param.id') : '';
			if(!$id){
				return $this->error('参数错误');
			}
			$data = \think\Db::name('auth_group')->field('id,title,rules')->where('id',$id)->find();
			$authRuleData = \think\Db::name('auth_rule')->field('id,name,title,pid,path')->where('type',1)->order('path,sort asc')->select();
			$resource = [];
			foreach ($authRuleData as $key => $value) {
				$path = explode('-', $value['path']);
				switch(count($path)){
					case 1:
						$resource[$value['id']] = $value;
						break;
					case 2:
						$resource[$path[1]]['child'][$value['id']] = $value;
						break;
					case 3:
						$resource[$path[1]]['child'][$path[2]]['child'][$value['id']] = $value;
						break;
				}
			}	
			$this->assign('resource', $resource);
			$this->assign('data', $data);
			return view('resource');
		}
	}
}