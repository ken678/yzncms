<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 模型管理
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Module as ModuleModel;
use app\common\controller\Adminbase;
use think\Controller;

class Module extends Adminbase
{
    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->ModuleModel = new ModuleModel();
    }

    //本地模块列表
    public function index()
    {
        if ($this->request->isAjax()) {
            $list = $this->ModuleModel->getAll();
            $result = array("code" => 0, "data" => $list['modules']);
            return json($result);
        }
        return $this->fetch();
    }

    //模块安装
    public function install()
    {
        if ($this->request->isPost()) {
            $module = $this->request->param('module');
            if (empty($module)) {
                $this->error('请选择需要安装的模块！');
            }
            if ($this->ModuleModel->install($module)) {
                $this->success('模块安装成功！', url('admin/Module/index'));
            } else {
                $error = $this->ModuleModel->getError();
                $this->error($error ? $error : '模块安装失败！');
            }
        } else {
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
            $this->success("模块卸载成功，请及时更新缓存！", url("admin/Module/index"));
        } else {
            $error = $this->ModuleModel->getError();
            $this->error($error ? $error : "模块卸载失败！", url("admin/Module/index"));
        }
    }

}
