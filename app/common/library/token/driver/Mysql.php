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
// |  mysql驱动类
// +----------------------------------------------------------------------
namespace app\common\library\token\driver;

use app\common\library\token\Driver;
use think\facade\Db;

class Mysql extends Driver
{

    /**
     * 默认配置
     * @var array
     */
    protected $options = [
        'table'      => 'user_token',
        'expire'     => 2592000,
        'connection' => [],
    ];

    /**
     * 构造函数
     * @param array $options 参数
     * @throws \think\db\exception\DbException
     */
    public function __construct(array $options = [])
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        if ($this->options['connection']) {
            $this->handler = Db::connect($this->options['connection'])->name($this->options['table']);
        } else {
            $this->handler = Db::name($this->options['table']);
        }
        $time      = time();
        $tokentime = cache('tokentime');
        if (!$tokentime || $tokentime < $time - 86400) {
            cache('tokentime', $time);
            $this->handler->where('expire_time', '<', $time)->where('expire_time', '>', 0)->delete();
        }
    }

    /**
     * 存储Token
     * @param string $token Token
     * @param int $user_id 会员ID
     * @param int $expire 过期时长,0表示无限,单位秒
     * @return true
     */
    public function set(string $token, int $user_id, $expire = null): bool
    {
        $expiretime = !is_null($expire) && $expire !== 0 ? time() + $expire : 0;
        $token      = $this->getEncryptedToken($token);
        $this->handler->removeOption('where');
        $this->handler->insert(['token' => $token, 'user_id' => $user_id, 'create_time' => time(), 'expire_time' => $expiretime]);
        return true;
    }

    /**
     * 获取Token内的信息
     * @param string $token
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get(string $token): mixed
    {
        $this->handler->removeOption('where');
        $data = $this->handler->where('token', $this->getEncryptedToken($token))->find();
        if ($data) {
            if (!$data['expire_time'] || $data['expire_time'] > time()) {
                //返回未加密的token给客户端使用
                $data['token'] = $token;
                //返回剩余有效时间
                $data['expires_in'] = $this->getExpiredIn($data['expire_time']);
                return $data;
            } else {
                self::delete($token);
            }
        }
        return [];
    }

    /**
     * 判断Token是否可用
     * @param string $token Token
     * @param int $user_id 会员ID
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function check(string $token, int $user_id): bool
    {
        $data = $this->get($token);
        return $data && $data['user_id'] == $user_id;
    }

    /**
     * 删除Token
     * @param string $token
     * @return true
     * @throws \think\db\exception\DbException
     */
    public function delete(string $token): bool
    {
        $this->handler->removeOption('where');
        $this->handler->where('token', $this->getEncryptedToken($token))->delete();
        return true;
    }

    /**
     * 删除指定用户的所有Token
     * @param int $user_id
     * @return true
     * @throws \think\db\exception\DbException
     */
    public function clear(int $user_id): bool
    {
        $this->handler->removeOption('where');
        $this->handler->where('user_id', $user_id)->delete();
        return true;
    }

}
