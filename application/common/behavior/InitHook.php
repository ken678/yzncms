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
namespace app\common\behavior;

//use Config;
//use Hook;
//use think\Db;

// 初始化钩子信息
class InitHook
{
    public function run($params)
    {
        /*$data = cache('Hooks');
    if (empty($data)) {
    $hooks = Db::name('Hooks')->column('name,addons');
    foreach ($hooks as $key => $value) {
    if ($value) {
    $map['status'] = 1;
    $names = explode(',', $value);
    $map['name'] = array('IN', $names);
    $data = Db::name('Addons')->where($map)->column('id,name');
    if ($data) {
    $addons_arr = array_intersect($names, $data);
    $addons[$key] = array_map('get_addon_class', $addons_arr);
    Hook::add($key, $addons[$key]);
    }
    }
    }
    cache('hooks', $addons);
    } else {
    //批量导入插件
    Hook::import($data, false);
    }*/

    }

}
