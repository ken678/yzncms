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
// | https://github.com/5ini99/think-addons
// | https://github.com/karsonzhang/fastadmin-addons
// +----------------------------------------------------------------------
namespace sys;

use think\facade\Config;
use think\View;

abstract class Addons
{
    // 当前插件标识
    protected $name;
    protected $view    = null;
    public $addon_path = '';

    // 插件配置作用域
    protected $configRange = 'addonconfig';
    // 插件信息作用域
    protected $infoRange = 'addoninfo';

    public function __construct()
    {
        $this->name = $this->getName();
        // 获取当前插件目录
        $this->addon_path = ADDON_PATH . $this->name . DIRECTORY_SEPARATOR;

        // 初始化视图模型
        $config['view_path'] = $this->addon_path;
        $config              = array_merge(Config::get('template.'), $config);
        $this->view          = new View();
        $this->view          = $this->view->init($config);
        //加载插件函数文件
        if (file_exists($this->addon_path . 'common.php')) {
            include_once $this->addon_path . 'common.php';
        }
    }

    /**
     * @title 获取当前模块名
     * @return string
     */
    final public function getName()
    {
        $data = explode('\\', get_class($this));
        return strtolower(array_pop($data));
    }

    /**
     * 读取基础配置信息
     * @param string $name
     * @return array
     */
    final public function getInfo($name = '')
    {
        if (empty($name)) {
            $name = $this->name;
        }
        $info = Config::get($this->infoRange . $name);
        if ($info) {
            return $info;
        }
        $info_file = $this->addon_path . 'info.ini';
        if (is_file($info_file)) {
            $info = parse_ini_file($info_file, true, INI_SCANNER_TYPED) ?: [];
            //$info['url'] = addon_url($name);
        }
        Config::set($this->infoRange . $name, $info);
        return $info ? $info : [];
    }

    /**
     * @title 获取当前模块名
     * @return string
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
     * @title 获取插件的配置数组
     * @param string $name 可选模块名
     * @return array|mixed|null
     */
    final public function getAddonConfig($name = '')
    {
        if (empty($name)) {
            $name = $this->name;
        }
        $config = Config::get($this->configRange . $name);
        if ($config) {
            return $config;
        }
        $config_file = $this->addon_path . 'config.php';
        if (is_file($config_file)) {
            $temp_arr = include $config_file;
            foreach ($temp_arr as $key => $value) {
                $config[$value['name']] = $value['value'] ?? '';
            }
            unset($temp_arr);
        }
        Config::set($this->configRange . $name, $config);
        return $config;
    }

    /**
     * 获取完整配置列表.
     *
     * @param string $name
     *
     * @return array
     */
    final public function getFullConfig($name = '')
    {
        $fullConfigArr = [];
        if (empty($name)) {
            $name = $this->getName();
        }
        $config_file = $this->addon_path . 'config.php';
        if (is_file($config_file)) {
            $fullConfigArr = include $config_file;
        }
        return $fullConfigArr;
    }

    /**
     * 设置插件信息数据.
     * @param $name
     * @param array $value
     * @return array
     */
    final public function setInfo($name = '', $value = [])
    {
        if (empty($name)) {
            $name = $this->name;
        }
        $info = $this->getInfo($name);
        $info = array_merge($info, $value);
        Config::set($this->infoRange . $name, $info);
        return $info;
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
     * @param array $replace 替换内容
     * @param array $config 模板参数
     */
    public function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        if (!is_file($template)) {
            $template = '/' . $template;
        }
        // 关闭模板布局
        $this->view->engine->layout(false);

        echo $this->view->fetch($template, $vars, $replace, $config);
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
    public function display($content, $vars = [], $replace = [], $config = [])
    {
        // 关闭模板布局
        $this->view->engine->layout(false);

        echo $this->view->display($content, $vars, $replace, $config);
    }
    /**
     * 渲染内容输出
     * @access public
     * @param string $content 内容
     * @param array $vars 模板输出变量
     * @return mixed
     */
    public function show($content, $vars = [])
    {
        // 关闭模板布局
        $this->view->engine->layout(false);

        echo $this->view->fetch($content, $vars, [], [], true);
    }

    //必须实现安装
    abstract public function install();

    //必须卸载插件方法
    abstract public function uninstall();
}
