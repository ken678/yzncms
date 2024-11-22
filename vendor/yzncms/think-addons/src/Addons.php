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
// | 插件基类 插件需要继承此类
// +----------------------------------------------------------------------
namespace think;

use think\facade\Config;
use think\facade\View;

abstract class Addons
{
    // 视图实例对象
    protected $view = null;
    // 当前错误信息
    protected $error;
    // 插件目录
    public $addons_path = '';
    // 当前插件标识
    protected $addonName = '';
    // 插件配置
    protected $addon_config;
    // 插件信息
    protected $addon_info;

    /**
     * 架构函数
     * @access public
     */
    public function __construct($name = null)
    {
        $name = is_null($name) ? $this->getName() : $name;
        //设置插件标识
        $this->addonName = $name;

        // 获取当前插件目录
        $this->addons_path = ADDON_PATH . $this->addonName . DS;

        $this->addon_config = "addon_{$this->addonName}_config";
        $this->addon_info   = "addon_{$this->addonName}_info";

        $this->view = clone View::instance();
        $this->view->config(['view_path' => $this->addons_path]);

        // 控制器初始化
        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }
    }

    /**
     * @title 获取当前模块名
     * @return string
     */
    final public function getName()
    {
        if ($this->addonName) {
            return $this->addonName;
        }
        $data = explode('\\', get_class($this));
        return strtolower(array_pop($data));
    }

    /**
     * 设置插件标识
     * @param $name
     */
    final public function setName($name)
    {
        $this->addonName = $name;
    }

    /**
     * 读取基础配置信息
     * @param string $name
     * @return array
     */
    final public function getInfo()
    {
        $info = Config::get($this->addon_info, []);
        if ($info) {
            return $info;
        }

        $info      = [];
        $info_file = $this->addons_path . 'info.ini';
        if (is_file($info_file)) {
            $info = parse_ini_file($info_file, true, INI_SCANNER_TYPED) ?: [];
            //$info['url'] = addon_url($name);
        }
        Config::set($info, $this->addon_info);
        return $info ? $info : [];
    }

    /**
     * 设置插件信息数据.
     * @param array $value
     * @return array
     */
    final public function setInfo($value = [])
    {
        $info = $this->getInfo();
        $info = array_merge($info, $value);
        Config::set($info, $this->addon_info);
        return $info;
    }

    /**
     * 检查基础配置信息是否完整
     * @return bool
     */
    final public function checkInfo()
    {
        $info            = $this->getInfo();
        $info_check_keys = ['name', 'title', 'description', 'status', 'author', 'version'];
        foreach ($info_check_keys as $value) {
            if (!array_key_exists($value, $info)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 获取插件的配置数组
     */
    final public function getAddonConfig()
    {
        $config = Config::get($this->addon_config, []);
        if ($config) {
            return $config;
        }
        $config     = [];
        $configFile = $this->addons_path . 'config.php';
        if (is_file($configFile)) {
            $configArr = include $configFile;
            if (is_array($configArr)) {
                foreach ($configArr as $key => $value) {
                    $config[$value['name']] = $value['value'] ?? '';
                }
                unset($configArr);
            }
        }
        Config::set($config, $this->addon_config);
        return $config;
    }

    /**
     * 设置配置数据
     * @param       $name
     * @param array $value
     * @return array
     */
    final public function setAddonConfig($value = [])
    {
        $config = $this->getAddonConfig();
        $config = array_merge($config, $value);
        Config::set($config, $this->addon_config);
        return $config;
    }

    /**
     * 获取完整配置列表.
     * @return array
     */
    final public function getFullConfig()
    {
        $fullConfigArr = [];
        $configFile    = $this->addons_path . 'config.php';
        if (is_file($configFile)) {
            $fullConfigArr = include $configFile;
        }
        return $fullConfigArr;
    }

    /**
     * 模板变量赋值
     * @access protected
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    final protected function assign($name, $value = '')
    {
        $this->view->assign($name, $value);
    }

    /**
     * 加载模板和页面输出 可以返回输出内容
     * @access public
     * @param string $template 模板文件名或者内容
     * @param array $vars 模板输出变量
     */
    public function fetch($template = '', $vars = [])
    {
        if (!is_file($template)) {
            $template = '/' . $template;
        }
        echo $this->view->fetch($template, $vars);
    }

    /**
     * 渲染内容输出
     * @access public
     * @param string $content 内容
     * @param array $vars 模板输出变量
     * @param array $replace 替换内容
     * @param array $config 模板参数
     * @return mixed
     */
    public function display($content, $vars = [])
    {
        echo $this->view->display($content, $vars);
    }

    /**
     * 获取当前错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    //必须实现安装
    abstract public function install();

    //必须卸载插件方法
    abstract public function uninstall();
}
