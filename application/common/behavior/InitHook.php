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
// | 注册钩子
// +----------------------------------------------------------------------
namespace app\common\behavior;

use think\Db;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Hook;

// 初始化钩子信息
class InitHook
{

    // 行为扩展的执行入口必须是run
    public function run($params)
    {
        $hooks = Cache::get('Hooks');
        if (empty($hooks)) {
            $hooks = [];
            //所有模块和插件钩子
            $res = Db::name('Hooks')->where('status', 1)->column('name,addons,modules');
            foreach ($res as $key => $value) {
                $hooks_class = [];
                //模块
                if ($value['modules']) {
                    $data = Db::name('Module')->whereIn('module', $value['modules'])->where('status', 1)->column('module');
                    if ($data) {
                        foreach ($data as $key => $module) {
                            $hooks_class[] = "\\app\\" . $module . "\\behavior\\Hooks";
                        }
                    }
                }
                //插件
                $hooks_class = Config::get('app_debug') ? [] : Cache::get('hooks', []);
                if (empty($hooks_class)) {
                    $hooks_class = (array) Config::get('addons.hooks', []);
                    // 初始化钩子
                    foreach ($hooks_class as $key => $values) {
                        if (is_string($values)) {
                            $values = explode(',', $values);
                        } else {
                            $values = (array) $values;
                        }
                        $hooks_class[$key] = array_filter(array_map('get_addon_class', $values));
                    }
                    Cache::set('hooks', $hooks_class);
                }
                if (!empty($hooks_class)) {
                    $hooks[$value['name']] = $hooks_class;
                }
            }
            Cache::set('Hooks', $hooks);
        }
        if (isset($hooks['app_init'])) {
            foreach ($hooks['app_init'] as $k => $v) {
                Hook::exec([$v, 'appInit']);
            }
        }
        Hook::import($hooks, false);
    }

}
