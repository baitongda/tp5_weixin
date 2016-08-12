<?php
namespace app\admin\controller;

use app\admin\model\Img as ImgModel;
use app\admin\model\Keyword as KeywordModel;
use think\Db;

class Img extends Base{
	/**
	 * 图文回复列表
	 * @author ning
	 * @DateTime 2016-07-30T14:41:15+0800
	 * @return   [type]                   [description]
	 */
	public function index(){
		$list = Db::table('img')->paginate('10');
		$this->assign('list', $list);
		return view('index');
	}

	/**
	 * 添加图文回复
	 * @author ning
	 * @DateTime 2016-07-30T14:48:21+0800
	 */
	public function add(){
		if(request()->isPost()){
			Db::startTrans();
			try{
				$imgModel = new ImgModel;
				if(!$id = $imgModel->validate(true)->save(input('post.'))){
					return $this->error($imgModel->getError());
				}

				$keywordModel = new KeywordModel;
				$data = [
					'keyword'=>input('post.keyword'),
					'pid'=>$id,
					'type'=>input('post.type'),
					'module'=>'img'
				];
				if(!$keywordModel->validate(true)->save($data)){
					return $this->error($keywordModel->getError());
				}
				Db::commit();
				return $this->success('添加成功',url('img/index'));
			}catch(\PDOException $e){
				Db::rollback();
				return $this->error('添加失败');
			}
		}else{
			return view('add');
		}
	}

	/**
	 * 编辑图文回复
	 * @author ning
	 * @DateTime 2016-07-30T18:05:37+0800
	 * @return   [type]                   [description]
	 */
	public function edit(){
		if(request()->isPost()){
			$id = input('?param.id') ? input('param.id') : '';
			$keyword = input('post.keyword') ? input('post.keyword') : '';
			if(!$id || !$keyword){
				return $this->error('参数错误');
			}

			Db::startTrans();
			try{
				$imgModel = new ImgModel;
				if(!$imgModel->validate(true)->save(input('post.'),['id'=>$id])){
					return $this->error($imgModel->getError());
				}

				$keywordModel = new KeywordModel;
				$keywordData = Db::table('keyword')->field('id,type')->where('keyword',$keyword)->find();
				if(!$keywordData){
					if(!$keywordModel->validate('Keyword.edit')->save(['keyword'=>$keyword,'type'=>input('post.type')], function($query) use($id){
						$query->where('pid',$id)->where('module','img');
					})){
						return $this->error($keywordModel->getError());
					}
				}else{
					if($keywordData['type'] != input('post.type')){
						if(!$keywordModel->save(['type'=>input('post.type')], function($query) use($id){
							$query->where('pid', $id)->where('module', 'img');
						})){
							return $this->error($keywordModel->getError());
						}
					}
				}
				


				Db::commit();
				return $this->success('修改成功', url('img/index'));
			}catch(\PDOException $e){
				Db::rollback();
				return $this->error('修改失败');
			}
		}else{
			$id = input('?param.id') ? input('param.id') : '';
			if(!$id){
				return $this->error('参数错误');
			}

			$data = Db::table('img')->where('id', $id)->find();
			$this->assign('data', $data);
			return view('edit');
		}
	}

	/**
	 * 删除图文回复
	 * @author ning
	 * @DateTime 2016-07-30T15:08:10+0800
	 * @return   [type]                   [description]
	 */
	public function del(){
		$id = input('?param.id') ? input('param.id') : '';
		if(!$id){
			return $this->error('参数错误');
		}

		Db::startTrans();
		try{
			$imgModel = new ImgModel;
			$imgModel->where('id', $id)->delete();
			$keywordModel = new KeywordModel;
			$KeywordModel->where('pid', $id)->where('module','img')->delete();

			Db::commit();
			return $this->success('删除成功');
		}catch(\PDOException $e){
			Db::rollback();
			return $this->error('修改失败');
		}
	}


}