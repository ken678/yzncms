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

namespace app\common\library\token\driver;

use app\common\library\token\Driver;

/**
 * Redis缓存驱动，适合单机部署、有前端代理实现高可用的场景，性能最好
 * 有需要在业务层实现读写分离、或者使用RedisCluster的需求，请使用Redisd驱动
 *
 * 要求安装phpredis扩展：https://github.com/nicolasff/phpredis
 * @author    尘缘 <130775@qq.com>
 */
class Redis extends Driver
{

    protected $options = [
        'host'        => '127.0.0.1',
        'port'        => 6379,
        'password'    => '',
        'select'      => 0,
        'timeout'     => 0,
        'expire'      => 0,
        'persistent'  => false,
        'userprefix'  => 'up:',
        'tokenprefix' => 'tp:',
    ];

    /**
     * 构造函数
     * @param array $options 缓存参数
     * @throws \RedisException
     */
    public function __construct(array $options = [])
    {
        if (!extension_loaded('redis')) {
            throw new \BadFunctionCallException('not support: redis');
        }
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        $this->handler = new \Redis;
        if ($this->options['persistent']) {
            $this->handler->pconnect($this->options['host'], $this->options['port'], $this->options['timeout'], 'persistent_id_' . $this->options['select']);
        } else {
            $this->handler->connect($this->options['host'], $this->options['port'], $this->options['timeout']);
        }

        if ('' != $this->options['password']) {
            $this->handler->auth($this->options['password']);
        }

        if (0 != $this->options['select']) {
            $this->handler->select($this->options['select']);
        }
    }

    /**
     * 获取加密后的Token
     * @param string $token Token标识
     * @return string
     */
    protected function getEncryptedToken(string $token): string
    {
        $config = \think\facade\Config::get('token');
        return $this->options['tokenprefix'] . hash_hmac($config['hashalgo'], $token, $config['key']);
    }

    /**
     * 获取会员的key
     * @param int $user_id
     * @return string
     */
    protected function getUserKey(int $user_id): string
    {
        return $this->options['userprefix'] . $user_id;
    }

    /**
     * 存储Token
     * @param string $token Token
     * @param int $user_id 会员ID
     * @param int $expire 过期时长,0表示无限,单位秒
     * @return bool|\Redis
     * @throws \RedisException
     */
    public function set(string $token, int $user_id, $expire = 0)
    {
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        if ($expire instanceof \DateTime) {
            $expire = $expire->getTimestamp() - time();
        }
        $key = $this->getEncryptedToken($token);
        if ($expire) {
            $result = $this->handler->setex($key, $expire, $user_id);
        } else {
            $result = $this->handler->set($key, $user_id);
        }
        //写入会员关联的token
        $this->handler->sAdd($this->getUserKey($user_id), $key);
        return $result;
    }

    /**
     * 获取Token内的信息
     * @param string $token $token
     * @return array
     * @throws \RedisException
     */
    public function get(string $token): array
    {
        $key   = $this->getEncryptedToken($token);
        $value = $this->handler->get($key);
        if (is_null($value) || false === $value) {
            return [];
        }
        //获取有效期
        $expire     = $this->handler->ttl($key);
        $expire     = $expire < 0 ? 365 * 86400 : $expire;
        $expiretime = time() + $expire;
        //解决使用redis方式储存token时api接口Token刷新与检测因expires_in拼写错误报错的BUG
        return ['token' => $token, 'user_id' => $value, 'expiretime' => $expiretime, 'expires_in' => $expire];
    }

    /**
     * 判断Token是否可用
     * @param string $token Token
     * @param int $user_id 会员ID
     * @return bool
     * @throws \RedisException
     */
    public function check(string $token, int $user_id): bool
    {
        $data = self::get($token);
        return $data && $data['user_id'] == $user_id;
    }

    /**
     * 删除Token
     * @param string $token
     * @return true
     * @throws \RedisException
     */
    public function delete(string $token): bool
    {
        $data = $this->get($token);
        if ($data) {
            $key     = $this->getEncryptedToken($token);
            $user_id = $data['user_id'];
            $this->handler->del($key);
            $this->handler->sRem($this->getUserKey($user_id), $key);
        }
        return true;

    }

    /**
     * 删除指定用户的所有Token
     * @param int $user_id
     * @return true
     * @throws \RedisException
     */
    public function clear(int $user_id): bool
    {
        $keys = $this->handler->sMembers($this->getUserKey($user_id));
        $this->handler->del($this->getUserKey($user_id));
        $this->handler->del($keys);
        return true;
    }

}
