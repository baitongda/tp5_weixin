<?php
namespace app\index\controller;

use app\index\model\Wxuser as WxuserModel;
use think\Db;
use think\Controller;

class Wxuser extends Controller{
	/**
	 * 更新用户信息
	 * @author ning
	 * @DateTime 2016-08-03T15:54:18+0800
	 * @return   [type]                   [description]
	 */
	public function update(){
		$openid = input('?post.openid') ? input('post.openid') : '';
		$thumb = input('?post.thumb') ? input('post.thumb') : '';
		$name = input('?post.name') ? input('post.name') : '';
		if(!$openid || !$thumb || !$name){
			return json(['status'=>400, 'data'=>'参数错误']);
		}
		$data = Db::table('wxuser')->where('openid',$openid)->find();
		$wxuserModel = new WxuserModel;
		if($data){
			$updateData = [
				'thumb'=>$thumb,
				'name'=>$name
			];
			if($wxuserModel->save($updateData, ['id'=>$data['id']])){
				return json(['status'=>200,'data'=>$data['id']]);
			}else{
				return json(['status'=>400, 'data'=>'更新失败']);
			}
		}else{
			$insertData = [
				'openid'=>$openid,
				'thumb'=>$thumb,
				'name'=>$name
			];
			if($id = $wxuserModel->validate(true)->save($insertData)){
				return json(['status'=>200,'data'=>$id]);
			}else{
				return json(['status'=>400, 'data'=>'添加失败']);
			}			
		}
	}
}