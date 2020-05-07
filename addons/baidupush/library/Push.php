<?php

namespace addons\baidupush\library;

use addons\baidupush\library\push\Driver;

/**
 * Push操作类
 */
class Push
{
    /**
     * @var array Push的实例
     */
    public static $instance = [];

    /**
     * @var object 操作句柄
     */
    public static $handler;

    /**
     * 连接Push驱动
     * @access public
     * @param array       $options 配置数组
     * @param bool|string $name    Push连接标识 true 强制重新初始化
     * @return Driver
     */
    public static function connect(array $options = [], $name = false)
    {
        $type = !empty($options['type']) ? $options['type'] : 'zhanzhang';
        $config = get_addon_config('baidupush');
        $type = strtolower($type);
        $options = array_merge($options, $config);
        if (false === $name) {
            $name = md5(serialize($options));
        }
        if (true === $name || !isset(self::$instance[$name])) {
            $class = false === strpos($type, '\\') ?
            '\\addons\\baidupush\\library\\push\\driver\\' . ucwords($type) :
            $type;
            if (true === $name) {
                return new $class($options);
            }
            self::$instance[$name] = new $class($options);
        }
        return self::$instance[$name];
    }

    /**
     * 自动初始化Push
     * @access public
     * @param array $options 配置数组
     * @return Driver
     */
    public static function init(array $options = [])
    {
        if (is_null(self::$handler)) {
            self::$handler = self::connect($options);
        }

        return self::$handler;
    }

    /**
     * 推送实时链接
     * @access public
     * @param array $urls URL数组
     * @return bool
     */
    public static function realtime($urls)
    {
        return self::init()->realtime($urls);
    }

    /**
     * 推送历史链接
     * @access public
     * @param array $urls URL数组
     * @return bool
     */
    public static function history($urls)
    {
        return self::init()->history($urls);
    }

    /**
     * 删除链接
     * @access public
     * @param array $urls URL数组
     * @return mixed
     */
    public static function delete($urls)
    {
        return self::init()->delete($urls);
    }

}
