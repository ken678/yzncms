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
// | 后台菜单模型
// +----------------------------------------------------------------------
namespace app\admin\model;

use app\admin\model\AuthRule;
use app\admin\service\User;
use think\Model;

class Menu extends Model
{

    /**
     * 获取菜单
     * @return type
     */
    final public function getMenuList()
    {
        $data = $this->getTree(0);
        return $data;
    }

    /**
     * 取得树形结构的菜单
     * @param type $myid
     * @param type $parent
     * @param type $Level
     * @return type
     */
    final public function getTree($myid, $parent = "", $Level = 1)
    {
        $data = $this->adminMenu($myid);
        $Level++;
        if (is_array($data)) {
            $ret = null;
            foreach ($data as $a) {
                $id         = $a['id'];
                $name       = $a['app'];
                $controller = $a['controller'];
                $action     = $a['action'];
                //附带参数
                $fu = "";
                if ($a['parameter']) {
                    $fu = "?" . $a['parameter'];
                }
                $array = array(
                    "menuid" => $id,
                    "id"     => $id . $name,
                    "title"  => $a['title'],
                    "icon"   => $a['icon'],
                    "parent" => $parent,
                    "url"    => url("{$name}/{$controller}/{$action}{$fu}", array("menuid" => $id)),
                );
                $ret[$id . $name] = $array;
                $child            = $this->getTree($a['id'], $id, $Level);
                //由于后台管理界面只支持三层，超出的不层级的不显示
                if ($child && $Level <= 3) {
                    $ret[$id . $name]['items'] = $child;
                }
            }
        }
        return $ret;
    }

    /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID
     * @param integer $with_self  是否包括他自己
     */
    final public function adminMenu($parentid, $with_self = false)
    {
        $parentid = (int) $parentid;
        $result   = $this->where(array('parentid' => $parentid, 'status' => 1))->order('listorder ASC,id ASC')->cache(60)->select()->toArray();
        if (empty($result)) {
            $result = array();
        }
        if ($with_self) {
            $parentInfo = $this->where(array('id' => $parentid))->find();
            $result2[]  = $parentInfo ? $parentInfo : array();
            $result     = array_merge($result2, $result);
        }
        //是否超级管理员
        if (User::instance()->isAdministrator()) {
            return $result;
        }
        $array = array();
        foreach ($result as $v) {
            $rule = $v['app'] . '/' . $v['controller'] . '/' . $v['action'];
            if ($this->checkRule($rule, [1, 2], null)) {
                $array[] = $v;
            }
        }
        return $array;
    }

    /**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     */
    final protected function checkRule($rule, $type = AuthRule::RULE_URL, $mode = 'url')
    {
        static $Auth = null;
        if (!$Auth) {
            $Auth = new \libs\Auth();
        }
        if (!$Auth->check($rule, User::instance()->id, $type, $mode)) {
            return false;
        }
        return true;
    }

    /**
     * 返回后台节点数据
     * @param boolean $tree    是否返回多维数组结构(生成菜单时用到),为false返回一维数组(生成权限节点时用到)
     * @retrun array
     *
     * 注意,返回的主菜单节点数组中有'controller'元素,以供区分子节点和主节点
     *
     */
    final public function returnNodes($tree = true)
    {
        static $tree_nodes = array();
        if ($tree && !empty($tree_nodes[(int) $tree])) {
            return $tree_nodes[$tree];
        }
        if ((int) $tree) {
            $list = $this->order('listorder ASC,id ASC')->select()->toArray();
            foreach ($list as $key => $value) {
                $list[$key]['url'] = $value['app'] . '/' . $value['controller'] . '/' . $value['action'];
            }
            $nodes = list_to_tree($list, $pk = 'id', $pid = 'parentid', $child = 'operator', $root = 0);
            foreach ($nodes as $key => $value) {
                if (!empty($value['operator'])) {
                    $nodes[$key]['child'] = $value['operator'];
                    unset($nodes[$key]['operator']);
                }
            }
        } else {
            $nodes = $this->order('listorder ASC,id ASC')->select()->toArray();
            foreach ($nodes as $key => $value) {
                $nodes[$key]['url'] = $value['app'] . '/' . $value['controller'] . '/' . $value['action'];
            }
        }
        $tree_nodes[(int) $tree] = $nodes;
        return $nodes;
    }

    /**
     * 更新缓存
     * @param type $data
     * @return type
     */
    public function menu_cache()
    {
        $data = $this->select()->toArray();
        if (empty($data)) {
            return false;
        }
        $cache = array();
        foreach ($data as $rs) {
            $cache[$rs['id']] = $rs;
        }
        cache('Menu', $cache);
        return $cache;
    }

}
