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
use think\Db;
use think\Request;
use app\content\model\ModelField;
use app\common\controller\Adminbase;

class Field extends Adminbase
{
	//初始化
    protected function _initialize()
    {
    	parent::_initialize();
    	$this->modelfield = new ModelField;

    }
	//显示字段列表
	public function index()
	{
		$modelid = Request::instance()->param('modelid','');
        if (empty($modelid)) {
            $this->error('参数错误！');
        }
        $model = Db::name("Model")->field(true)->where(array("modelid" => $modelid))->find();
        if (empty($model)) {
            $this->error('该模型不存在！');
        }

        //根据模型读取字段列表
        $data = $this->modelfield->getModelField($modelid);
        //禁止被禁用的字段列表
        $this->assign("forbid_fields", $this->modelfield->forbid_fields);
        //禁止被删除的字段列表
        $this->assign("forbid_delete", $this->modelfield->forbid_delete);

        $this->assign("modelid", $modelid);
        $this->assign("data", $data);
        return $this->fetch();
	}

}
