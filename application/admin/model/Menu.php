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

use \think\Db;
use \think\Model;

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
                    "menuid" => $id,
                    "id" => $id . $name,
                    "title" => $a['title'],
                    "icon" => $a['icon'],
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

    /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID
     * @param integer $with_self  是否包括他自己
     */
    final public function adminMenu($parentid, $with_self = false)
    {
        $parentid = (int) $parentid;
        $result = $this->where(array('parentid' => $parentid, 'status' => 1))->order('listorder ASC,id ASC')->select()->toArray();
        if (empty($result)) {
            $result = array();
        }
        if ($with_self) {
            $parentInfo = $this->where(array('id' => $parentid))->find();
            $result2[] = $parentInfo ? $parentInfo : array();
            $result = array_merge($result2, $result);
        }
        //是否超级管理员
        //if (User::getInstance()->isAdministrator()) {
        return $result;
        //}
        $array = array();
        foreach ($result as $v) {

            $rule = $v['app'] . '/' . $v['controller'] . '/' . $v['action'];
            if ($this->checkRule($rule, array('in', '1,2'), null)) {
                $array[] = $v;
            }
        }
        return $array;

    }

    /**
     * 添加后台菜单
     */
    public function add($data)
    {
        $validate = new \app\admin\validate\Menu;
        $result = $validate->scene('add')->check($data);
        if (!$result) {
            $this->error = $validate->getError();
            return false;
        }
        return $this->allowField(true)->save($data) !== false ? true : false;
    }

    /**
     * 修改后台菜单
     */
    public function edit($data)
    {
        $validate = new \app\admin\validate\Menu;
        $result = $validate->scene('edit')->check($data);
        if (!$result) {
            $this->error = $validate->getError();
            return false;
        }
        return $this->allowField(true)->isUpdate(true)->save($data) !== false ? true : false;
    }

}
