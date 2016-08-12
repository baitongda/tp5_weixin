<?php
namespace app\admin\model;
use think\Model;

class Text extends Model{
	protected $auto = ['update_time'];
	protected $insert = ['create_time'];
}