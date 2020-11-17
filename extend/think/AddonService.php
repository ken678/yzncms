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
// | 插件服务类
// +----------------------------------------------------------------------
namespace think;

use think\Exception;
use think\facade\Cache;
use util\Sql;

class AddonService
{
    /**
     * 安装插件.
     *
     * @param string $name   插件名称
     * @param bool   $force  是否覆盖
     * @param array  $extend 扩展参数
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function install($name)
    {
        try {
            // 检查插件是否完整
            self::check($name);
            /*if (!$force) {
        self::noconflict($name);
        }*/
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        // 复制静态资源
        $sourceAssetsDir = self::getSourceAssetsDir($name);
        $destAssetsDir   = self::getDestAssetsDir($name);
        if (is_dir($sourceAssetsDir)) {
            \util\File::copy_dir($sourceAssetsDir, $destAssetsDir);
        }
        try {
            // 默认启用该插件
            $info = get_addon_info($name);
            if (0 >= $info['status']) {
                $info['status'] = 1;
                set_addon_info($name, $info);
            }
            // 执行安装脚本
            $class = get_addon_class($name);
            if (class_exists($class)) {
                $addon = new $class();
                $addon->install();
            }
            if (isset($info['has_adminlist']) && $info['has_adminlist']) {
                $admin_list = $addonObj->admin_list;
                //添加菜单
                model('admin/Menu')->addAddonMenu($info, $admin_list);
            }
            //更新插件行为实现
            $hooks_update = model('admin/Hooks')->updateHooks($name);
            if (!$hooks_update) {
                $this->where("name='{$name}'")->delete();
                throw new Exceptionr('更新钩子处插件失败,请卸载后尝试重新安装！');
            }
            self::runSQL($name);
            Cache::set('Hooks', null);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        // 刷新
        self::refresh();
        return true;
    }

    /**
     * 卸载插件.
     *
     * @param string $name
     * @param bool   $force 是否强制卸载
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function uninstall($name)
    {
        if (!$name || !is_dir(ADDON_PATH . $name)) {
            throw new Exception('插件不存在！');
        }
        // 移除插件基础资源目录
        $destAssetsDir = self::getDestAssetsDir($addonName);
        if (is_dir($destAssetsDir)) {
            \util\File::del_dir($destAssetsDir);
        }
        // 执行卸载脚本
        try {
            // 默认禁用该插件
            $info = get_addon_info($name);
            if ($info['status']) {
                $info['status'] = -1;
                set_addon_info($name, $info);
            }
            //删除插件后台菜单
            if (isset($info['has_adminlist']) && $info['has_adminlist']) {
                model('admin/Menu')->delAddonMenu($info);
            }
            $hooks_update = model('admin/Hooks')->removeHooks($name);
            if ($hooks_update === false) {
                throw new Exception('卸载插件所挂载的钩子数据失败！');
            }
            $class = get_addon_class($name);
            if (class_exists($class)) {
                $addon = new $class();
                $addon->uninstall();
            };
            self::runSQL($name, 'uninstall');
            Cache::set('Hooks', null);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        // 刷新
        self::refresh();
        return true;
    }

    /**
     * 启用.
     *
     * @param string $name  插件名称
     * @param bool   $force 是否强制覆盖
     *
     * @return bool
     */
    public static function enable($name, $force = false)
    {
        if (!$name || !is_dir(ADDON_PATH . $name)) {
            throw new Exception('插件不存在！');
        }

        $info           = get_addon_info($name);
        $info['status'] = 1;
        //unset($info['url']);
        set_addon_info($name, $info);

        //执行启用脚本
        try {
            //AddonsModel::update(['status' => 1], ['name' => $name]);
            $class = get_addon_class($name);
            if (class_exists($class)) {
                $addon = new $class();
                if (method_exists($class, 'enable')) {
                    $addon->enable();
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        // 刷新
        self::refresh();
        return true;
    }

    /**
     * 禁用.
     *
     * @param string $name  插件名称
     * @param bool   $force 是否强制禁用
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function disable($name, $force = false)
    {
        if (!$name || !is_dir(ADDON_PATH . $name)) {
            throw new Exception('插件不存在！');
        }

        $info           = get_addon_info($name);
        $info['status'] = 0;
        //unset($info['url']);
        set_addon_info($name, $info);

        // 执行禁用脚本
        try {
            $class = get_addon_class($name);
            if (class_exists($class)) {
                $addon = new $class();

                if (method_exists($class, 'disable')) {
                    $addon->disable();
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        // 刷新
        self::refresh();
        return true;
    }

    /**
     * 刷新插件缓存文件.
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function refresh()
    {
        $file = app()->getRootPath() . 'config' . DS . 'addons.php';

        $config = get_addon_autoload_config(true);
        if ($config['autoload']) {
            return;
        }

        if (!\util\File::is_really_writable($file)) {
            throw new Exception('addons.php文件没有写入权限');
        }

        if ($handle = fopen($file, 'w')) {
            fwrite($handle, "<?php\n\n" . 'return ' . var_export($config, true) . ';');
            fclose($handle);
        } else {
            throw new Exception('文件没有写入权限');
        }

        return true;
    }

    /**
     * 执行安装数据库脚本
     * @param type $name 模块名(目录名)
     * @return boolean
     */
    public static function runSQL($name = '', $Dir = 'install')
    {
        $sql_file = ADDON_PATH . "{$name}" . DS . "{$Dir}" . DS . "{$Dir}.sql";
        if (file_exists($sql_file)) {
            $sql_statement = Sql::getSqlFromFile($sql_file);
            if (!empty($sql_statement)) {
                foreach ($sql_statement as $value) {
                    try {
                        Db::execute($value);
                    } catch (\Exception $e) {
                        throw new Exception('导入SQL失败，请检查{$name}.sql的语句是否正确');
                    }
                }
            }
        }
        return true;
    }

    /**
     * 获取插件源资源文件夹
     * @param   string $name 插件名称
     * @return  string
     */
    protected static function getSourceAssetsDir($name)
    {
        return ADDON_PATH . $name . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
    }

    /**
     * 获取插件目标资源文件夹
     * @param   string $name 插件名称
     * @return  string
     */
    protected static function getDestAssetsDir($name)
    {
        $assetsDir = ROOT_PATH . str_replace("/", DIRECTORY_SEPARATOR, "public/static/addons/{$name}/");
        if (!is_dir($assetsDir)) {
            mkdir($assetsDir, 0755, true);
        }
        return $assetsDir;
    }

    /**
     * 检测插件是否完整.
     *
     * @param string $name 插件名称
     *
     * @throws Exception
     *
     * @return bool
     */
    public static function check($name)
    {
        if (!$name || !is_dir(ADDON_PATH . $name)) {
            throw new Exception('插件不存在！');
        }
        $addonClass = get_addon_class($name);
        if (!$addonClass) {
            throw new Exception('插件主启动程序不存在');
        }
        $addon = new $addonClass();
        if (!$addon->checkInfo()) {
            throw new Exception('配置文件不完整');
        }
        return true;
    }

}
