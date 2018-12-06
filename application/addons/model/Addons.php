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
// | 插件模型
// +----------------------------------------------------------------------
namespace app\addons\model;

use think\Db;
use think\Model;

class Addons extends Model
{
    //插件所处目录路径
    protected $addonsPath = null;
    protected $insert = ['create_time'];
    protected function setCreateTimeAttr($value)
    {
        return time();
    }

    // 模型初始化
    protected function initialize()
    {
        parent::initialize();
        $this->addonsPath = ADDON_PATH;

    }

    /**
     * 获取插件列表
     *
     * @param string $addon_dir
     */
    public function getAddonList($addon_dir = '')
    {
        if (!$addon_dir) {
            $addon_dir = ADDON_PATH;
        }
        //取得模块目录名称
        $dirs = array_map('basename', glob($addon_dir . '*', GLOB_ONLYDIR));
        if ($dirs === false || !file_exists($addon_dir)) {
            $this->error = '插件目录不可读或者不存在';
            return false;
        }
        // 读取数据库插件表
        $addons = $this->order('id desc')->column(true, 'name');
        //遍历插件列表
        foreach ($dirs as $value) {
            //是否已经安装过
            if (!isset($addons[$value])) {
                $class = get_addon_class($value);
                if (!class_exists($class)) {
                    // 实例化插件失败忽略执行
                    $addons[$value]['uninstall'] = -1;
                    continue;
                }
                //获取插件配置
                $obj = new $class();
                $addons[$value] = $obj->info;
                if ($addons[$value]) {
                    $addons[$value]['uninstall'] = 1;
                    unset($addons[$value]['status']);
                    //是否有配置
                    //$config = $obj->getAddonConfig();
                    //$addons[$value]['config'] = $config;
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
        // 复制文件
        $sourceAssetsDir = self::getSourceAssetsDir($addonName);
        $destAssetsDir = self::getDestAssetsDir($addonName);
        if (is_dir($sourceAssetsDir)) {
            copydirs($sourceAssetsDir, $destAssetsDir);
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
        $info['config'] = json_encode($addonObj->getAddonConfig());
        //添加插件安装记录
        $id = $this->allowField(true)->save($info);
        if (!$id) {
            $this->error = '写入插件数据失败！';
            return false;
        }
        //如果插件有自己的后台
        if ($info['has_adminlist']) {
            $admin_list = $addonObj->admin_list;
            //添加菜单
            $this->addAddonMenu($info, $admin_list);
        }
        //更新插件行为实现
        $hooks_update = model('admin/Hooks')->updateHooks($addonName);
        if (!$hooks_update) {
            $this->where("name='{$addon_name}'")->delete();
            $this->error = '更新钩子处插件失败,请卸载后尝试重新安装！';
            return false;
        }
        //更新缓存
        //$this->addons_cache();
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
        // 移除插件基础资源目录
        $destAssetsDir = self::getDestAssetsDir($addonName);
        if (is_dir($destAssetsDir)) {
            rmdirs($destAssetsDir);
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
            //删除插件后台菜单
            if ($info['has_adminlist']) {
                $this->delAddonMenu($info);
            }
            //更新缓存
            //$this->addons_cache();
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
     * 获取插件源资源文件夹
     * @param   string $name 插件名称
     * @return  string
     */
    protected static function getSourceAssetsDir($name)
    {
        return ADDON_PATH . "{$name}/public/";
    }

    /**
     * 获取插件目标资源文件夹
     * @param   string $name 插件名称
     * @return  string
     */
    protected static function getDestAssetsDir($name)
    {
        $assetsDir = config('static_path') . "/addons/{$name}";
        if (!is_dir($assetsDir)) {
            mkdir($assetsDir, 0755, true);
        }
        return $assetsDir;
    }

    /**
     * 添加插件后台管理菜单
     * @param type $info
     * @param type $adminlist
     * @return boolean
     */
    protected function addAddonMenu($info, $admin_list = null)
    {
        if (empty($info)) {
            return false;
        }
        //查询出“插件后台列表”菜单ID
        $menuId = Db::name('Menu')->where(array("app" => "Addons", "controller" => "Addons", "action" => "addonadmin"))->value('id');
        if (empty($menuId)) {
            return false;
        }
        $data = array(
            //父ID
            "parentid" => $menuId,
            //模块目录名称，也是项目名称
            "app" => "Addons",
            //插件名称
            "controller" => $info['name'],
            //方法名称
            "action" => "index",
            //附加参数 例如：a=12&id=777
            "parameter" => "isadmin=1",
            //状态，1是显示，0是不显示
            "status" => 1,
            //名称
            "title" => $info['title'],
            //备注
            "tip" => $info['title'] . "插件管理后台！",
            //排序
            "listorder" => 0,
        );
        //添加插件后台
        $parentid = Db::name('Menu')->insertGetId($data);
        if (!$parentid) {
            return false;
        }
        //显示“插件后台列表”菜单
        Db::name("Menu")->where(array('id' => $menuId))->update(array('status' => 1));
        //插件具体菜单
        if (!empty($admin_list)) {
            foreach ($admin_list as $key => $menu) {
                //检查参数是否存在
                if (empty($menu['title']) || empty($menu['action'])) {
                    continue;
                }
                //如果是index，跳过，因为已经有了。。。
                if ($menu['action'] == 'index') {
                    continue;
                }
                $data = array(
                    //父ID
                    "parentid" => $parentid,
                    //模块目录名称，也是项目名称
                    "app" => "Addons",
                    //文件名称，比如LinksAction.class.php就填写 Links
                    "controller" => $info['name'],
                    //方法名称
                    "action" => $menu['action'],
                    //附加参数 例如：a=12&id=777
                    "parameter" => 'isadmin=1',
                    //状态，1是显示，0是不显示
                    "status" => (int) $menu['status'],
                    //名称
                    "title" => $menu['title'],
                    //备注
                    "tip" => $menu['tip'] ?: '',
                    //排序
                    "listorder" => (int) $menu['listorder'],
                );
                Db::name('Menu')->insert($data);
            }
        }
        return true;
    }

    /**
     * 删除对应插件菜单和权限
     * @param type $info 插件信息
     * @return boolean
     */
    public function delAddonMenu($info)
    {
        if (empty($info)) {
            return false;
        }
        //查询出“插件后台列表”菜单ID
        $menuId = Db::name("Menu")->where(array("app" => "Addons", "controller" => "Addons", "action" => "addonadmin"))->value('id');
        if (empty($menuId)) {
            return false;
        }
        //删除对应菜单
        Db::name("Menu")->where(array('app' => 'Addons', 'controller' => $info['name']))->delete();
        //删除权限
        //M("Access")->where(array("app" => "Addons", 'controller' => $info['name']))->delete();
        //检查“插件后台列表”菜单下还有没有菜单，没有就隐藏
        $count = Db::name("Menu")->where(array('parentid' => $menuId))->count();
        if (!$count) {
            Db::name("Menu")->where(array('id' => $menuId))->update(array('status' => 0));
        }
        return true;
    }

    /**
     * 获取插件目录
     * @return type
     */
    public function getAddonsPath()
    {
        return $this->addonsPath;
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
}
