<?php
namespace app\admin\controller;

use app\admin\model\Text as TextModel;
use app\admin\model\Keyword as KeywordModel;
use think\Db;

class Text extends Base{
	/**
	 * 文本回复列表
	 * @author ning
	 * @DateTime 2016-07-29T21:12:58+0800
	 * @return   [type]                   [description]
	 */
	public function index(){
		$list = Db::table('text')->paginate('10');
		$this->assign('list', $list);
		return view('index');
	}

	/**
	 * 添加文本回复
	 * @author ning
	 * @DateTime 2016-07-29T21:13:18+0800
	 */
	public function add(){
		if(request()->isPost()){
			Db::startTrans();
			try{
				$textModel = new TextModel;
				if(!$id = $textModel->validate(true)->save(input('post.'))){
					return $this->error($textModel->getError());
				}

				$keywordModel = new KeywordModel;
				$data = [
					'keyword'=>input('post.keyword'),
					'pid'=>$id,
					'module'=>'text'
				];
				if(!$keywordModel->validate(true)->save($data)){
					return $this->error($keywordModel->getError());
				}
				Db::commit();
				return $this->success('添加成功',url('text/index'));
			}catch(\PDOException $e){
				Db::rollback();
				return $this->error('添加失败');
			}
		}else{
			return view('add');
		}
	}

	/**
	 * 编辑文本回复
	 * @author ning
	 * @DateTime 2016-07-30T14:48:44+0800
	 * @return   [type]                   [description]
	 */
	public function edit(){
		if(request()->isPost()){
			$id = input('?post.id') ? input('post.id') : '';
			$keyword = input('?post.keyword') ? input('post.keyword') : '';
			if(!$id || !$keyword){
				return $this->error('参数错误');
			}
			
			Db::startTrans();
			try{
				$textModel = new TextModel;
				if(!$textModel->validate(true)->save(input('post.'),['id'=>$id])){
					return $this->error($textModel->getError());
				}

				$keywordModel = new KeywordModel;
				$keywordData = Db::table('keyword')->field('id')->where('keyword',$keyword)->find();
				if(!$keywordData){
					if(!$keywordModel->validate('Keyword.edit')->save(['keyword'=>$keyword], function($query) use($id){
						$query->where('pid',$id)->where('module','text');
					})){
						return $this->error($keywordModel->getError());
					}
				}

				Db::commit();
				return $this->success('修改成功',url('text/index'));
			}catch(\PDOException $e){
				Db::rollback();
				return $this->error('修改失败');
			}

		}else{
			$id = input('?param.id') ? input('param.id') : '';
			if(!$id){
				return $this->error('参数错误');
			}
			$data = Db::table('text')->where('id', $id)->find();
			$this->assign('data', $data);
			return view('edit');
		}
	}

	/**
	 * 删除文本回复
	 * @author ning
	 * @DateTime 2016-07-30T14:28:08+0800
	 * @return   [type]                   [description]
	 */
	public function del(){
		$id = input('?param.id') ? input('param.id') : '';
		if(!$id){
			return $this->error('参数错误');
		}

		Db::startTrans();
		try{
			$textModel = new TextModel();
			$textModel->where('id',$id)->delete();
			$keywordModel = new KeywordModel;
			$keywordModel->where('pid',$id)->where('module','text')->delete();
			Db::commit();
			return $this->success('删除成功');
		}catch(\PDOException $e){
			Db::rollaback();
			return $this->error('删除失败');
		}
	}
}