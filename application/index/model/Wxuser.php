<?php
namespace app\index\model;

use think\Model;

class Wxuser extends Model{
	protected $auto = ['update_time'];
	protected $insert = ['create_time'];
}