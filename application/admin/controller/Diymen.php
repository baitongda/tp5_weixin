<?php
namespace app\admin\controller;

use app\admin\model\Diymen as DiymenModel;
use think\Db;

class Diymen extends Base{
	/**
	 * 菜单列表
	 * @author ning
	 * @DateTime 2016-07-31T17:33:25+0800
	 * @return   [type]                   [description]
	 */
	public function index(){
		$list = Db::query('select id,title,keyword,url,fid,sort,path,is_show,concat(path,"-",id) as bpath from diymen order by bpath');
		foreach($list as $key=>$value){
			$list[$key]['count'] = count(explode('-',$value['path']));
		}
		$this->assign('list',$list);
		return view('index');
	}

	/**
	 * 添加菜单
	 * @author ning
	 * @DateTime 2016-07-31T17:53:15+0800
	 */
	public function add(){
		if(request()->isPost()){
			$diymenModel = new DiymenModel;
			if($diymenModel->validate(true)->save(input('post.'))){
				return $this->success('添加成功',url('diymen/index'));
			}else{
				return $this->error($diymenModel->getError());
			}
		}else{
			$this->getFirst();
			return view('add');
		}
	}

	/**
	 * 编辑菜单
	 * @author ning
	 * @DateTime 2016-07-31T17:53:38+0800
	 * @return   [type]                   [description]
	 */
	public function edit(){
		if(request()->isPost()){
			$id = input('?post.id') ? input('post.id') : '';
			if(!$id){
				return $this->error('参数错误');
			}
			$diymenModel = new DiymenModel;
			if($diymenModel->validate(true)->save(input('post.'),['id'=>$id])){
				return $this->success('修改成功',url('diymen/index'));
			}else{
				return $this->error($diymenModel->getError());
			}
		}else{
			$id = input('?param.id') ? input('param.id') : '';
			if(!$id){
				return $this->error('参数错误');
			}
			$data = Db::table('diymen')->where('id',$id)->find();
			$this->getFirst();
			$this->assign('data', $data);
			return view('edit');
		}
	}

	/**
	 * 删除菜单
	 * @author ning
	 * @DateTime 2016-07-31T17:53:55+0800
	 * @return   [type]                   [description]
	 */
	public function del(){
		$id = input('?param.id') ? input('param.id') : '';
		if(!$id){
			return $this->error('参数错误');
		}
		if(Db::table('diymen')->where('id',$id)->delete()){
			return $this->success('删除成功');
		}else{
			return $this->error('删除失败');
		}
	}

	/**
	 * 	获取一级菜单
	 * @author ning
	 * @DateTime 2016-07-31T18:10:40+0800
	 * @return   [type]                   [description]
	 */
	private function getFirst(){
		$diymenModel = new DiymenModel;
		$data = $diymenModel->field('id,title')->where('path','0')->where('is_show',1)->select();
		array_unshift($data, ['id'=>0,'title'=>'一级菜单']);
		$this->assign('pids', $data);
	}

	/**
	 * 生成菜单
	 * @author ning
	 * @DateTime 2016-07-31T18:41:07+0800
	 * @return   [type]                   [description]
	 */
	public function createMenu(){
		$options = [
			'token'=>config('token'),
			'encodingaeskey'=>config('encodingaeskey'),
			'appid'=>config('appid'),
			'appsecret'=>config('appsecret')
		];

		$weObj = new \com\Wechat($options);
		$menu = $weObj->getMenu();

		$diymenModel = new DiymenModel;
		$newmenu['button'] = [];
		$diymenData = $diymenModel->where('fid',0)->where('is_show',1)->limit(3)->order('sort desc')->select();
		foreach ($diymenData as $key => $vo) {
			$c = $diymenModel->where('fid',$vo['id'])->where('is_show',1)->limit(5)->order('sort desc')->select();
			if($c){
				$newmenu['button'][$key] = [
					'name'=>$vo['title'],
					'sub_button'=>[]
				];
				foreach ($c as $k => $voo) {
					if($voo['url']){
						$newmenu['button'][$key]['sub_button'][$k] = [
							'type'=>'view',
							'name'=>$voo['title'],
							'url'=>$voo['url']
						];
					}else{
						$newmenu['button'][$key]['sub_button'][$k] = [
							'type'=>'click',
							'name'=>$voo['title'],
							'key'=>$voo['keyword']
						];
					}
				}
			}else{
				if($vo['url']){
					$newmenu['button'][$key] = [
						'type'=>'view',
						'name'=>$vo['title'],
						'url'=>$vo['url']
					];
				}else{
					$newmenu['button'][$key] = [
						'type'=>'click',
						'name'=>$vo['title'],
						'key'=>$vo['keyword']
					];
				}

			}
		}

		$result = $weObj->createMenu($newmenu);
		if($result){
			return $this->success('菜单生成成功');
		}else{
			return $this->error('菜单生成失败');
		}
	}

}