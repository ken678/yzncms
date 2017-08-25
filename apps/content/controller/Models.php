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
use think\Loader;
use think\Cookie;
use app\common\controller\Adminbase;

/**
 * 后台模型管理
 */
class Models extends Adminbase
{
	/**
     * 模型列表首页
     * @author 御宅男  <530765310@qq.com>
     */
	public function index()
	{
		$data = Db::name("Model")->where(array("type" => 0))->select();
        // 记录当前列表页的cookie
        Cookie::set('__forward__',$_SERVER['REQUEST_URI']);
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

    /**
     * 模型删除
     * @author 御宅男  <530765310@qq.com>
     */
    public function delete()
    {
        $modelid = Request::instance()->param('modelid/d');
        empty($modelid) && $this->error('参数不能为空！');
        //检查该模型是否已经被使用
        $r = Db::name("Category")->where(array("modelid" => $modelid))->find();
        if ($r) {
            $this->error("该模型使用中，删除栏目后再删除！");
        }

        if (Loader::model("content/Models")->deleteModel($modelid)) {
            $this->success("删除成功！", url("index"));
        } else {
            $this->error("删除失败！");
        }

    }

    /**
     * 模型禁用
     * @author 御宅男  <530765310@qq.com>
     */
	public function disabled()
	{
		$modelid = Request::instance()->param('modelid/d');
        empty($modelid) && $this->error('参数不能为空！');
		$r = Db::name('Model')->where((array('modelid'=>$modelid)))->value('disabled');
		$status = $r == '1' ? '0' : '1';
		Db::name('Model')->where((array('modelid'=>$modelid)))->update(array('disabled'=>$status));
		$this->success("操作成功！");
	}

}
