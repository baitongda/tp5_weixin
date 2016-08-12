<?php
namespace app\admin\controller;
use app\admin\model\AuthRule as AuthRuleModel;

class AuthRule extends Base{
	/**
	 * 权限列表
	 * @author ning
	 * @DateTime 2016-06-23T13:20:01+0800
	 * @return   [type]                   [description]
	 */
	public function index(){
		$authRuleData = \think\Db::query('select id,name,title,pid,sort,path,type,concat(path,"-",id) as bpath from auth_rule where status=1 order by bpath');
		foreach ($authRuleData as $key => $value) {
			$authRuleData[$key]['count'] = count(explode('-', $value['path']));
		}
		$this->assign('data', $authRuleData);
		return view('index');
	}

	/**
	 * 添加权限
	 * @author ning
	 * @DateTime 2016-06-22T22:26:12+0800
	 */
	public function add(){
		if(request()->isPost()){
			$authRuleModel = new AuthRuleModel;
			if($id = $authRuleModel->validate(true)->save(input('post.'))){
				// 给管理员添加全部权限
				$authGroupData = \think\Db::name('auth_group')->field('rules')->where('id',1)->find();
				$rules = $authGroupData['rules'] . ',' . $id;
				\think\Db::name('auth_group')->where('id',1)->update(['rules'=>$rules]);
				$this->getSidebar();
				session('_auth_list_'.session('user_auth')['uid'].'1', null);
				return $this->success('添加成功',url('index'));
			}else{
				return $this->error($authRuleModel->getError());
			}
		}else{
			$pidData = \think\Db::query('select id,title,path,concat(path,"-",id) as bpath from auth_rule where status=1 and type=1 and is_show=1 order by bpath');
			foreach ($pidData as $key => $value) {
				$pidData[$key]['count'] = count(explode('-', $value['path']));
			}
			$this->assign('pidData', $pidData);
			return view('add');
		}
	}

	/**
	 * 编辑权限
	 * @author ning
	 * @DateTime 2016-06-23T21:33:15+0800
	 * @return   [type]                   [description]
	 */
	public function edit(){
		if(request()->isPost()){
			$id = input('?post.id') ? input('post.id') : '';
			if(!$id){
				return $this->error('参数错误');
			}
			if(in_array($id, explode(',', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17'))){
				return $this->error('改权限不允许编辑');
			}
			$authRuleModel = new AuthRuleModel;
			if($authRuleModel->validate(true)->save(input('post.'), ['id'=>$id])){
				$this->getSidebar();
				session('_auth_list_'.session('user_auth')['uid'].'1', null);
				return $this->success('修改成功',url('index'));
			}else{
				return $this->error($authRuleModel->getError());
			}			
		}else{
			$id = input('?param.id') ? input('param.id') : '';
			if(!$id){
				return $this->error('参数错误');
			}
			$data = \think\Db::name('auth_rule')->field('id,name,title,type,condition,pid,sort,is_show')->where('id',$id)->find();
			$pidData = \think\Db::query('select id,title,path,concat(path,"-",id) as bpath from auth_rule where status=1 and type=1 and is_show=1 order by bpath');
			foreach ($pidData as $key => $value) {
				$pidData[$key]['count'] = count(explode('-', $value['path']));
			}
			$this->assign('pidData', $pidData);			
			$this->assign('data',$data);
			return view('edit');
		}
	}

	/**
	 * 删除权限
	 * @author ning
	 * @DateTime 2016-06-26T11:12:01+0800
	 * @return   [type]                   [description]
	 */
	public function del(){
		$id = input('?param.id') ? intval(input('param.id')) : '';
		if(!$id){
			return $this->error('参数错误');
		}
		if(in_array($id, explode(',', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17'))){
			return $this->error('该权限不允许编辑');
		}
		$ids = [$id];

		$child1 = \think\Db::table('auth_rule')->field('id')->where('pid',$id)->select();
		if($child1){
			foreach ($child1 as $key => $value) {
				$ids[] = $value['id'];
				$child2 = \think\Db::table('auth_rule')->field('id')->where('pid',$value['id'])->select();
				if($child2){
					foreach ($child2 as $k2 => $v2) {
						$ids[] = $v2['id'];
					}
				}
			}
		}
		
		\think\Db::table('auth_rule')->where('id','in',$ids)->delete();

		$rules = \think\Db::table('auth_group')->field('id,rules')->select();
		foreach ($rules as $rule) {
			$rulesArr = explode(',', $rule['rules']);
			$rules = implode(',', array_diff($rulesArr, $ids));
			\think\Db::table('auth_group')->where('id', $rule['id'])->update(['rules'=>$rules]);
		}
		$this->getSidebar();
		session('_auth_list_'.session('user_auth')['uid'].'1', null);
		return $this->success('删除成功');
	}

}