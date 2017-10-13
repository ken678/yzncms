<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\common\model;

use think\Loader;
use \think\Db;
use \think\Model;

/**
 * 缓存模型
 */
class Cache extends Model
{
    /**
     * 删除指定模块下的全部缓存队列
     * @param type $module 模块名称
     * @return boolean
     */
    public function deleteCacheModule($module)
    {
        if (empty($module)) {
            $this->error = '请指定模块！';
            return false;
        }
        if (self::destroy(['module' => $module, 'system' => 0]) !== false) {
            return true;
        } else {
            $this->error = '删除失败！';
            return false;
        }
    }

    /**
     * 获取需要更新缓存列队
     * @param type $isup 是否强制获取最新
     * @return type
     */
    public function getCacheList($isup = false)
    {
        $cache = Cache('cache_list');
        if (empty($cache) || $isup) {
            //取得现在全部需要更新缓存的队列
            $cache = Db::name('Cache')->order(array('id' => 'ASC'))->select();
            Cache('cache_list', $cache, 600);
        }
        return $cache;
    }

    /**
     * 执行更新缓存
     * @param array $config 缓存配置
     * @return boolean
     */
    public function runUpdate(array $config)
    {
        if (empty($config)) {
            $this->error = '没有可需要更新的缓存信息！';
            return false;
        }
        $mo = '';
        if (empty($config['module'])) {
            $mo = "Common/{$config['model']}";
        } else {
            $mo = "{$config['module']}/{$config['model']}";
        }
        $model = Loader::model($mo);
        if ($config['action']) {
            $action = $config['action'];
            $model->$action();
            return true;
        }
        return false;
    }

    /**
     * 安装模块是，注册缓存
     * @param array $cache 缓存配置
     * @param array $config 模块配置
     * @return boolean
     */
    public function installModuleCache(array $cache, array $config)
    {
        if (empty($cache) || empty($config)) {
            $this->error = '参数不完整！';
            return false;
        }
        $module = $config['module'];
        $data = array();
        foreach ($cache as $key => $rs) {
            $add = array(
                'key' => $key,
                'name' => $rs['name'],
                'module' => $rs['module'] ?: $module,
                'model' => $rs['model'],
                'action' => $rs['action'],
                'param' => $rs['param'] ?: '',
                'system' => 0,
            );
            /*if (!$this->create($add)) {
            return false;
            }*/
            $data[] = $add;
        }
        if (!empty($data)) {
            return $this->allowField(true)->saveAll($data) !== false ? true : false;
        }
        return true;
    }

}
