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
// | 菜单类
// +----------------------------------------------------------------------
namespace app\common\library;

use app\admin\model\Menu as MenuModel;
use think\Exception;

class Menu
{

    /**
     * 添加插件后台管理菜单
     * @param type $info
     * @param type $adminlist
     * @return boolean
     */
    public static function addAddonMenu(array $admin_list, array $config, $parentid = 0)
    {
        if (empty($config)) {
            throw new Exception('模块配置信息为空！');
        }
        //查询出“插件后台列表”菜单ID
        $defaultMenuParentid = MenuModel::where(["app" => "admin", "controller" => "addons", "action" => "addonadmin"])->value('id') ?: 41;
        //插件具体菜单
        if (!empty($admin_list)) {
            foreach ($admin_list as $key => $menu) {
                //检查参数是否存在
                if (empty($menu['title']) || empty($menu['name'])) {
                    continue;
                }
                $route = self::menuRoute($menu['name'], 'admin');
                $data  = array_merge(array(
                    //父ID
                    "parentid"  => $parentid ?: ($menu['parentid'] ?? (int) $defaultMenuParentid),
                    'icon'      => isset($menu['icon']) ? $menu['icon'] : ($parentid == 0 ? 'icon-circle-line' : ''),
                    //状态，1是显示，0是不显示
                    "status"    => $menu['status'] ?? 0,
                    //名称
                    "title"     => $menu['title'],
                    //备注
                    "tip"       => $menu['tip'] ?? '',
                    'addon'     => $config['name'],
                    //排序
                    "listorder" => $menu['listorder'] ?? 0,
                ), $route);
                $result = MenuModel::create($data);
                //是否有子菜单
                if (!empty($menu['child'])) {
                    self::addAddonMenu($menu['child'], $config, $result['id']);
                }
            }
            //显示“插件后台列表”菜单
            MenuModel::where('id', $defaultMenuParentid)->update(['status' => 1]);
        }
        //清除缓存
        cache('Menu', null);
        return true;
    }

    /**
     * 删除对应插件菜单和权限
     * @return boolean
     */
    public static function delAddonMenu($name)
    {
        //删除对应菜单
        MenuModel::where('addon', $name)->delete();
        self::AddonMenuStatus();
        return true;
    }

    /**
     * 启用对应插件菜单和权限
     * @return boolean
     */
    public static function enableAddonMenu($name)
    {
        //删除对应菜单
        MenuModel::where('addon', $name)->update(['status' => 1]);
        self::AddonMenuStatus();
        return true;
    }

    /**
     * 禁用对应插件菜单和权限
     * @return boolean
     */
    public static function disableAddonMenu($name)
    {
        //删除对应菜单
        MenuModel::where('addon', $name)->update(['status' => 0]);
        self::AddonMenuStatus();
        return true;
    }

    private static function AddonMenuStatus()
    {
        //查询出“插件后台列表”菜单ID
        $menuId = MenuModel::where(array("app" => "Addons", "controller" => "Addons", "action" => "addonadmin"))->value('id') ?: 41;
        //检查“插件后台列表”菜单下还有没有菜单，没有就隐藏
        $count = MenuModel::where('parentid', $menuId)->count();
        if (!$count) {
            MenuModel::where('id', $menuId)->update(array('status' => 0));
        }
        return true;
    }

    /**
     * 把模块安装时，Menu.php中配置的route进行转换
     * @param type $route route内容
     * @param type $moduleNama 安装模块名称
     * @return array
     */
    private static function menuRoute($route, $moduleNama)
    {
        $route = explode('/', $route, 3);
        if (count($route) < 3) {
            array_unshift($route, $moduleNama);
        }
        $data = array(
            'app'        => $route[0],
            'controller' => $route[1],
            'action'     => $route[2],
        );
        return $data;
    }

}
