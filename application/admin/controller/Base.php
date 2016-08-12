<?php
namespace app\admin\controller;
use think\Controller;

class Base extends Controller{
	public function _initialize(){
		// 判断是否登录，没有登录跳转登录页面
		if(!session('user_auth') || !session('user_auth_sign')){
			$this->redirect(url('index/index'));
		}

		$module = $this->request->module();
		$controller = $this->request->controller();
		$action = $this->request->action();

		$auth = new \com\Auth();
		if(!$auth->check($module . '/' . $controller  . '/' . $action, session('user_auth')['uid'])){
			return $this->error('没有权限');
		}
		if(!session('sidebar')){
			$this->getSidebar();
		}
		$sidebar = session('sidebar');
		
		$activeRouter = $module.'/'.$controller.'/'.$action;

		$parent_id_1 = 0;
		$parent_id_2 = 0;
		$resource1 = \think\Db::name('auth_rule')->field('id,name,title,pid')->where('name',$activeRouter)->find();
		if($resource1){
			$this->assign('resource1', $resource1);
			$parent_id_1 = $resource1['pid'];
			$resource2 = \think\Db::name('auth_rule')->field('id,name,title,pid')->where('id',$parent_id_1)->find();
			if($resource2){
				$this->assign('resource2',$resource2);
				$parent_id_2 = $resource2['pid'];
				$resource3 = \think\Db::name('auth_rule')->field('id,name,title,pid')->where('id',$parent_id_2)->find();
				if($resource3){
					$this->assign('resource3',$resource3);
				}
			}
		}
		$this->assign('sidebar', $sidebar);
		$this->assign('uri', $activeRouter);
		$this->assign('parent_id_1', $parent_id_1);
		$this->assign('parent_id_2', $parent_id_2);
	}

	/**
	 * 获取菜单
	 * @author ning
	 * @DateTime 2016-06-22T21:44:16+0800
	 * @return   [type]                   [description]
	 */
	protected function getSidebar(){
		$authGroupAccessData = \think\Db::name('auth_group_access')->field('group_id')->where('uid',session('user_auth')['uid'])->find();
		$authGroupData = \think\Db::name('auth_group')->field('rules')->where('id',$authGroupAccessData['group_id'])->find();
		$authRuleData = \think\Db::name('auth_rule')->field('id,name,title,pid,sort,path')->where('id','in',$authGroupData['rules'])->where('type',1)->where('status',1)->where('is_show',1)->order('path,sort asc')->select();
		$sidebar = [];
		foreach ($authRuleData as $key => $value) {
			$path = explode('-', $value['path']);
			switch(count($path)){
				case 1:
					$sidebar[$value['id']] = $value;
					break;
				case 2:
					$sidebar[$path[1]]['child'][$value['id']] = $value;
					break;
				case 3:
					$sidebar[$path[1]]['child'][$path[2]]['child'][$value['id']] = $value;
					break;
			}
		}
		session('sidebar',$sidebar);
	}
}