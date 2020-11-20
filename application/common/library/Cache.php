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
// | 缓存类
// +----------------------------------------------------------------------
namespace app\common\library;

use app\common\model\Cache as CacheModel;
use think\Exception;

class Cache
{
    /**
     * 安装模块是，注册缓存
     * @param array $cache 缓存配置
     * @param array $config 模块配置
     * @return boolean
     */
    public static function installModuleCache(array $cache, array $config)
    {
        if (empty($cache) || empty($config)) {
            throw new Exception('参数不完整！');
        }
        $module = $config['module'];
        foreach ($cache as $key => $rs) {
            $add = array(
                'key'    => $key,
                'name'   => $rs['name'],
                'module' => isset($rs['module']) ? $rs['module'] : $module,
                'model'  => $rs['model'],
                'action' => $rs['action'],
                //'param' => isset($rs['param']) ? $rs['param'] : '',
                'system' => 0,
            );
            CacheModel::create($add);
        }
        return true;
    }

    /**
     * 删除指定模块下的全部缓存队列
     * @param type $module 模块名称
     * @return boolean
     */
    public static function deleteCacheModule($module)
    {
        CacheModel::destroy(['module' => $module, 'system' => 0]);
        return true;
    }
}
