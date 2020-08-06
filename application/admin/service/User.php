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
// |  后台用户服务
// +----------------------------------------------------------------------
namespace app\admin\service;

use think\Db;
use think\facade\Session;
use util\Tree;

class User extends \libs\Auth
{
    //当前登录会员详细信息
    private static $userInfo = array();

    protected static $instance = null;
    const administratorRoleId = 1;

    /**
     * 获取示例
     * @param array $options 实例配置
     * @return static
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($options);
        }

        return self::$instance;
    }

    /**
     * 魔术方法
     * @param type $name
     * @return null
     */
    public function __get($name)
    {
        //从缓存中获取
        if (isset(self::$userInfo[$name])) {
            return self::$userInfo[$name];
        } else {
            $userInfo = $this->getInfo();
            if (!empty($userInfo)) {
                return $userInfo[$name];
            }
            return null;
        }
    }

    /**
     * 取出当前管理员所拥有权限的分组.
     *
     * @param bool $withself 是否包含当前所在的分组
     *
     * @return array
     */
    public function getChildrenGroupIds($withself = false)
    {
        //取出当前管理员所有的分组
        $groups = $this->getGroups();
        $groupIds = [];
        foreach ($groups as $k => $v) {
            $groupIds[] = $v['id'];
        }
        $originGroupIds = $groupIds;
        foreach ($groups as $k => $v) {
            if (in_array($v['parentid'], $originGroupIds)) {
                $groupIds = array_diff($groupIds, [$v['id']]);
                unset($groups[$k]);
            }
        }
        // 取出所有分组
        $groupList = \app\admin\model\AuthGroup::where('status', 1)->select()->toArray();
        $objList = [];
        foreach ($groups as $k => $v) {
            if ($v['rules'] === '*') {
                $objList = $groupList;
                break;
            }
            // 取出包含自己的所有子节点
            $childrenList = Tree::instance()->init($groupList)->getChildren($v['id'], true);
            $obj = Tree::instance()->init($childrenList)->getTreeArray($v['parentid']);
            $objList = array_merge($objList, Tree::instance()->getTreeList($obj));
        }
        $childrenGroupIds = [];
        foreach ($objList as $k => $v) {
            $childrenGroupIds[] = $v['id'];
        }
        if (!$withself) {
            $childrenGroupIds = array_diff($childrenGroupIds, $groupIds);
        }
        return $childrenGroupIds;
    }

    /**
     * 取出当前管理员所拥有权限的管理员.
     *
     * @param bool $withself 是否包含自身
     *
     * @return array
     */
    public function getChildrenAdminIds($withself = false)
    {
        $childrenAdminIds = [];
        if (!$this->isAdministrator()) {
            $groupIds = $this->getChildrenGroupIds(false);
            $childrenAdminIds = Db::name('Admin')->where('roleid', 'in', $groupIds)->column('id');
        } else {
            //超级管理员拥有所有人的权限
            $childrenAdminIds = Db::name('Admin')->column('id');
        }
        if ($withself) {
            if (!in_array($this->id, $childrenAdminIds)) {
                $childrenAdminIds[] = $this->id;
            }
        } else {
            $childrenAdminIds = array_diff($childrenAdminIds, [$this->id]);
        }
        return $childrenAdminIds;
    }

    public function getGroups($uid = null)
    {
        $uid = is_null($uid) ? $this->id : $uid;
        return parent::getGroups($uid);
    }

    /**
     * 获取当前登录用户资料
     * @return array
     */
    public function getInfo()
    {
        if (empty(self::$userInfo)) {
            self::$userInfo = $this->getUserInfo($this->isLogin());
        }
        return !empty(self::$userInfo) ? self::$userInfo : false;
    }

    /**
     * 检验用户是否已经登陆
     * @return boolean 失败返回false，成功返回当前登陆用户基本信息
     */
    public function isLogin()
    {
        $user = Session::get('admin_user_auth');
        if (empty($user)) {
            return 0;
        } else {
            return Session::get('admin_user_auth_sign') == data_auth_sign($user) ? (int) $user['uid'] : 0;
        }
    }

    /**
     * 检查当前用户是否超级管理员
     * @return boolean
     */
    public function isAdministrator($uid = null)
    {
        $userInfo = $this->getInfo();
        if (!empty($userInfo) && $userInfo['roleid'] == self::administratorRoleId) {
            return true;
        }
        return false;
        /*$uid = is_null($uid) ? $this->isLogin() : $uid;
    return $uid && (intval($uid) === self::administratorRoleId);*/
    }

    /**
     * 注销登录状态
     * @return boolean
     */
    public function logout()
    {
        Session::clear();
        return true;
    }

    /**
     * 获取用户信息
     * @param type $identifier 用户名或者用户ID
     * @return boolean|array
     */
    public function getUserInfo($identifier, $password = null)
    {
        if (empty($identifier)) {
            return false;
        }
        return model('admin/AdminUser')->getUserInfo($identifier, $password);
    }

    /**
     * 获取错误信息
     * @access public
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

}
