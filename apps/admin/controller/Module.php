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

namespace app\admin\controller;

use app\admin\model\Module as ModuleModel;
use think\Controller;
use think\Url;
use app\common\controller\Adminbase;

class Module extends Adminbase
{
	//初始化
    protected function _initialize() {
        parent::_initialize();
        $this->ModuleModel = new ModuleModel();
    }

    //本地模块列表
    public function index()
    {
        $list = $this->ModuleModel->getAll();

        $this->assign("data", $list['modules']);
        return $this->fetch();
    }

    //模块安装
    public function install()
    {
    	if($this->request->isPost()){
    		$module = $this->request->param('module');
    		if (empty($module)) {
                $this->error('请选择需要安装的模块！');
            }
            if ($this->ModuleModel->install($module)) {
                $this->success('模块安装成功！', Url::build('Admin/Module/index'));
            } else {
                $error = $this->ModuleModel->getError();
                $this->error($error ? $error : '模块安装失败！');
            }
    	}else{
    		$module = $this->request->param('module', '');
            if (empty($module)) {
                $this->error('请选择需要安装的模块！');
            }
            $config = $this->ModuleModel->getInfoFromFile($module);
    		$this->assign('config', $config);
    		return $this->fetch();

    	}
    }

    //模块卸载
    public function uninstall()
    {
        $module = $this->request->param('module');
        if (empty($module)) {
            $this->error('请选择需要安装的模块！');
        }
        if ($this->ModuleModel->uninstall($module)) {
            $this->success("模块卸载成功，请及时更新缓存！", Url::build("Module/index"));
        } else {
            $error = $this->ModuleModel->getError();
            $this->error($error ? $error : "模块卸载失败！", Url::build("Module/index"));
        }
    }












}
