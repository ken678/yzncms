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
// | https://github.com/5ini99/think-addons
// | https://github.com/karsonzhang/fastadmin-addons
// +----------------------------------------------------------------------
namespace sys;

use app\common\library\Menu as MenuLib;
use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;
use think\Db;
use think\Exception;
use util\Sql;

class AddonService
{
    /**
     * 安装插件.
     * @param string $name   插件名称
     * @param boolean $force  是否覆盖
     * @throws Exception
     * @return bool
     */
    public static function install($name, $force = false)
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
                $admin_list = property_exists($addon, 'admin_list') ? $addon->admin_list : '';
                //添加菜单
                MenuLib::addAddonMenu($info, $admin_list);
            }
            self::runSQL($name);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        // 刷新
        self::refresh();
        return true;
    }

    /**
     * 卸载插件.
     * @param string $name
     * @param boolean $force 是否强制卸载
     * @throws Exception
     * @return bool
     */
    public static function uninstall($name, $force = false)
    {
        if (!$name || !is_dir(ADDON_PATH . $name)) {
            throw new Exception('插件不存在！');
        }
        // 移除插件基础资源目录
        $destAssetsDir = self::getDestAssetsDir($name);
        if (is_dir($destAssetsDir)) {
            \util\File::del_dir($destAssetsDir);
        }
        // 执行卸载脚本
        try {
            // 默认禁用该插件
            $info = get_addon_info($name);
            if ($info['status'] != -1) {
                $info['status'] = -1;
                set_addon_info($name, $info);
            }
            //删除插件后台菜单
            if (isset($info['has_adminlist']) && $info['has_adminlist']) {
                MenuLib::delAddonMenu($info);
            }
            $class = get_addon_class($name);
            if (class_exists($class)) {
                $addon = new $class();
                $addon->uninstall();
            };
            self::runSQL($name, 'uninstall');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        // 刷新
        self::refresh();
        return true;
    }

    /**
     * 启用.
     * @param string $name  插件名称
     * @param bool   $force 是否强制覆盖
     * @return bool
     */
    public static function enable($name, $force = false)
    {
        if (!$name || !is_dir(ADDON_PATH . $name)) {
            throw new Exception('插件不存在！');
        }
        //执行启用脚本
        try {
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

        $info           = get_addon_info($name);
        $info['status'] = 1;
        set_addon_info($name, $info);
        // 刷新
        self::refresh();
        return true;
    }

    /**
     * 禁用.
     * @param string $name  插件名称
     * @param bool   $force 是否强制禁用
     * @throws Exception
     * @return bool
     */
    public static function disable($name, $force = false)
    {
        if (!$name || !is_dir(ADDON_PATH . $name)) {
            throw new Exception('插件不存在！');
        }
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
        $info           = get_addon_info($name);
        $info['status'] = 0;
        set_addon_info($name, $info);
        // 刷新
        self::refresh();
        return true;
    }

    /**
     * 离线安装
     * @param string $file 插件压缩包
     */
    public static function local($file)
    {
        $addonsTempDir = self::getAddonsBackupDir();
        if (!$file || !$file instanceof \think\File) {
            throw new Exception('没有文件上传或服务器上传限制');
        }
        $uploadFile = $file->rule('uniqid')->validate(['size' => 102400000, 'ext' => 'zip'])->move($addonsTempDir);
        if (!$uploadFile) {
            // 上传失败获取错误信息
            throw new Exception($file->getError());
        }
        $tmpFile = $addonsTempDir . $uploadFile->getSaveName();
        $info    = [];
        $zip     = new ZipFile();
        try {
            // 打开插件压缩包
            try {
                $zip->openFile($tmpFile);
            } catch (ZipException $e) {
                @unlink($tmpFile);
                throw new Exception('无法打开压缩文件');
            }

            $config = self::getInfoIni($zip);
            // 判断插件标识
            $name = isset($config['name']) ? $config['name'] : '';
            if (!$name) {
                throw new Exception('插件info.ini文件不正确');
            }

            // 判断插件是否存在
            if (!preg_match("/^[a-zA-Z0-9]+$/", $name)) {
                throw new Exception('插件名称不正确');
            }

            // 判断新插件是否存在
            $newAddonDir = self::getAddonDir($name);
            if (is_dir($newAddonDir)) {
                throw new Exception('插件已经存在');
            }

            //创建插件目录
            @mkdir($newAddonDir, 0755, true);
            // 解压到插件目录
            try {
                $zip->extractTo($newAddonDir);
            } catch (ZipException $e) {
                @unlink($newAddonDir);
                throw new Exception('无法解压缩文件');
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } finally {
            $zip->close();
            unset($uploadFile);
            @unlink($tmpFile);
        }
    }

    /**
     * 刷新插件缓存文件.
     * @throws Exception
     * @return bool
     */
    public static function refresh()
    {
        $file = ROOT_PATH . 'config' . DS . 'addons.php';

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
        $sql_file = ADDON_PATH . "{$name}" . DS . "{$Dir}.sql";
        if (file_exists($sql_file)) {
            $sql_statement = Sql::getSqlFromFile($sql_file);
            if (!empty($sql_statement)) {
                foreach ($sql_statement as $value) {
                    $value = str_ireplace('__PREFIX__', config('database.prefix'), $value);
                    $value = str_ireplace('INSERT INTO ', 'INSERT IGNORE INTO ', $value);
                    try {
                        Db::execute($value);
                    } catch (\Exception $e) {
                        throw new Exception("导入SQL失败，请检查{$name}.sql的语句是否正确");
                    }
                }
            }
        }
        return true;
    }

    /**
     * 匹配配置文件中info信息
     * @param ZipFile $zip
     * @return array|false
     * @throws Exception
     */
    protected static function getInfoIni($zip)
    {
        $config = [];
        // 读取插件信息
        try {
            $info   = $zip->getEntryContents('info.ini');
            $config = parse_ini_string($info);
        } catch (ZipException $e) {
            throw new Exception('插件info.ini文件不存在！');
        }
        return $config;
    }

    /**
     * 获取插件备份目录
     */
    public static function getAddonsBackupDir()
    {
        $dir = ROOT_PATH . 'runtime' . DS . 'addons' . DS;
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        return $dir;
    }

    /**
     * 获取指定插件的目录
     */
    public static function getAddonDir($name)
    {
        $dir = ADDON_PATH . $name . DS;
        return $dir;
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
     * @param string $name 插件名称
     * @throws Exception
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
