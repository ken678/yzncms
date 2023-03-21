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
// | Push操作类
// +----------------------------------------------------------------------
namespace addons\weburlpush\library;

use think\Manager;

class Push extends Manager
{
    protected $namespace = '\\addons\\weburlpush\\library\\push\\driver\\';

    /**
     * 获取一个渠道
     * @param string $name
     * @return Channel
     */
    public function channel($name)
    {
        return $this->driver($name);
    }

    protected function resolveConfig(string $name)
    {
        return $this->getChannelConfig($name);
    }

    public function getChannelConfig(string $channel)
    {
        if ($config = get_addon_config('weburlpush')) {
            return $config[$channel] ?? [];
        }
        throw new InvalidArgumentException("Channel [$channel] not found.");
    }

    protected function resolveParams($name): array
    {
        return array_merge([$name], parent::resolveParams($name));
    }

    /**
     * 默认驱动
     * @return string|null
     */
    public function getDefaultDriver()
    {
        return null;
    }
}
