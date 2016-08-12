<?php
namespace app\index\controller;

use app\index\model\Tetris as TetrisModel;
use think\Db;

class Tetris extends Base{
	/**
	 * 更新分数
	 * @author ning
	 * @DateTime 2016-08-03T17:24:59+0800
	 * @return   [type]                   [description]
	 */
	public function update(){
		$wxuid = input('?post.wxuid') ? input('post.wxuid') : '';
		$openid = input('?post.openid') ? input('post.openid') : '';
		$score = input('?post.score') ? input('post.score') : '';
		if(!$wxuid || !$openid){
			return json(['status'=>400, 'data'=>'参数错误']);
		}
		// 是否关注过了
		$wxuser = Db::table('wxuser')->where('openid',$openid)->find();
		if(!$wxuser || $wxuser['id'] != $wxuid){
			return json(['status'=>400, 'data'=>'没有关注公众号']);
		}

		$data = Db::table('tetris')->where('wxuid',$wxuid)->find();
		$tetrisModel = new TetrisModel;
		if($data){
			if($data['score'] < $score){			
				if($tetrisModel->validate('Tetris.edit')->save(['score'=>$score],['id'=>$data['id']])){
					return json(['status'=>200, 'data'=>'更新成功']);
				}else{
					return json(['status'=>400, 'data'=>$tetrisModel->getError()]);
				}
			}
		}else{
			if($tetrisModel->validate(true)->save(['wxuid'=>$wxuid,'score'=>$score])){
				return json(['status'=>200, 'data'=>'添加成功']);
			}else{
				return json(['status'=>400, 'data'=>'添加失败']);
			}
		}

	}

	/**
	 * 获取分数
	 * @author ning
	 * @DateTime 2016-08-03T17:53:59+0800
	 * @return   [type]                   [description]
	 */
	public function getOne(){
		$wxuid = input('?post.wxuid') ? input('post.wxuid') : '';
		if(!$wxuid){
			return json(['status'=>400, 'data'=>'参数错误']);
		}

		$data = Db::table('tetris')->where('wxuid',$wxuid)->find();
		if($data){
			return json(['status'=>200, 'data'=>$data]);
		}else{
			return json(['status'=>400, 'data'=>'没有分数']);
		}
	}

	/**
	 * 排行榜
	 * @author ning
	 * @DateTime 2016-08-04T10:54:48+0800
	 * @return   [type]                   [description]
	 */
	public function rank(){
		$list = Db::table('tetris')
								->alias('t')
								->field('t.id,t.score,w.thumb,w.name')
								->join('wxuser w','w.id = t.wxuid')
								->paginate('20');
		$this->assign('list', $list);

		return view('rank');
	}
}