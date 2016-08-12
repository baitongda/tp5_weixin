<?php
namespace app\admin\controller;
use app\admin\model\Areply as AreplyModel;
use think\Db;
class Areply extends Base{
	/**
	 * 关注回复
	 * @author ning
	 * @DateTime 2016-07-29T16:05:57+0800
	 * @return   [type]                   [description]
	 */
	public function index(){
		$data = \think\Db::table('areply')->find();
		$this->assign('data',$data);
		return view('index');
	}

	/**
	 * 设置关注回复
	 * @author ning
	 * @DateTime 2016-07-30T10:43:25+0800
	 */
	public function set(){
		$data = Db::table('areply')->find();
		if(request()->isPost()){
			$keyword = input('?post.keyword') ? input('post.keyword') : '';

			$areplyModel = new AreplyModel;

			if($data){
				if($areplyModel->save(['keyword'=>$keyword],['id'=>$data['id']])){
					return $this->success('设置成功');
				}else{
					return $this->error('设置失败');
				}
			}else{
				if($areplyModel->save(['keyword'=>$keyword])){
					return $this->success('设置成功');
				}else{
					return $this->error('设置失败');
				}
			}
		}
	}

}