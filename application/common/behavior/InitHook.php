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
use think\facade\Hook;

// 初始化钩子信息
class InitHook
{

    // 行为扩展的执行入口必须是run
    public function run($params)
    {
        $data = cache('Hooks');
        if (empty($data)) {
            $hooks = Db::name('Hooks')->column('name,addons');
            foreach ($hooks as $key => $value) {
                if ($value) {
                    $data = Db::name('Addons')->whereIn('name', $value)->where('status', 1)->column('id,name');
                    if ($data) {
                        $addons[$key] = array_map('get_addon_class', $data);
                        Hook::add($key, $addons[$key]);
                        cache('hooks', $addons);
                    }
                }
            }
        } else {
            //批量导入插件
            Hook::import($data, false);
        }

    }

}
