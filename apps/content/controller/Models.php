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
use \think\Request;
use \think\Loader;
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

	//添加模型
    public function add()
    {
    	if(Request::instance()->isPost()){
    		$data = Request::instance()->param();
            if (empty($data)) {
                $this->error('提交数据不能为空！');
            }
            if (Loader::model("content/Models")->addModel($data)) {
                $this->success("添加模型成功！");
            } else {
                $error = Loader::model("content/Models")->getError();
                $this->error($error ? $error : '添加失败！');
            }
    	}else{
    		return $this->fetch();

    	}
    }

    //模型禁用
	public function disabled()
	{
		$modelid = Request::instance()->param('modelid/d', 0);
		$r = Db::name('Model')->where((array('modelid'=>$modelid)))->value('disabled');
		$status = $r == '1' ? '0' : '1';
		Db::name('Model')->where((array('modelid'=>$modelid)))->update(array('disabled'=>$status));
		$this->success("操作成功！");
	}

}
