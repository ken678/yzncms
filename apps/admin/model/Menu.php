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
use think\Model;

/**
 * 菜单模型
 */
class Menu extends Model
{
    protected $resultSetType = 'collection';

     /**
     * 获取菜单 头部菜单导航
     */
    final public function getMenu()
    {
        $menuid = input('menuid/d',0);
        $menuid = $menuid ? $menuid : cookie("menuid");

        $array = $this->adminMenu($menuid,1);
        $numbers = count($array);
        if($numbers==1) return '';
        foreach ($array as $k => $v) {
            $array[$k]['url'] = $v['app'].'/'.$v['controller'].'/'.$v['action'];
            $array[$k]['parameter'] = "menuid={$menuid}&{$array[$k]['parameter']}";
        }
        return $array;
    }

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
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID
     * @param integer $with_self  是否包括他自己
     */
    final public function adminMenu($parentid, $with_self = false)
    {
        $parentid = (int) $parentid;
        $result = $this->where(array('pid' => $parentid, 'status' => 1))->order('listorder ASC,id ASC')->select()->toArray();
        if (empty($result)) {
            $result = array();
        }
        if ($with_self) {
            $parentInfo = $this->where(array('id' => $parentid))->find()->toArray();
            $result2[] = $parentInfo ? $parentInfo : array();
            $result = array_merge($result2, $result);
        }
        //是否超级管理员
        if (is_administrator()) {
            return $result;
        }
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
            $ret = NULL;
            foreach ($data as $a) {
                $id = $a['id'];
                $name = $a['app'];
                $controller = $a['controller'];
                $action = $a['action'];
                //附带参数
                $fu = "";
                if ($a['parameter']) {
                    $fu = "?" . $a['parameter'];
                }
                $array = array(
                    "id" => $id . $name,
                    "title" => $a['title'],
                    "parent" => $parent,
                    "url" => url("{$name}/{$controller}/{$action}{$fu}", array("menuid" => $id)),
                );
                $ret[$id . $name] = $array;
                $child = $this->getTree($a['id'], $id, $Level);
                //由于后台管理界面只支持三层，超出的不层级的不显示
                if ($child && $Level <= 3) {
                    $ret[$id . $name]['items'] = $child;
                }
            }
        }
        return $ret;
    }

















}