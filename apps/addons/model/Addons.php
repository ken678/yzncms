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
namespace app\addons\model;

use think\Model;

/**
 * 插件模型
 */

class Addons extends Model
{
    protected $autoWriteTimestamp = false;
    protected $auto = [
        'create_time',
    ];
    protected function setCreateTimeAttr($value)
    {
        return time();
    }

    /**
     * 获取插件列表
     *
     * @param string $addon_dir
     */
    public function getAddonList($addon_dir = '')
    {
        if (!$addon_dir) {
            $addon_dir = YZN_ADDON_PATH;
        }
        //取得模块目录名称
        $dirs = array_map('basename', glob($addon_dir . '*', GLOB_ONLYDIR));
        if ($dirs === false || !file_exists($addon_dir)) {
            $this->error = '插件目录不可读或者不存在';
            return false;
        }
        $addons = array();
        if (!empty($dirs)) {
            //取得已安装插件列表
            $addonsList = $this->where(array('name' => array('in', $dirs)))->field(true)->select();
        } else {
            $addonsList = array();
        }
        foreach ($addonsList as $key => $value) {
            $addonsList[$key] = $value->toArray();
        }
        foreach ($addonsList as $addon) {
            $addon['uninstall'] = 0;
            //$addon['config'] = unserialize($addon['config']);
            $addons[$addon['name']] = $addon;
        }
        //遍历插件列表
        foreach ($dirs as $value) {
            //是否已经安装过
            if (!isset($addons[$value])) {
                $class = get_addon_class($value);
                if (!class_exists($class)) {
                    // 实例化插件失败忽略执行
                    trace($class);
                    \think\Log::record('插件' . $value . '的入口文件不存在！');
                    continue;
                }
                //获取插件配置
                $obj = new $class();
                $addons[$value] = $obj->info;
                if ($addons[$value]) {
                    $addons[$value]['uninstall'] = 1;
                    unset($addons[$value]['status']);
                    //是否有配置
                    $config = $obj->getAddonConfig();
                    $addons[$value]['config'] = $config;
                }
            }
        }
        int_to_string($addons, array('status' => array(-1 => '损坏', 0 => '禁用', 1 => '启用', null => '未安装')));
        $addons = list_sort_by($addons, 'uninstall', 'desc');
        return $addons;
    }

    /**
     * 安装插件
     * @param type $addonName 插件标识
     * @return boolean
     */
    public function installAddon($addonName)
    {
        if (empty($addonName)) {
            $this->error = '请选择需要安装的插件！';
            return false;
        }
        //检查插件是否安装
        if ($this->isInstall($addonName)) {
            $this->error = '该插件已经安装，无需重复安装！';
            return false;
        }
        $class = get_addon_class($addonName);
        if (!class_exists($class)) {
            $this->error = '获取插件对象出错！';
        }
        $addonObj = new $class();
        //获取插件信息
        $info = $addonObj->info;
        if (empty($info)) {
            $this->error = '插件信息获取失败！';
            return false;
        }
        //开始安装
        $install = $addonObj->install();
        if ($install !== true) {
            if (method_exists($addonObj, 'getError')) {
                $this->error = $addonObj->getError() ?: '执行插件预安装操作失败！';
            } else {
                $this->error = '执行插件预安装操作失败！';
            }
            return false;
        }
        if (isset($$addonObj->admin_list) && is_array($addonObj->admin_list) && $addonObj->admin_list !== array()) {
            $info['has_adminlist'] = 1;
        } else {
            $info['has_adminlist'] = 0;
        }
        $info['config'] = json_encode($addonObj->getAddonConfig());
        //插件信息验证
        //
        //添加插件安装记录
        $id = $this->allowField(true)->save($info);
        if (!$id) {
            $this->error = '写入插件数据失败！';
            return false;
        }
        //更新插件行为实现
        $hooks_update = model('admin/Hooks')->updateHooks($addonName);
        if (!$hooks_update) {
            $this->where("name='{$addon_name}'")->delete();
            $this->error = '更新钩子处插件失败,请卸载后尝试重新安装！';
            return false;
        }
        //更新缓存
        $this->addons_cache();
        return $id;

    }

    /**
     * 卸载插件
     * @param type $addonId 插件id
     * @return boolean
     */
    public function uninstallAddon($addonId)
    {
        $addonId = (int) $addonId;
        if (empty($addonId)) {
            $this->error = '请选择需要卸载的插件！';
            return false;
        }
        //获取插件信息
        $info = $this->where(array('id' => $addonId))->find();
        $class = get_addon_class($info['name']);
        if (empty($info) || !class_exists($class)) {
            $this->error = '该插件不存在！';
            return false;
        }
        //插件标识
        $addonName = $info['name'];
        //检查插件是否安装
        if ($this->isInstall($addonName) == false) {
            $this->error = '该插件未安装，无需卸载！';
            return false;
        }
        //卸载插件
        $addonObj = new $class();
        $uninstall = $addonObj->uninstall();
        if ($uninstall !== true) {
            if (method_exists($addonObj, 'getError')) {
                $this->error = $addonObj->getError() ? $addonObj->getError() : '执行插件预卸载操作失败！';
            } else {
                $this->error = '执行插件预卸载操作失败！';
            }
            return false;
        }
        //删除插件记录
        if (false !== $this->where(array('id' => $addonId))->delete()) {
            //更新缓存
            $this->addons_cache();
            //删除行为挂载点
            $hooks_update = model('admin/Hooks')->removeHooks($addonName);
            if ($hooks_update === false) {
                $this->error = '卸载插件所挂载的钩子数据失败！';
            }
            //更新行为缓存
            //cache('Behavior', null);
            return true;

        } else {
            $this->error = '卸载插件失败！';
            return false;
        }
    }

    /**
     * 检查插件是否已经安装
     * @param type $name 插件标识
     * @return boolean
     */
    public function isInstall($name)
    {
        if (empty($name)) {
            return false;
        }
        $count = $this->where(array('name' => $name))->find();
        return $count ? true : false;
    }

    /**
     * 获取插件的后台列表
     */
    public function getAdminList()
    {
        $admin = array();
        $db_addons = $this->where("status=1 AND has_adminlist=1")->field('title,name')->select();
        if ($db_addons) {
            foreach ($db_addons as $value) {
                $value = $value->toArray();
                $admin[] = array('title' => $value['title'], 'url' => "Addons/adminList?name={$value['name']}");
            }
        }
        return $admin;
    }

    /**
     * 缓存已安装插件缓存
     * @return type
     */
    public function addons_cache()
    {
        $return = array();
        $data = \think\Db::name('Addons')->where(array('status' => 1))->order(array('id' => 'DESC'))->select();
        if (!empty($data)) {
            foreach ($data as $r) {
                //$r['config'] = unserialize($r['config']);
                $return[$r['name']] = $r;
            }
        }
        cache('Addons', $return);
        return $return;
    }
}
