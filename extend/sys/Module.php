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
// | 模块管理
// +----------------------------------------------------------------------

namespace sys;

use app\admin\model\Module as Module_Model;
use think\Db;
use util\Sql;

class Module
{
    //模块所处目录路径
    protected $appPath = APP_PATH;
    //模块模板安装路径
    protected $templatePath;
    protected $installdir;
    protected $uninstaldir;
    //静态资源目录
    public $extresPath = null;
    //已安装模块列表
    protected $moduleList = array();
    //系统模块，隐藏
    protected $systemModuleList = array('admin', 'index', 'api', 'attachment', 'common', 'addons');
    //当前模块名称
    private $moduleName = null;
    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->extresPath = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR;
        $this->templatePath = TEMPLATE_PATH . 'default' . DIRECTORY_SEPARATOR;
    }

    /**
     * 获取所有模块信息
     */
    public function getAll()
    {
        $dirs = array_map('basename', glob($this->appPath . '*', GLOB_ONLYDIR));
        if ($dirs === false || !file_exists($this->appPath)) {
            $this->error = '模块目录不可读或者不存在';
            return false;
        }
        // 正常模块(包括已安装和未安装)
        $dirs_arr = array_diff($dirs, $this->systemModuleList);

        // 读取数据库已经安装模块表
        $modules = Module_Model::order('listorder asc')->select();

        //数量
        //$count = count($dirs_arr);
        $list = [];
        foreach ($dirs_arr as $module) {
            $list[$module] = $this->getInfoFromFile($module);
        }
        return $list;
    }

    /**
     * 从文件获取模块信息
     * @param string $name 模块名称
     * @return array|mixed
     */
    public function getInfoFromFile($moduleName = '')
    {
        if (empty($moduleName)) {
            $this->error = '模块名称不能为空！';
            return false;
        }
        $config = array(
            //模块目录
            'module' => $moduleName,
            //模块名称
            'modulename' => $moduleName,
            //模块简介
            'introduce' => '',
            //模块作者
            'author' => '',
            //作者地址
            'authorsite' => '',
            //作者邮箱
            'authoremail' => '',
            //版本号，请不要带除数字外的其他字符
            'version' => '',
            //适配最低yzncms版本，
            'adaptation' => '',
            //签名
            'sign' => '',
            //依赖模块
            'depend' => array(),
            //行为
            'tags' => array(),
            //缓存
            'cache' => array(),
        );

        // 从配置文件获取
        if (is_file($this->appPath . $moduleName . DIRECTORY_SEPARATOR . 'info.php')) {
            $moduleConfig = include $this->appPath . $moduleName . DIRECTORY_SEPARATOR . 'info.php';
            $config = array_merge($config, $moduleConfig);
        }

        //检查是否安装，如果安装了，加载模块安装后的相关配置信息
        if ($this->isInstall($moduleName)) {
            $moduleList = cache('Module');
            $config = array_merge($moduleList[$moduleName], $config);
        }
        return $config;
    }

    /**
     * 执行模块安装
     * @param type $moduleName 模块名(目录名)
     * @return boolean
     */
    public function install($moduleName = '')
    {
        defined('INSTALL') or define("INSTALL", true);
        if (empty($moduleName)) {
            if ($this->moduleName) {
                $moduleName = $this->moduleName;
            } else {
                $this->error = '请选择需要安装的模块！';
                return false;
            }
        }
        //已安装模块列表
        $moduleList = cache('Module');
        //目录权限检测
        /*if ($this->competence($moduleName) !== true) {
        return false;
        }*/
        //加载模块基本配置
        $config = $this->getInfoFromFile($moduleName);
        //版本检查
        if ($config['adaptation']) {
            if (version_compare('1.0.0', $config['adaptation'], '>=') == false) {
                $this->error = '该模块要求系统最低版本为：' . $config['adaptation'] . '！';
                return false;
            }
        }
        //依赖模块检测
        if (!empty($config['depend']) && is_array($config['depend'])) {
            foreach ($config['depend'] as $mod) {
                if ('content' == $mod) {
                    continue;
                }
                if (!isset($moduleList[$mod])) {
                    $this->error = "安装该模块，需要安装依赖模块 {$mod} !";
                    return false;
                }
            }
        }
        //检查模块是否已经安装
        if ($this->isInstall($moduleName)) {
            $this->error = '模块已经安装，无法重复安装！';
            return false;
        }
        //保存在安装表
        if (!Module_Model::create($config, true)) {
            $this->error = '安装失败！';
            return false;
        }
        //执行数据库脚本安装
        $this->runSQL($moduleName);
        //执行菜单项安装
        if ($this->installMenu($moduleName) !== true) {
            $this->installRollback($moduleName);
            return false;
        }
        //缓存注册
        if (!empty($config['cache'])) {
            if (model('common/Cache')->installModuleCache($config['cache'], $config) !== true) {
                $this->error = model('common/Cache')->getError();
                $this->installRollback($moduleName);
                return false;
            }
        }
        //前台模板
        $this->installdir = $this->appPath . "{$moduleName}" . DIRECTORY_SEPARATOR . "install" . DIRECTORY_SEPARATOR;
        if (is_dir($this->installdir . "template" . DIRECTORY_SEPARATOR)) {
            //拷贝模板到前台模板目录中去
            copydirs($this->installdir . "template" . DIRECTORY_SEPARATOR, $this->templatePath);
        }
        //静态资源文件
        if (file_exists($this->installdir . "public" . DIRECTORY_SEPARATOR)) {
            //拷贝模板到前台模板目录中去
            copydirs($this->installdir . "public" . DIRECTORY_SEPARATOR, $this->extresPath . strtolower($moduleName) . '/');
        }
        //更新缓存
        cache('Module', null);
        return true;
    }

    /**
     * 模块卸载
     * @param type $moduleName 模块名(目录名)
     * @return boolean
     */
    public function uninstall($moduleName = '')
    {
        defined('UNINSTALL') or define("UNINSTALL", true);
        if (empty($moduleName)) {
            if ($this->moduleName) {
                $moduleName = $this->moduleName;
            } else {
                $this->error = '请选择需要卸载的模块！';
                return false;
            }
        }
        //设置脚本最大执行时间
        @set_time_limit(0);
        /*if ($this->competence($moduleName) !== true) {
        return false;
        }*/
        //取得该模块数据库中记录的安装信息
        $info = Module_Model::where(array('module' => $moduleName))->find();
        if (empty($info)) {
            $this->error = '该模块未安装，无需卸载！';
            return false;
        }
        if ($info['iscore']) {
            $this->error = '内置模块，不能卸载！';
            return false;
        }
        //删除
        if (Module_Model::where(array('module' => $moduleName))->delete() == false) {
            $this->error = '卸载失败！';
            return false;
        }
        //注销缓存
        model('common/Cache')->deleteCacheModule($moduleName);

        //执行数据库脚本卸载
        $this->runSQL($moduleName, 'uninstall');
        //删除菜单项
        $this->uninstallMenu($moduleName);

        //删除模块前台模板
        if (is_dir($this->templatePath . $moduleName . DIRECTORY_SEPARATOR)) {
            rmdirs($this->templatePath . $moduleName . DIRECTORY_SEPARATOR);
        }
        //静态资源移除
        if (is_dir($this->extresPath . strtolower($moduleName) . DIRECTORY_SEPARATOR)) {
            rmdirs($this->extresPath . strtolower($moduleName) . DIRECTORY_SEPARATOR);
        }
        //更新缓存
        cache('Module', null);
        return true;
    }

    /**
     * 安装菜单项
     * @param type $moduleName 模块名称
     * @param type $file 文件
     * @return boolean
     */
    private function installMenu($moduleName = '', $file = 'menu')
    {
        if (empty($moduleName)) {
            if ($this->moduleName) {
                $moduleName = $this->moduleName;
            } else {
                $this->error = '模块名称不能为空！';
                return false;
            }
        }
        $path = $this->appPath . "{$moduleName}" . DIRECTORY_SEPARATOR . "install" . DIRECTORY_SEPARATOR . "{$file}.php";
        //检查是否有安装脚本
        if (!file_exists($path)) {
            return true;
        }
        $menu = include $path;
        if (empty($menu)) {
            return true;
        }
        $status = model('admin/Menu')->installModuleMenu($menu, $this->getInfoFromFile($moduleName));
        if ($status === true) {
            return true;
        } else {
            $this->error = model('admin/Menu')->getError() ?: '安装菜单项出现错误！';
            return false;
        }
    }

    /**
     * 卸载菜单项项
     * @param type $moduleName
     * @return boolean
     */
    private function uninstallMenu($moduleName = '')
    {
        if (empty($moduleName)) {
            if ($this->moduleName) {
                $moduleName = $this->moduleName;
            } else {
                $this->error = '模块名称不能为空！';
                return false;
            }
        }
        Db::name('menu')->where(array('app' => ucwords($moduleName)))->delete();
        return true;
    }

    /**
     * 安装回滚
     * @param type $moduleName 模块名(目录名)
     */
    private function installRollback($moduleName = '')
    {
        if (empty($moduleName)) {
            if ($this->moduleName) {
                $moduleName = $this->moduleName;
            } else {
                $this->error = '模块名称不能为空！';
                return false;
            }
        }
        //删除安装状态
        Module_Model::where(array('module' => $moduleName))->delete();
        //更新缓存
        cache('Module', null);
    }

    /**
     * 执行安装数据库脚本
     * @param type $moduleName 模块名(目录名)
     * @return boolean
     */
    private function runSQL($moduleName = '', $Dir = 'install')
    {
        if (empty($moduleName)) {
            if ($this->moduleName) {
                $moduleName = $this->moduleName;
            } else {
                $this->error = '模块名称不能为空！';
                return false;
            }
        }
        $sql_file = $this->appPath . "{$moduleName}" . DIRECTORY_SEPARATOR . "{$Dir}" . DIRECTORY_SEPARATOR . "{$moduleName}.sql";
        if (file_exists($sql_file)) {
            $sql_statement = Sql::getSqlFromFile($sql_file);
            if (!empty($sql_statement)) {
                foreach ($sql_statement as $value) {
                    try {
                        Db::execute($value);
                    } catch (\Exception $e) {
                        $this->error = '导入SQL失败，请检查install.sql的语句是否正确';
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * 是否已经安装
     * @param type $moduleName 模块名(目录名)
     * @return boolean
     */
    public function isInstall($moduleName = '')
    {
        if (empty($moduleName)) {
            $this->error = '模块名称不能为空！';
            return false;
        }
        $moduleList = cache('Module');
        return (isset($moduleList[$moduleName]) && $moduleList[$moduleName]) ? true : false;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

}
