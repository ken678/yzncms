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
use think\facade\Hook;

// 初始化钩子信息
class InitHook
{

    // 行为扩展的执行入口必须是run
    public function run($params)
    {
        $data = Cache::get('Hooks');
        if (empty($data)) {
            $hooks = Db::name('Hooks')->column('id,name');
            foreach ($hooks as $key => $value) {
                $hooks_class = [];
                $modules = Db::name('HooksRule')->where(['hid' => $key, 'type' => 1])->order('listorder asc,create_time desc')->column('name');
                //模块
                if ($modules) {
                    $data = Db::name('Module')->where('status', 1)->whereIn('module', $modules)->column('module');
                    if ($data) {
                        $modules = array_intersect($modules, $data);
                        foreach ($modules as $key => $module) {
                            $hooks_class[] = "\\app\\" . $module . "\\behavior\\Hooks";
                        }
                    }

                }
                //插件
                $plugin = Db::name('HooksRule')->where(['hid' => $key, 'type' => 2])->order('listorder asc,create_time desc')->column('name');
                if ($plugin) {
                    $data = Db::name('Addons')->where('status', 1)->whereIn('name', $value)->column('name');
                    if ($data) {
                        $plugins = array_intersect($plugin, $data);
                        $hooks_class = array_merge($hooks_class, array_map('get_addon_class', $plugins));
                    }
                }
                Hook::add($value, $hooks_class);
            }
            Cache::set('Hooks', Hook::get());
        } else {
            //批量导入插件
            Hook::import($data, false);
        }
    }

}
