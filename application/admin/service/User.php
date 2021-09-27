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

use app\admin\model\AdminUser;
use think\Db;
use think\facade\Config;
use think\facade\Cookie;
use think\facade\Session;
use util\Random;
use util\Tree;

class User extends \libs\Auth
{
    //当前登录会员详细信息
    private static $userInfo   = array();
    protected $error           = '';
    protected static $instance = null;
    const ADMINISTRATORROLEID  = 1;

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

    public function __get($name)
    {
        return Session::get('admin.' . $name);
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
        $groups   = $this->getGroups();
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
        $objList   = [];
        foreach ($groups as $k => $v) {
            if ($v['rules'] === '*') {
                $objList = $groupList;
                break;
            }
            // 取出包含自己的所有子节点
            $childrenList = Tree::instance()->init($groupList)->getChildren($v['id'], true);
            $obj          = Tree::instance()->init($childrenList)->getTreeArray($v['parentid']);
            $objList      = array_merge($objList, Tree::instance()->getTreeList($obj, 'title'));
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
            $groupIds         = $this->getChildrenGroupIds(false);
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

    public function getRuleIds($uid = null)
    {
        $uid = is_null($uid) ? $this->id : $uid;
        return parent::getRuleIds($uid);
    }

    /**
     * 用户登录
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool|mixed
     */
    public function login($username = '', $password = '', $keeptime = 0)
    {
        $username = trim($username);
        $password = trim($password);
        $admin    = AdminUser::get(['username' => $username]);
        if (!$admin) {
            $this->setError('用户名不正确');
            return false;
        }
        if ($admin['status'] !== 1) {
            $this->setError('管理员已经被禁止登录');
            return false;
        }
        if ($admin->password != encrypt_password($password, $admin->encrypt)) {
            //$admin->loginfailure++;
            $admin->save();
            $this->setError('密码不正确');
            return false;
        }
        //$admin->loginfailure = 0;
        $admin->last_login_time = time();
        $admin->last_login_ip   = request()->ip();
        $admin->token           = Random::uuid();
        $admin->save();
        Session::set("admin", $admin->toArray());
        $auth = [
            'uid'             => $admin->id,
            'username'        => $admin->username,
            'last_login_time' => $admin->last_login_time,
        ];
        $this->keeplogin($keeptime);
        return true;
    }

    /**
     * 刷新保持登录的Cookie
     *
     * @param int $keeptime
     * @return  boolean
     */
    protected function keeplogin($keeptime = 0)
    {
        if ($keeptime) {
            $expiretime = time() + $keeptime;
            $key        = md5(md5($this->id) . md5($keeptime) . md5($expiretime) . $this->token);
            $data       = [$this->id, $keeptime, $expiretime, $key];
            Cookie::set('keeplogin', implode('|', $data), 86400 * 30);
            return true;
        }
        return false;
    }

    /**
     * 自动登录
     * @return boolean
     */
    public function autologin()
    {
        $keeplogin = Cookie::get('keeplogin');
        if (!$keeplogin) {
            return false;
        }
        list($id, $keeptime, $expiretime, $key) = explode('|', $keeplogin);
        if ($id && $keeptime && $expiretime && $key && $expiretime > time()) {
            $admin = AdminUser::get($id);
            if (!$admin || !$admin->token) {
                return false;
            }
            //token有变更
            if ($key != md5(md5($id) . md5($keeptime) . md5($expiretime) . $admin->token)) {
                return false;
            }
            $ip = request()->ip();
            //IP有变动
            if ($admin->last_login_ip != $ip) {
                return false;
            }
            Session::set("admin", $admin->toArray());
            //刷新自动登录的时效
            $this->keeplogin($keeptime);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检验用户是否已经登陆
     * @return boolean 失败返回false，成功返回当前登陆用户基本信息
     */
    public function isLogin()
    {
        $admin = Session::get('admin');
        if (!$admin) {
            return 0;
        }
        //判断是否同一时间同一账号只能在一个地方登录
        if (Config::get('login_unique')) {
            $my = AdminUser::get($admin['id']);
            if (!$my || $my['token'] != $admin['token']) {
                $this->logout();
                return false;
            }
        }
        //判断管理员IP是否变动
        if (Config::get('loginip_check')) {
            if (!isset($admin['last_login_ip']) || $admin['last_login_ip'] != request()->ip()) {
                $this->logout();
                return false;
            }
        }
        $this->logined = true;
        return true;
    }

    /**
     * 检查当前用户是否超级管理员
     * @return boolean
     */
    public function isAdministrator($uid = null)
    {
        $userInfo = Session::get('admin');
        if (!empty($userInfo) && $userInfo['roleid'] == self::ADMINISTRATORROLEID) {
            return true;
        }
        return false;
    }

    /**
     * 检测当前控制器和方法是否匹配传递的数组
     *
     * @param array $arr 需要验证权限的数组
     * @return bool
     */
    public function match($arr = [], $path = "")
    {
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return false;
        }

        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower($path), $arr) || in_array('*', $arr)) {
            return true;
        }

        // 没找到匹配
        return false;
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        $admin = AdminUser::get(intval($this->id));
        if ($admin) {
            $admin->token = '';
            $admin->save();
        }
        Session::delete("admin");
        Cookie::delete("keeplogin");
        return true;
    }

    /**
     * 获取错误信息
     * @access public
     * @return mixed
     */
    public function getError()
    {
        return $this->error ? $this->error : '';
    }

    /**
     * 设置错误信息
     *
     * @param string $error 错误信息
     * @return Auth
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

}
