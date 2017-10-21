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
namespace app\addons\controller;

use app\common\controller\Adminbase;
use think\Cookie;
use think\Db;
use think\Loader;

/**
 * 插件管理
 */
class Addons extends Adminbase
{
    public function _initialize()
    {
        parent::_initialize();
        $this->addons = Loader::model('Addons');
    }

    //显示插件列表
    public function index()
    {
        $addons = $this->addons->getAddonList();
        $page = $this->request->param('page/d', 1);
        $number = 25; // 每页显示
        $voList = Db::name('Addons')->paginate($number, false, array(
            'page' => $page,
        ));
        $_page = $voList->render(); // 获取分页显示
        $voList = array_slice($addons, bcmul($number, $page) - $number, $number);
        $this->assign('_page', $_page);
        $this->assign('_list', $voList);
        // 记录当前列表页的cookie
        Cookie::set('__forward__', $_SERVER['REQUEST_URI']);
        return $this->fetch();
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
