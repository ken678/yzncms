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
    public static function addAddonMenu($info, $admin_list = null)
    {
        if (empty($info)) {
            throw new Exception('没有数据！');
        }
        //查询出“插件后台列表”菜单ID
        $menuId = MenuModel::where(array("app" => "addons", "controller" => "addons", "action" => "addonadmin"))->value('id') ?: 41;
        $data   = array(
            //父ID
            "parentid"   => $menuId,
            'icon'       => isset($info['icon']) ? $info['icon'] : 'icon-circle-line',
            //模块目录名称，也是项目名称
            "app"        => "addons",
            //插件名称
            "controller" => $info['name'],
            //方法名称
            "action"     => "index",
            //附加参数 例如：a=12&id=777
            "parameter"  => "isadmin=1",
            //状态，1是显示，0是不显示
            "status"     => 1,
            //名称
            "title"      => $info['title'],
            //备注
            "tip"        => $info['title'] . "插件管理后台！",
            //排序
            "listorder"  => 0,
        );
        //添加插件后台
        $parentid = MenuModel::create($data);
        //显示“插件后台列表”菜单
        MenuModel::where('id', $menuId)->update(array('status' => 1));
        //插件具体菜单
        if (!empty($admin_list)) {
            foreach ($admin_list as $key => $menu) {
                //检查参数是否存在
                if (empty($menu['title']) || empty($menu['action'])) {
                    continue;
                }
                //如果是index，跳过，因为已经有了。。。
                if ($menu['action'] == 'index') {
                    continue;
                }
                $data = array(
                    //父ID
                    "parentid"   => $parentid->getAttr('parentid'),
                    //模块目录名称，也是项目名称
                    "app"        => "addons",
                    //文件名称，比如LinksAction.class.php就填写 Links
                    "controller" => $info['name'],
                    //方法名称
                    "action"     => $menu['action'],
                    //附加参数 例如：a=12&id=777
                    "parameter"  => 'isadmin=1',
                    //状态，1是显示，0是不显示
                    "status"     => (int) $menu['status'],
                    //名称
                    "title"      => $menu['title'],
                    //备注
                    "tip"        => $menu['tip'] ?: '',
                    //排序
                    "listorder"  => (int) $menu['listorder'],
                );
                MenuModel::create($data);
            }
        }
        return true;
    }

    /**
     * 模块安装时进行菜单注册
     * @param array $data 菜单数据
     * @param array $config 模块配置
     * @param type $parentid 父菜单ID
     * @return boolean
     */
    public static function installModuleMenu(array $data, array $config, $parentid = 0)
    {
        if (empty($data) || !is_array($data)) {
            throw new Exception('没有数据！');
        }
        if (empty($config)) {
            throw new Exception('模块配置信息为空！');
        }
        //默认安装时父级ID
        $defaultMenuParentid = MenuModel::where(array('app' => 'admin', 'controller' => 'module', 'action' => 'list'))->value('id') ?: 45;
        //安装模块名称
        $moduleNama = $config['module'];
        foreach ($data as $rs) {
            if (empty($rs['route'])) {
                throw new Exception('菜单信息配置有误，route 不能为空！');
            }
            $route   = self::menuRoute($rs['route'], $moduleNama);
            $pid     = $parentid ?: ((is_null($rs['parentid']) || !isset($rs['parentid'])) ? (int) $defaultMenuParentid : $rs['parentid']);
            $newData = array_merge(array(
                'title'     => $rs['name'],
                'icon'      => isset($rs['icon']) ? $rs['icon'] : '',
                'parentid'  => $pid,
                'status'    => isset($rs['status']) ? $rs['status'] : 0,
                'tip'       => isset($rs['remark']) ? $rs['remark'] : '',
                'listorder' => isset($rs['listorder']) ? $rs['listorder'] : 0,
            ), $route);

            $result = MenuModel::create($newData);
            //是否有子菜单
            if (!empty($rs['child'])) {
                self::installModuleMenu($rs['child'], $config, $result['id']);
            }
        }
        //清除缓存
        cache('Menu', null);
        return true;
    }

    /**
     * 删除对应插件菜单和权限
     * @param type $info 插件信息
     * @return boolean
     */
    public static function delAddonMenu($info)
    {
        if (empty($info)) {
            throw new Exception('没有数据！');
        }
        //查询出“插件后台列表”菜单ID
        $menuId = MenuModel::where(array("app" => "Addons", "controller" => "Addons", "action" => "addonadmin"))->value('id') ?: 41;
        //删除对应菜单
        MenuModel::where(array('app' => 'Addons', 'controller' => $info['name']))->delete();
        //删除权限
        //M("Access")->where(array("app" => "Addons", 'controller' => $info['name']))->delete();
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
