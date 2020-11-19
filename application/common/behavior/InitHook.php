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

use think\facade\Hook;

class InitHook
{
    // 行为扩展的执行入口必须是run
    public function run($params)
    {
        $hooks = (array) \think\facade\Config::get('addons.hooks', []);
        // 初始化钩子
        foreach ($hooks as $key => $values) {
            if (is_string($values)) {
                $values = explode(',', $values);
            } else {
                $values = (array) $values;
            }
            //$hooks[$key] = array_filter(array_map('get_addon_class', $values));
            $hooks[$key] = array_filter($values);
        }
        if (isset($hooks['app_init'])) {
            foreach ($hooks['app_init'] as $k => $v) {
                Hook::exec([$v, 'appInit']);
            }
        }
        Hook::import($hooks, false);
    }
}
