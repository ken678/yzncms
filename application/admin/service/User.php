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
use think\facade\Request;
use think\facade\Session;
use util\Random;
use util\Tree;

class User extends \libs\Auth
{
    //当前登录会员详细信息
    protected $error   = '';
    protected $logined = false; //登录状态

    public function __construct()
    {
        parent::__construct();
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

    public function check($name, $uid = '', $type = 1, $mode = 'url', $relation = 'or')
    {
        $uid = $uid ? $uid : $this->id;
        return parent::check($name, $uid, $type, $mode, $relation);
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

    public function getAuthList($uid = null)
    {
        $uid = is_null($uid) ? $this->id : $uid;
        return parent::getAuthList($uid);
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
        Session::set("admin.safecode", $this->getEncryptSafecode($admin));
        $this->keeplogin($admin, $keeptime);
        return true;
    }

    /**
     * 刷新保持登录的Cookie
     *
     * @param int $keeptime
     * @return  boolean
     */
    protected function keeplogin($admin, $keeptime = 0)
    {
        if ($keeptime) {
            $expiretime = time() + $keeptime;
            $key        = $this->getKeeploginKey($admin, $keeptime, $expiretime);
            Cookie::set('keeplogin', implode('|', [$admin['id'], $keeptime, $expiretime, $key]), $keeptime);
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
            if ($key != $this->getKeeploginKey($admin, $keeptime, $expiretime)) {
                return false;
            }
            $ip = request()->ip();
            //IP有变动
            if ($admin->last_login_ip != $ip) {
                return false;
            }
            Session::set("admin", $admin->toArray());
            Session::set("admin.safecode", $this->getEncryptSafecode($admin));
            //刷新自动登录的时效
            $this->keeplogin($admin, $keeptime);
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
        if ($this->logined) {
            return true;
        }
        $admin = Session::get('admin');
        if (!$admin) {
            return false;
        }
        $my = AdminUser::get($admin['id']);
        if (!$my) {
            return false;
        }
        //校验安全码，可用于判断关键信息发生了变更需要重新登录
        if (!isset($admin['safecode']) || $this->getEncryptSafecode($my) !== $admin['safecode']) {
            $this->logout();
            return false;
        }
        //判断是否同一时间同一账号只能在一个地方登录
        if (Config::get('login_unique')) {
            if ($my['token'] != $admin['token']) {
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
    public function isAdministrator()
    {
        return in_array('*', $this->getRuleIds()) ? true : false;
    }

    /**
     * 获取自动登录Key
     * @param $params
     * @param $keeptime
     * @param $expiretime
     * @return string
     */
    public function getKeeploginKey($params, $keeptime, $expiretime)
    {
        $key = md5(md5($params['id']) . md5($keeptime) . md5($expiretime) . $params['token'] . Config::get('token.key'));
        return $key;
    }

    /**
     * 获取加密后的安全码
     * @param $params
     * @return string
     */
    public function getEncryptSafecode($params)
    {
        return md5(md5($params['username']) . md5(substr($params['password'], 0, 6)) . Config::get('token.key'));
    }

    /**
     * 检测当前控制器和方法是否匹配传递的数组
     *
     * @param array $arr 需要验证权限的数组
     * @return bool
     */
    public function match($arr = [])
    {
        $request = Request::instance();
        $arr     = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return false;
        }

        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower($request->action()), $arr) || in_array('*', $arr)) {
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
        $this->logined = false; //重置登录状态
        Session::delete("admin");
        Cookie::delete("keeplogin");
        return true;
    }

    public function getSidebar(){
        $module = request()->module();
        // 读取管理员当前拥有的权限节点
        $userRule = $this->getAuthList();
         // 必须将结果集转换为数组
        $ruleList = \app\admin\model\AuthRule::where('status', 1)
            ->where('ismenu',1)
            ->order('listorder', 'desc')
            ->cache("__menu__")
            ->select()->toArray();
        $indexRuleList = \app\admin\model\AuthRule::where('status', 1)
            ->where('ismenu',0)
            ->where('name', 'like', '%/index')
            ->column('name,parentid');
        $pidArr = array_unique(array_filter(array_column($ruleList, 'parentid')));
        foreach ($ruleList as $k => &$v) {
            if (!in_array($v['name'], $userRule)) {
                unset($ruleList[$k]);
                continue;
            }
            $indexRuleName = $v['name'] . '/index';
            if (isset($indexRuleList[$indexRuleName]) && !in_array($indexRuleName, $userRule)) {
                unset($ruleList[$k]);
                continue;
            }
            $v['type'] = $v['ismenu'];//兼容前端
            $v['href'] = isset($v['url']) && $v['url'] ? $v['url'] : '/' . $module . '/' . $v['name'];
            $v['href'] = preg_match("/^((?:[a-z]+:)?\/\/|data:image\/)(.*)/i", $v['href']) ? $v['href'] : url($v['href']);
        }
        $lastArr = array_unique(array_filter(array_column($ruleList, 'parentid')));
        $pidDiffArr = array_diff($pidArr, $lastArr);
        foreach ($ruleList as $index => $item) {
            if (in_array($item['id'], $pidDiffArr)) {
                unset($ruleList[$index]);
            }
        }
        // 构造菜单数据
        Tree::instance()->init($ruleList);
        return Tree::instance()->getTreeArray(0);
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
