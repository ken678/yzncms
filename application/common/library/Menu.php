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

use app\admin\model\AuthRule as AuthRuleModel;
use util\Tree;

class Menu
{
    /**
     * 创建菜单
     * @param array $menu
     * @param mixed $parent 父类的name或pid
     */
    public static function create($menu = [], $parent = 0)
    {
        $old = [];
        self::menuUpdate($menu, $old, $parent);
    }

    /**
     * 删除菜单
     * @param string $name 规则name
     * @return boolean
     */
    public static function delete($name)
    {
        $ids = self::getAuthRuleIdsByName($name);
        if (!$ids) {
            return false;
        }
        AuthRuleModel::destroy($ids);
        return true;
    }

    /**
     * 启用菜单
     * @param string $name
     * @return boolean
     */
    public static function enable($name)
    {
        $ids = self::getAuthRuleIdsByName($name);
        if (!$ids) {
            return false;
        }
        AuthRuleModel::where('id', 'in', $ids)->update(['status' => 1]);
        return true;
    }

    /**
     * 禁用菜单
     * @param string $name
     * @return boolean
     */
    public static function disable($name)
    {
        $ids = self::getAuthRuleIdsByName($name);
        if (!$ids) {
            return false;
        }
        AuthRuleModel::where('id', 'in', $ids)->update(['status' => 0]);
        return true;
    }

    /**
     * 菜单升级
     * @param array $newMenu
     * @param array $oldMenu
     * @param int   $parent
     * @throws Exception
     */
    private static function menuUpdate($newMenu, &$oldMenu, $parent = 0)
    {
        if (!is_numeric($parent)) {
            $parentRule = AuthRuleModel::getByName($parent);
            $pid        = $parentRule ? $parentRule['id'] : 0;
        } else {
            $pid = $parent;
        }
        $allow = array_flip(['name', 'title', 'url', 'icon', 'condition', 'remark', 'ismenu', 'menutype', 'extend', 'listorder', 'status']);
        foreach ($newMenu as $k => $v) {
            $hasChild         = isset($v['sublist']) && $v['sublist'];
            $data             = array_intersect_key($v, $allow);
            $data['ismenu']   = $data['ismenu'] ?? ($hasChild ? 1 : 0);
            $data['icon']     = $data['icon'] ?? ($hasChild ? 'iconfont icon-other' : 'iconfont icon-circle-line');
            $data['parentid'] = $pid;
            $data['status']   = $data['status'] ?? 'normal';
            if (!isset($oldMenu[$data['name']])) {
                $menu = AuthRuleModel::create($data);
            } else {
                $menu = $oldMenu[$data['name']];
                //更新旧菜单
                AuthRuleModel::update($data, ['id' => $menu['id']]);
                //$oldMenu[$data['name']]['keep'] = true;
            }
            if ($hasChild) {
                self::menuUpdate($v['sublist'], $oldMenu, $menu['id']);
            }
        }
    }

    /**
     * 根据名称获取规则IDS
     * @param string $name
     * @return array
     */
    public static function getAuthRuleIdsByName($name)
    {
        $ids  = [];
        $menu = AuthRuleModel::getByName($name);
        if ($menu) {
            // 必须将结果集转换为数组
            $ruleList = AuthRuleModel::order('listorder', 'desc')->field('id,parentid,name')->select()->toArray();
            // 构造菜单数据
            $ids = Tree::instance()->init($ruleList)->getChildrenIds($menu['id'], true);
        }
        return $ids;
    }
}
