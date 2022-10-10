<?php

use think\Db;
use think\facade\Config;

// 插件目录
define('ADDON_PATH', ROOT_PATH . 'addons' . DS);
/**
 * 处理插件钩子
 * @param string $hook        钩子名称
 * @param mixed $params       传入参数
 * @param  boolean $is_return 是否返回（true:返回值，false:直接输入）
 * @param  bool   $once       只获取一个有效返回值
 * @return void
 */
function hook($hook, $params = [], $is_return = false, $once = false)
{
    if ($is_return == true) {
        return \think\facade\Hook::listen($hook, $params, $once);
    }
    \think\facade\Hook::listen($hook, $params, $once);
}

/**
 * 获得插件列表
 * @return array
 */
function get_addon_list()
{
    $results = scandir(ADDON_PATH);
    $list    = [];
    foreach ($results as $name) {
        if ($name === '.' or $name === '..') {
            continue;
        }
        if (is_file(ADDON_PATH . $name)) {
            continue;
        }
        $addonDir = ADDON_PATH . $name . DS;
        if (!is_dir($addonDir)) {
            continue;
        }
        if (!is_file($addonDir . ucfirst($name) . '.php')) {
            continue;
        }
        //这里不采用get_addon_info是因为会有缓存
        //$info = get_addon_info($name);
        $info_file = $addonDir . 'info.ini';
        if (!is_file($info_file)) {
            continue;
        }
        $info = parse_ini_file($info_file, true, INI_SCANNER_TYPED) ?: [];
        if (!isset($info['name'])) {
            continue;
        }
        //$info['url'] = addon_url($name);
        $list[$name] = $info;
    }
    return $list;
}

/**
 * 获得插件自动加载的配置.
 * @param  bool  $truncate  是否清除手动配置的钩子
 * @return array
 */
function get_addon_autoload_config($truncate = false)
{
    // 读取addons的配置
    $config = (array) Config::get('addons.');
    if ($truncate) {
        // 清空手动配置的钩子
        $config['hooks'] = [];
    }
    // 读取插件目录及钩子列表
    $base   = get_class_methods('\\think\\Addons');
    $base   = array_merge($base, ['install', 'uninstall', 'enable', 'disable']);
    $addons = get_addon_list();
    foreach ($addons as $name => $addon) {
        if (0 >= $addon['status']) {
            continue;
        }
        // 读取出所有公共方法
        $methods = (array) get_class_methods('\\addons\\' . $name . '\\' . ucfirst($name));
        // 跟插件基类方法做比对，得到差异结果
        $hooks = array_diff($methods, $base);
        // 循环将钩子方法写入配置中
        foreach ($hooks as $hook) {
            $hook = parse_name($hook, 0, false);
            if (!isset($config['hooks'][$hook])) {
                $config['hooks'][$hook] = [];
            }
            // 兼容手动配置项
            if (is_string($config['hooks'][$hook])) {
                $config['hooks'][$hook] = explode(',', $config['hooks'][$hook]);
            }
            if (!in_array($name, $config['hooks'][$hook])) {
                $config['hooks'][$hook][] = get_addon_class($name);
            }
        }
    }
    //TODO 模块的钩子
    $modules = Db::name('Module')->where('status', 1)->select();
    foreach ($modules as $name => $addon) {
        $name = $addon['module'];
        if (is_file(APP_PATH . $name . DIRECTORY_SEPARATOR . 'behavior' . DIRECTORY_SEPARATOR . 'Hooks.php')) {
            $methods = (array) get_class_methods('\\app\\' . $name . '\\behavior\\Hooks');
            $hooks   = array_diff($methods, $base);
            foreach ($hooks as $hook) {
                $hook = parse_name($hook, 0, false);
                if (!isset($config['hooks'][$hook])) {
                    $config['hooks'][$hook] = [];
                }
                // 兼容手动配置项
                if (is_string($config['hooks'][$hook])) {
                    $config['hooks'][$hook] = explode(',', $config['hooks'][$hook]);
                }
                if (!in_array($name, $config['hooks'][$hook])) {
                    $config['hooks'][$hook][] = '\\app\\' . $name . '\\behavior\\Hooks';
                }
            }
        }
    }
    return $config;
}

