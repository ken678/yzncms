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
namespace app\admin\model;

use think\Db;
use \think\Model;

/**
 * 菜单基础模型
 */
class Menu extends Model
{
    //获取菜单列表
    public static function getList()
    {
        return Db::name('menu')->order(array('listorder', 'id' => 'DESC'))->select();
    }

    // 获取菜单
    public static function getInfo($map)
    {
        return Db::name('menu')->where($map)->find();
    }

    // 更新数据
    public static function edit($data)
    {
        return Db::name('menu')->update($data);
    }

    // 删除数据
    public static function remove($id)
    {
        return Db::name('menu')->delete($id);
    }

}
