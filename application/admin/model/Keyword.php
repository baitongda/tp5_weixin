<?php
namespace app\admin\model;
use think\Model;

class Keyword extends Model{
	protected $auto = ['update_time'];
	protected $insert = ['create_time'];
}