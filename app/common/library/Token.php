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
// |  Token操作类
// +----------------------------------------------------------------------
namespace app\common\library;

use app\common\library\token\Driver;
use think\facade\Config;
use think\facade\Env;
use think\facade\Log;

class Token
{
    /**
     * @var array Token的实例
     */
    public static $instance = [];

    /**
     * @var object 操作句柄
     */
    public static $handler;

    /**
     * 连接Token驱动
     * @access public
     * @param  array       $options 配置数组
     * @param  bool|string $name    Token连接标识 true 强制重新连接
     * @return Driver
     */
    public static function connect(array $options = [], $name = false)
    {
        $type = !empty($options['type']) ? $options['type'] : 'File';

        if (false === $name) {
            $name = md5(serialize($options));
        }

        if (true === $name || !isset(self::$instance[$name])) {
            $class = !str_contains($type, '\\') ?
            '\\app\\common\\library\\token\\driver\\' . ucwords($type) :
            $type;

            // 记录初始化信息
            Env::get('APP_DEBUG') && Log::record('[ TOKEN ] INIT ' . $type, 'info');

            if (true === $name) {
                return new $class($options);
            }

            self::$instance[$name] = new $class($options);
        }

        return self::$instance[$name];
    }

    /**
     * 自动初始化Token
     * @access public
     * @param  array $options 配置数组
     * @return Driver
     */
    public static function init(array $options = [])
    {
        if (is_null(self::$handler)) {
            if (empty($options) && 'complex' == Config::get('token.type')) {
                $default = Config::get('token.default');
                // 获取默认Token配置，并连接
                $options = Config::get('token.' . $default['type']) ?: $default;
            } elseif (empty($options)) {
                $options = Config::get('token');
            }

            self::$handler = self::connect($options);
        }

        return self::$handler;
    }

    /**
     * 判断Token是否可用(check别名)
     * @access public
     * @param  string $token Token标识
     * @return bool
     */
    public static function has($token, $user_id)
    {
        return self::check($token, $user_id);
    }

    /**
     * 判断Token是否可用
     * @param string $token Token标识
     * @return bool
     */
    public static function check($token, $user_id)
    {
        return self::init()->check($token, $user_id);
    }

    /**
     * 读取Token
     * @access public
     * @param  string $token   Token标识
     * @param  mixed  $default 默认值
     * @return mixed
     */
    public static function get($token, $default = false)
    {
        return self::init()->get($token) ?: $default;
    }

    /**
     * 写入Token
     * @access public
     * @param  string   $token   Token标识
     * @param  mixed    $user_id 存储数据
     * @param  int|null $expire  有效时间 0为永久
     * @return boolean
     */
    public static function set($token, $user_id, $expire = null)
    {
        return self::init()->set($token, $user_id, $expire);
    }

    /**
     * 删除Token(delete别名)
     * @access public
     * @param  string $token Token标识
     * @return boolean
     */
    public static function rm($token)
    {
        return self::delete($token);
    }

    /**
     * 删除Token
     * @param string $token 标签名
     * @return bool
     */
    public static function delete($token)
    {
        return self::init()->delete($token);
    }

    /**
     * 清除Token
     * @access public
     * @param  int user_id 用户编号
     * @return boolean
     */
    public static function clear($user_id = null)
    {
        return self::init()->clear($user_id);
    }

}
