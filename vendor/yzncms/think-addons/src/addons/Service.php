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
namespace think\addons;

use app\common\library\Cache as CacheLib;
use app\common\library\Menu as MenuLib;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use think\Db;
use think\Exception;
use think\facade\Cache;
use util\File;
use util\Sql;

class Service
{
    /**
     * 插件列表
     */
    public static function addons($params = [])
    {
        $params['domain'] = request()->host(true);
        return self::sendRequest('/addon/index', $params, 'GET');
    }

    /**
     * 检测插件是否购买授权
     */
    public static function isBuy($name, $extend = [])
    {
        $params = array_merge(['name' => $name, 'domain' => request()->host(true)], $extend);
        return self::sendRequest('/addon/isbuy', $params, 'POST');
    }

    /**
     * 远程下载插件
     *
     * @param string $name   插件名称
     * @param array  $extend 扩展参数
     * @return  string
     */
    public static function download($name, $extend = [])
    {
        $addonsTempDir = self::getAddonsBackupDir();
        $tmpFile       = $addonsTempDir . $name . ".zip";
        try {
            $client   = self::getClient();
            $response = $client->get('/addon/download', ['query' => array_merge(['name' => $name], $extend)]);
            $body     = $response->getBody();
            $content  = $body->getContents();
            if ($content == '' || stripos($content, '<title>系统发生错误</title>') !== false) {
                throw new Exception("插件下载失败");
            }
            if (substr($content, 0, 1) === '{') {
                $json = (array) json_decode($content, true);
                if (isset($json['code'])) {
                    //下载返回错误，抛出异常
                    throw new AddonException($json['msg'], $json['code'], $json['data']);
                }
            };
        } catch (TransferException $e) {
            throw new Exception("插件下载失败");
        }

        if ($write = fopen($tmpFile, 'w')) {
            fwrite($write, $content);
            fclose($write);
            return $tmpFile;
        }
        throw new Exception("没有权限写入临时文件");
    }

