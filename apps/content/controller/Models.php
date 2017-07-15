<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\content\controller;
use \think\Db;
use app\common\controller\Adminbase;

/**
 * 后台模型管理
 */
class Models extends Adminbase
{
	//模型列表
	public function index()
	{
		$data = Db::name("Model")->where(array("type" => 0))->select();
		$this->assign("data", $data);
		return $this->fetch();
	}

}
