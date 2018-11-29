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
// | 插件管理
// +----------------------------------------------------------------------
namespace app\addons\controller;

use app\addons\model\Addons as Addons_model;
use app\common\controller\Adminbase;
use think\Db;

class Addons extends Adminbase
{
    protected function initialize()
    {
        parent::initialize();
        $this->addons = new Addons_model;
    }

    //显示插件列表
    public function index()
    {
        if ($this->request->isAjax()) {
            $addons = $this->addons->getAddonList();
            $result = array("code" => 0, "data" => $addons);
            return json($result);
        }
        return $this->fetch();

    }

    public function hooks()
    {
        var_dump(222);
    }

    /**
     * 安装插件
     */
    public function install()
    {
        $addonName = $this->request->param('addon_name');
        if (empty($addonName)) {
            $this->error('请选择需要安装的插件！');
        }
        if ($this->addons->installAddon($addonName)) {
            $this->success('插件安装成功！', url('Addons/index'));
        } else {
            $error = $this->addons->getError();
            $this->error($error ? $error : '插件安装失败！');
        }
    }

    /**
     * 卸载插件
     */
    public function uninstall()
    {
        $addonId = $this->request->param('id/d');
        if (empty($addonId)) {
            $this->error('请选择需要卸载的插件！');
        }
        if ($this->addons->uninstallAddon($addonId)) {
            $this->success('插件卸载成功！', url('Addons/index'));
        } else {
            $error = $this->addons->getError();
            $this->error($error ? $error : '插件卸载失败！');
        }
    }

}
