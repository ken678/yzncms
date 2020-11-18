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

use app\common\controller\Adminbase;
use think\AddonService;

class Addons extends Adminbase
{
    //显示插件列表
    public function index()
    {
        if ($this->request->isAjax()) {
            $addons = get_addon_list();
            $list   = [];
            foreach ($addons as $k => &$v) {
                $config      = get_addon_config($v['name']);
                $v['config'] = $config ? 1 : 0;
            }
            $result = array("code" => 0, "data" => $addons);
            return json($result);
        }
        return $this->fetch();

    }

    /**
     * 设置插件页面
     */
    public function config($name = null)
    {
        $name = $name ? $name : $this->request->get("name");
        if (!$name) {
            $this->error('参数不得为空！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件名称不正确！');
        }
        if (!is_dir(ADDON_PATH . $name)) {
            $this->error('目录不存在！');
        }
        $info   = get_addon_info($name);
        $config = get_addon_fullconfig($name);
        if (!$info) {
            $this->error('配置不存在！');
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("config/a", [], 'trim');
            if ($params) {
                foreach ($config as $k => &$v) {
                    if (isset($params[$v['name']])) {
                        if ($v['type'] == 'array') {
                            $params[$v['name']] = is_array($params[$v['name']]) ? $params[$v['name']] : (array) json_decode($params[$v['name']],
                                true);
                            $value = $params[$v['name']];
                        } else {
                            $value = is_array($params[$v['name']]) ? implode(',',
                                $params[$v['name']]) : $params[$v['name']];
                        }
                        $v['value'] = $value;
                    }
                }
                try {
                    //更新配置文件
                    set_addon_fullconfig($name, $config);
                    //AddonService::refresh();
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->success('插件配置成功！');
        }
        $this->assign('data', ['info' => $info, 'config' => $config]);
        $configFile = ADDON_PATH . $name . DIRECTORY_SEPARATOR . 'config.html';
        if (is_file($configFile)) {
            $this->assign('custom_config', $this->view->fetch($configFile));
        }
        return $this->fetch();
    }

    /**
     * 禁用启用.
     */
    public function state()
    {
        $name   = $this->request->param('name');
        $action = $this->request->param('action');

        if (!$name) {
            $this->error('参数不得为空！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件名称不正确');
        }
        try {
            $action = $action == 'enable' ? $action : 'disable';
            //调用启用、禁用的方法
            AddonService::$action($name, true);
            //Cache::delete('__menu__');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('操作成功');
    }

    /**
     * 安装插件
     */
    public function install()
    {
        $name = $this->request->param('name');
        if (empty($name)) {
            $this->error('请选择需要安装的插件！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件标识错误！');
        }
        try {
            AddonService::install($name);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件安装成功！清除浏览器缓存和框架缓存后生效！');
    }

    /**
     * 卸载插件
     */
    public function uninstall()
    {
        $name = $this->request->param('name');
        if (empty($name)) {
            $this->error('请选择需要安装的插件！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件标识错误！');
        }
        try {
            AddonService::uninstall($name, true);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件卸载成功！清除浏览器缓存和框架缓存后生效！');
    }

    /**
     * 卸载插件
     */
    /*public function uninstall()
    {
    $addonId = $this->request->param('id/d');
    if (empty($addonId)) {
    $this->error('请选择需要卸载的插件！');
    }
    //获取插件信息
    $info  = Addons_Model::where(array('id' => $addonId))->find();
    $class = get_addon_class($info['name']);
    if (empty($info) || !class_exists($class)) {
    $this->error('该插件不存在！');
    }
    //插件标识
    $addonName = $info['name'];
    //检查插件是否安装
    if ($this->isInstall($addonName) == false) {
    $this->error('该插件未安装，无需卸载！');
    }
    //卸载插件
    $addonObj  = new $class();
    $uninstall = $addonObj->uninstall();
    if ($uninstall !== true) {
    if (method_exists($addonObj, 'getError')) {
    $this->error($addonObj->getError() ? $addonObj->getError() : '执行插件预卸载操作失败！');
    } else {
    $this->error('执行插件预卸载操作失败！');
    }
    }
    if (false !== Addons_Model::destroy($addonId)) {
    //删除插件后台菜单
    if (isset($info['has_adminlist']) && $info['has_adminlist']) {
    model('admin/Menu')->delAddonMenu($info);
    }
    // 移除插件基础资源目录
    $destAssetsDir = self::getDestAssetsDir($addonName);
    if (is_dir($destAssetsDir)) {
    \util\File::del_dir($destAssetsDir);
    }
    $hooks_update = model('admin/Hooks')->removeHooks($addonName);
    if ($hooks_update === false) {
    $this->error = '卸载插件所挂载的钩子数据失败！';
    }
    Cache::set('Hooks', null);
    $this->success('插件卸载成功！清除浏览器缓存和框架缓存后生效！', url('Addons/index'));
    } else {
    $this->error('插件卸载失败！');
    }
    }

    public function install()
    {
    $addonName = $this->request->param('addon_name');
    if (empty($addonName)) {
    $this->error('请选择需要安装的插件！');
    }
    //检查插件是否安装
    if ($this->isInstall($addonName)) {
    $this->error('该插件已经安装，无需重复安装！');
    }
    $class = get_addon_class($addonName);
    if (!class_exists($class)) {
    $this->error('获取插件对象出错！');
    }
    $addonObj = new $class();
    //获取插件信息
    $info = $addonObj->info;
    if (empty($info)) {
    $this->error('插件信息获取失败！');
    }
    //开始安装
    $install = $addonObj->install();
    if ($install !== true) {
    if (method_exists($addonObj, 'getError')) {
    $this->error($addonObj->getError() ?: '执行插件预安装操作失败！');
    } else {
    $this->error('执行插件预安装操作失败！');
    }
    }
    $info['config'] = json_encode($addonObj->getAddonConfig());
    //添加插件安装记录
    $res = Addons_Model::create($info, true);
    if (!$res) {
    $this->error('写入插件数据失败！');
    }
    // 复制静态资源
    $sourceAssetsDir = self::getSourceAssetsDir($addonName);
    $destAssetsDir   = self::getDestAssetsDir($addonName);
    if (is_dir($sourceAssetsDir)) {
    \util\File::copy_dir($sourceAssetsDir, $destAssetsDir);
    }
    //如果插件有自己的后台
    if (isset($info['has_adminlist']) && $info['has_adminlist']) {
    $admin_list = $addonObj->admin_list;
    //添加菜单
    model('admin/Menu')->addAddonMenu($info, $admin_list);
    }
    //更新插件行为实现
    $hooks_update = model('admin/Hooks')->updateHooks($addonName);
    if (!$hooks_update) {
    $this->where("name='{$addonName}'")->delete();
    $this->error('更新钩子处插件失败,请卸载后尝试重新安装！');
    }
    Cache::set('Hooks', null);
    $this->success('插件安装成功！清除浏览器缓存和框架缓存后生效！', url('Addons/index'));
    }*/

    //本地安装
    public function local()
    {
        if ($this->request->isPost()) {
            $files = $this->request->file('file');
            if ($files == null) {
                $this->error("请选择上传文件！");
            }
            if (strtolower(substr($files->getInfo('name'), -3, 3)) != 'zip') {
                $this->error("上传的文件格式有误！");
            }
            //插件名称
            $addonName = pathinfo($files->getInfo('name'));
            $addonName = $addonName['filename'];
            //检查插件目录是否存在
            if (file_exists(ADDON_PATH . $addonName)) {
                $this->error('该插件目录已经存在！');
            }

            //上传临时文件地址
            $filename = $files->getInfo('tmp_name');
            $zip      = new \util\PclZip($filename);
            $status   = $zip->extract(PCLZIP_OPT_PATH, ADDON_PATH . $addonName);
            if ($status) {
                $this->success('插件解压成功，可以进入插件管理进行安装！', url('index'));
            } else {
                $this->error('插件解压失败！');
            }
        }
    }
}
