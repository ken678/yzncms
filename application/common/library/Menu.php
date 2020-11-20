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