    /**
     * 安装插件.
     * @param string $name   插件名称
     * @param boolean $force  是否覆盖
     * @param array   $extend 扩展参数
     * @throws Exception
     * @return bool
     */
    public static function install($name, $force = false, $extend = [])
    {
        if (!$name || (is_dir(ADDON_PATH . $name) && !$force)) {
            throw new Exception('插件已经存在');
        }
        $extend['domain'] = request()->host(true);

        // 远程下载插件
        $tmpFile = self::download($name, $extend);

        $addonDir = self::getAddonDir($name);

        try {
            // 解压插件压缩包到插件目录
            self::unzip($name);
            // 检查插件是否完整
            self::check($name);
            if (!$force) {
                self::noconflict($name);
            }
        } catch (AddonException $e) {
            @File::del_dir($addonDir);
            throw new AddonException($e->getMessage(), $e->getCode(), $e->getData());
        } catch (Exception $e) {
            @File::del_dir($addonDir);
            throw new Exception($e->getMessage());
        }
        try {
            // 默认启用该插件
            $info = get_addon_info($name);
            /*if ($info['status']) {
            $info['status'] = 0;
            set_addon_info($name, $info);
            }*/
            // 执行安装脚本
            $class = get_addon_class($name);
            if (class_exists($class)) {
                $addon = new $class();
                $addon->install();

                if (isset($info['has_adminlist']) && $info['has_adminlist']) {
                    $admin_list = property_exists($addon, 'admin_list') ? $addon->admin_list : [];
                    //添加菜单
                    MenuLib::addAddonMenu($admin_list, $info);
                }
                $cache_list = property_exists($addon, 'cache_list') ? $addon->cache_list : [];
                if ($cache_list) {
                    CacheLib::installAddonCache($cache_list, $info);
                }
            }
        } catch (Exception $e) {
            @File::del_dir($addonDir);
            throw new Exception($e->getMessage());
        }
        self::runSQL($name);
        // 启用插件
        self::enable($name, true);

        $info['config']   = get_addon_config($name) ? 1 : 0;
        $info['testdata'] = is_file(Service::getTestdataFile($name));
        return $info;
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
        $info = get_addon_info($name);
        if ($info['status'] == 1) {
            throw new Exception('请先禁用插件再进行卸载');
        }
        if (!$force) {
            self::noconflict($name);
        }
        // 移除插件全局资源文件
        if ($force) {
            $list = self::getGlobalFiles($name);
            foreach ($list as $k => $v) {
                @unlink(ROOT_PATH . $v);
            }
        }
        // 执行卸载脚本
        try {
            $class = get_addon_class($name);
            if (class_exists($class)) {
                $addon = new $class();
                $addon->uninstall();

                //删除插件后台菜单
                if (isset($info['has_adminlist']) && $info['has_adminlist']) {
                    MenuLib::delAddonMenu($name);
                }
                $cache_list = property_exists($addon, 'cache_list') ? $addon->cache_list : [];
                if ($cache_list) {
                    CacheLib::deleteCacheAddon($info['name']);
                }
            };
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        // 移除插件目录
        File::del_dir(ADDON_PATH . $name);
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
        if (!$force) {
            self::noconflict($name);
        }
        $addonDir        = self::getAddonDir($name);
        $sourceAssetsDir = self::getSourceAssetsDir($name);
        $destAssetsDir   = self::getDestAssetsDir($name);
        if (is_dir($sourceAssetsDir)) {
            File::copy_dir($sourceAssetsDir, $destAssetsDir);
        }

        // 复制application到全局
        foreach (self::getCheckDirs() as $k => $dir) {
            if (is_dir($addonDir . $dir)) {
                File::copy_dir($addonDir . $dir, ROOT_PATH . $dir);
            }
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

        if (isset($info['has_adminlist']) && $info['has_adminlist']) {
            MenuLib::enableAddonMenu($name);
        }
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
        if (!$force) {
            self::noconflict($name);
        }
        // 移除插件全局文件
        $list = self::getGlobalFiles($name);
        $dirs = [];
        foreach ($list as $k => $v) {
            $file   = ROOT_PATH . $v;
            $dirs[] = dirname($file);
            @unlink($file);
        }

        // 移除插件空目录
        $dirs = array_filter(array_unique($dirs));
        foreach ($dirs as $k => $v) {
            File::remove_empty_folder($v);
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
        if (isset($info['has_adminlist']) && $info['has_adminlist']) {
            MenuLib::disableAddonMenu($name);
        }
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

            try {
                // 默认启用该插件
                $info = get_addon_info($name);
                /*if ($info['status']) {
                $info['status'] = 0;
                set_addon_info($name, $info);
                }*/
                // 执行安装脚本
                $class = get_addon_class($name);
                if (class_exists($class)) {
                    $addon = new $class();
                    $addon->install();

                    if (isset($info['has_adminlist']) && $info['has_adminlist']) {
                        $admin_list = property_exists($addon, 'admin_list') ? $addon->admin_list : [];
                        //添加菜单
                        MenuLib::addAddonMenu($admin_list, $info);
                    }
                    $cache_list = property_exists($addon, 'cache_list') ? $addon->cache_list : [];
                    if ($cache_list) {
                        CacheLib::installAddonCache($cache_list, $info);
                    }
                }
            } catch (Exception $e) {
                @File::del_dir($newAddonDir);
                throw new Exception($e->getMessage());
            }
            self::runSQL($name);
            // 启用插件
            //self::enable($name, true);
        } catch (AddonException $e) {
            throw new AddonException($e->getMessage(), $e->getCode(), $e->getData());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } finally {
            $zip->close();
            unset($uploadFile);
            @unlink($tmpFile);
        }
        $info['config']   = get_addon_config($name) ? 1 : 0;
        $info['testdata'] = is_file(Service::getTestdataFile($name));
        return $info;
    }

    /**
     * 升级插件
     *
     * @param string $name   插件名称
     * @param array  $extend 扩展参数
     */
    public static function upgrade($name, $extend = [])
    {
        $info = get_addon_info($name);
        if ($info['status'] == 1) {
            throw new Exception('请先禁用插件');
        }
        $config = get_addon_config($name);
        if ($config) {
            //备份配置
        }

        // 远程下载插件
        $tmpFile = self::download($name, $extend);

        // 备份插件文件
        self::backup($name);

        $addonDir = self::getAddonDir($name);

        // 删除插件目录下的application和public
        $files = self::getCheckDirs();
        foreach ($files as $index => $file) {
            @File::del_dir($addonDir . $file);
        }

        try {
            // 解压插件
            self::unzip($name);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } finally {
            // 移除临时文件
            @unlink($tmpFile);
        }

        if ($config) {
            // 还原配置
            set_addon_config($name, $config);
        }

        // 导入
        self::runSQL($name);

        // 执行升级脚本
        try {
            $addonName = ucfirst($name);
            //创建临时类用于调用升级的方法
            $sourceFile = $addonDir . $addonName . ".php";
            $destFile   = $addonDir . $addonName . "Upgrade.php";

            $classContent = str_replace("class {$addonName} extends", "class {$addonName}Upgrade extends", file_get_contents($sourceFile));

            //创建临时的类文件
            file_put_contents($destFile, $classContent);

            $className = "\\addons\\" . $name . "\\" . $addonName . "Upgrade";
            $addon     = new $className($name);

            //调用升级的方法
            if (method_exists($addon, "upgrade")) {
                $addon->upgrade();
            }

            //移除临时文件
            @unlink($destFile);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        // 刷新
        self::refresh();

        //必须变更版本号
        $info['version'] = isset($extend['version']) ? $extend['version'] : $info['version'];

        $info['config']    = get_addon_config($name) ? 1 : 0;
        $info['bootstrap'] = is_file(self::getBootstrapFile($name));
        return $info;
    }

    /**
     * 解压插件
     *
     * @param string $name 插件名称
     * @return  string
     * @throws  Exception
     */
    public static function unzip($name)
    {
        if (!$name) {
            throw new Exception('参数不正确');
        }
        $addonsBackupDir = self::getAddonsBackupDir();
        $file            = $addonsBackupDir . $name . '.zip';

        // 打开插件压缩包
        $zip = new ZipFile();
        try {
            $zip->openFile($file);
        } catch (ZipException $e) {
            $zip->close();
            throw new Exception('无法打开ZIP文件');
        }

        $dir = self::getAddonDir($name);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755);
        }

        // 解压插件压缩包
        try {
            $zip->extractTo($dir);
        } catch (ZipException $e) {
            throw new Exception('无法解压ZIP文件');
        } finally {
            $zip->close();
        }
        return $dir;
    }

    /**
     * 刷新插件缓存文件.
     * @throws Exception
     * @return bool
     */
    public static function refresh()
    {
        //刷新addons.js
        $addons       = get_addon_list();
        $bootstrapArr = [];
        foreach ($addons as $name => $addon) {
            $bootstrapFile = self::getBootstrapFile($name);
            if ($addon['status'] > 0 && is_file($bootstrapFile)) {
                $bootstrapArr[] = file_get_contents($bootstrapFile);
            }
        }
        $addonsFile = ROOT_PATH . str_replace("/", DS, "public/static/libs/layui_exts/addons.js");
        if ($handle = fopen($addonsFile, 'w')) {
            $tpl = <<<EOD
layui.define([], function(exports) {
    {__JS__}
    exports('addons', '');
});
EOD;
            fwrite($handle, str_replace("{__JS__}", implode("\n", $bootstrapArr), $tpl));
            fclose($handle);
        } else {
            throw new Exception("文件addons.js没有写入权限");
        }

        Cache::rm("addons");
        Cache::rm("hooks");

        $file = self::getExtraAddonsFile();

        $config = get_addon_autoload_config(true);
        if ($config['autoload']) {
            return;
        }

        if (!File::is_really_writable($file)) {
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
     * 备份插件
     * @param string $name 插件名称
     * @return bool
     * @throws Exception
     */
    public static function backup($name)
    {
        $addonsBackupDir = self::getAddonsBackupDir();
        $file            = $addonsBackupDir . $name . '-backup-' . date("YmdHis") . '.zip';
        $zipFile         = new ZipFile();
        try {
            $zipFile
                ->addDirRecursive(self::getAddonDir($name))
                ->saveAsFile($file)
                ->close();
        } catch (ZipException $e) {

        } finally {
            $zipFile->close();
        }

        return true;
    }

    /**
     * 是否有冲突
     *
     * @param string $name 插件名称
     * @return  boolean
     * @throws  AddonException
     */
    public static function noconflict($name)
    {
        // 检测冲突文件
        $list = self::getGlobalFiles($name, true);
        if ($list) {
            //发现冲突文件，抛出异常
            throw new AddonException("发现冲突文件", -3, ['conflictlist' => $list]);
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
     * 获取插件在全局的文件
     *
     * @param string  $name         插件名称
     * @param boolean $onlyconflict 是否只返回冲突文件
     * @return  array
     */
    public static function getGlobalFiles($name, $onlyconflict = false)
    {
        $list         = [];
        $addonDir     = self::getAddonDir($name);
        $checkDirList = self::getCheckDirs();
        $checkDirList = array_merge($checkDirList, ['assets']);

        $assetDir = self::getDestAssetsDir($name);

        // 扫描插件目录是否有覆盖的文件
        foreach ($checkDirList as $k => $dirName) {
            //检测目录是否存在
            if (!is_dir($addonDir . $dirName)) {
                continue;
            }
            //匹配出所有的文件
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($addonDir . $dirName, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $fileinfo) {
                if ($fileinfo->isFile()) {
                    $filePath = $fileinfo->getPathName();
                    //如果名称为assets需要做特殊处理
                    if ($dirName === 'assets') {
                        $path = str_replace(ROOT_PATH, '', $assetDir) . str_replace($addonDir . $dirName . DS, '', $filePath);
                    } else {
                        $path = str_replace($addonDir, '', $filePath);
                    }
                    if ($onlyconflict) {
                        $destPath = ROOT_PATH . $path;
                        if (is_file($destPath)) {
                            if (filesize($filePath) != filesize($destPath) || md5_file($filePath) != md5_file($destPath)) {
                                $list[] = $path;
                            }
                        }
                    } else {
                        $list[] = $path;
                    }
                }
            }
        }
        $list = array_filter(array_unique($list));
        return $list;
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
     * 获取插件行为、路由配置文件
     * @return string
     */
    public static function getExtraAddonsFile()
    {
        return ROOT_PATH . 'config' . DS . 'addons.php';
    }

    /**
     * 获取bootstrap.js路径
     * @return string
     */
    public static function getBootstrapFile($name)
    {
        return ADDON_PATH . $name . DS . 'bootstrap.js';
    }

    /**
     * 获取testdata.sql路径
     * @return string
     */
    public static function getTestdataFile($name)
    {
        return ADDON_PATH . $name . DS . 'testdata.sql';
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
     * 获取检测的全局文件夹目录
     * @return  array
     */
    protected static function getCheckDirs()
    {
        return [
            'application',
            'public',
            'templates',
        ];
    }

    /**
     * 获取插件源资源文件夹
     * @param   string $name 插件名称
     * @return  string
     */
    protected static function getSourceAssetsDir($name)
    {
        return ADDON_PATH . $name . DS . 'assets' . DS;
    }

    /**
     * 获取插件目标资源文件夹
     * @param   string $name 插件名称
     * @return  string
     */
    protected static function getDestAssetsDir($name)
    {
        $assetsDir = ROOT_PATH . str_replace("/", DS, "public/static/addons/{$name}/");
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

    /**
     * 获取远程服务器
     * @return  string
     */
    protected static function getServerUrl()
    {
        return config('api_url');
    }

    /**
     * 获取请求对象
     * @return Client
     */
    public static function getClient()
    {
        $options = [
            'base_uri'        => self::getServerUrl(),
            'timeout'         => 30,
            'connect_timeout' => 30,
            'verify'          => false,
            'http_errors'     => false,
            'headers'         => [
                'X-REQUESTED-WITH' => 'XMLHttpRequest',
                'Referer'          => dirname(request()->root(true)),
                'User-Agent'       => 'YznAddon',
            ],
        ];
        static $client;
        if (empty($client)) {
            $client = new Client($options);
        }
        return $client;
    }

    /**
     * 发送请求
     * @return array
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function sendRequest($url, $params = [], $method = 'POST')
    {
        $json = [];
        try {
            $client   = self::getClient();
            $options  = strtoupper($method) == 'POST' ? ['form_params' => $params] : ['query' => $params];
            $response = $client->request($method, $url, $options);
            $body     = $response->getBody();
            $content  = $body->getContents();
            $json     = (array) json_decode($content, true);
        } catch (TransferException $e) {
            throw new Exception('网络错误!');
        } catch (\Exception $e) {
            throw new Exception('未知的数据格式!');
        }
        return $json;
    }

}