/**
 * 获取插件类的类名
 * @param $name         插件名
 * @param string $type  返回命名空间类型
 * @param string $class 当前类名
 * @return string
 */
function get_addon_class($name, $type = 'hook', $class = null)
{
    $name = parse_name($name);
    // 处理多级控制器情况
    if (!is_null($class) && strpos($class, '.')) {
        $class = explode('.', $class);

        $class[count($class) - 1] = parse_name(end($class), 1);
        $class                    = implode('\\', $class);
    } else {
        $class = parse_name(is_null($class) ? $name : $class, 1);
    }

    switch ($type) {
        case 'controller':
            $namespace = "\\addons\\" . $name . "\\controller\\" . $class;
            break;
        default:
            $namespace = "\\addons\\" . $name . "\\" . $class;
    }
    return class_exists($namespace) ? $namespace : '';
}

/**
 * 读取插件的基础信息
 * @param string $name 插件名
 * @return array
 */
function get_addon_info($name)
{
    $addon = get_addon_instance($name);
    if (!$addon) {
        return [];
    }
    return $addon->getInfo($name);
}

/**
 * 获取插件类的配置数组.
 * @param  string  $name  插件名
 * @return array
 */
function get_addon_fullconfig($name)
{
    $addon = get_addon_instance($name);
    if (!$addon) {
        return [];
    }

    return $addon->getFullConfig($name);
}

/**
 * 获取插件类的配置值值
 * @param  string  $name  插件名
 * @return array
 */
function get_addon_config($name)
{
    $addon = get_addon_instance($name);
    if (!$addon) {
        return [];
    }
    return $addon->getAddonConfig($name);
}

/**
 * 获取插件的单例
 * @param $name
 * @return mixed|null
 */
function get_addon_instance($name)
{
    static $_addons = [];
    if (isset($_addons[$name])) {
        return $_addons[$name];
    }
    $class = get_addon_class($name);
    if (class_exists($class)) {
        $_addons[$name] = new $class();
        return $_addons[$name];
    } else {
        return null;
    }
}

/**
 * 设置基础配置信息.
 * @param  string  $name  插件名
 * @param  array  $array  配置数据
 * @throws Exception
 * @return bool
 */
function set_addon_info($name, $array)
{
    $file  = ADDON_PATH . $name . DS . 'info.ini';
    $addon = get_addon_instance($name);
    $array = $addon->setInfo($name, $array);
    if (!isset($array['name']) || !isset($array['title']) || !isset($array['version'])) {
        throw new Exception('插件配置写入失败');
    }
    $res = [];
    foreach ($array as $key => $val) {
        if (is_array($val)) {
            $res[] = "[$key]";
            foreach ($val as $skey => $sval) {
                $res[] = "$skey = " . (is_numeric($sval) ? $sval : $sval);
            }
        } else {
            $res[] = "$key = " . (is_numeric($val) ? $val : $val);
        }
    }
    if ($handle = fopen($file, 'w')) {
        fwrite($handle, implode("\n", $res) . "\n");
        fclose($handle);
        //清空当前配置缓存
        Config::set('addoninfo' . $name, null);
    } else {
        throw new Exception('文件没有写入权限');
    }

    return true;
}

/**
 * 写入配置文件.
 * @param  string  $name  插件名
 * @param  array  $array  配置数据
 * @throws Exception
 * @return bool
 */
function set_addon_fullconfig($name, $array)
{
    $file = ADDON_PATH . $name . DS . 'config.php';
    if (!\util\File::is_really_writable($file)) {
        throw new Exception('文件没有写入权限');
    }
    if ($handle = fopen($file, 'w')) {
        fwrite($handle, "<?php\n\n" . 'return ' . var_export($array, true) . ";\n");
        fclose($handle);
    } else {
        throw new Exception('文件没有写入权限');
    }

    return true;
}
