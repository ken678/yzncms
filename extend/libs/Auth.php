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
// | AUTH类
// +----------------------------------------------------------------------
namespace libs;

use think\Db;
use think\facade\Config;
use think\facade\Request;
use think\facade\Session;

/**
 * 权限认证类
 * 功能特性：
 * 1，是对规则进行认证，不是对节点进行认证。用户可以把节点当作规则名称实现对节点进行认证。
 *      $auth=new Auth();  $auth->check('规则名称','用户id')
 * 2，可以同时对多条规则进行认证，并设置多条规则的关系（or或者and）
 *      $auth=new Auth();  $auth->check('规则1,规则2','用户id','and')
 *      第三个参数为and时表示，用户需要同时具有规则1和规则2的权限。 当第三个参数为or时，表示用户值需要具备其中一个条件即可。默认为or
 * 3，一个用户可以属于多个用户组(think_auth_group_access表 定义了用户所属用户组)。我们需要设置每个用户组拥有哪些规则(think_auth_group 定义了用户组权限)
 *
 * 4，支持规则表达式。
 *      在think_auth_rule 表中定义一条规则时，如果type为1， condition字段就可以定义规则表达式。 如定义{score}>5  and {score}<100  表示用户的分数在5-100之间时这条规则才会通过。
 */
class Auth
{
    protected static $instance;
    /**
     * 当前请求实例
     * @var Request
     */
    protected $request;
    //默认配置
    protected $_config = [
        'AUTH_ON'    => true, // 认证开关
        'AUTH_TYPE'  => 1, // 认证方式，1为实时认证；2为登录认证。
        'AUTH_GROUP' => 'auth_group', // 用户组数据表名
        'AUTH_RULE'  => 'auth_rule', // 权限规则表
        'AUTH_USER'  => 'admin', // 用户信息表
    ];

    /**
     * 类架构函数
     * Auth constructor.
     */
    public function __construct()
    {
        //可设置配置项 auth, 此配置项为数组。
        if ($auth = Config::get('auth')) {
            $this->config = array_merge($this->_config, $auth);
        }
        // 初始化request
        $this->request = Request::instance();
    }

    /**
     * 初始化
     * @access public
     * @param array $options 参数
     * @return Auth
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }

        return self::$instance;
    }

    /**
     * 检查权限
     * @param name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param uid  int           认证用户的id
     * @param string mode        执行check的模式
     * @param relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return boolean           通过验证返回true;失败返回false
     */
    public function check($name, $uid, $type = 1, $mode = 'url', $relation = 'or')
    {
        if (!$this->_config['AUTH_ON']) {
            return true;
        }
        $authList = $this->getAuthList($uid); //获取用户需要验证的所有有效规则列表
        if (in_array('*', $authList)) {
            return true;
        }
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = [$name];
            }
        }
        $list = []; //保存验证通过的规则名
        if ('url' == $mode) {
            $REQUEST = unserialize(strtolower(serialize($this->request->param())));
        }
        foreach ($authList as $auth) {
            $query = preg_replace('/^.+\?/U', '', $auth);
            if ($mode == 'url' && $query != $auth) {
                parse_str($query, $param); //解析规则中的param
                $intersect = array_intersect_assoc($REQUEST, $param);
                $auth      = preg_replace('/\?.*$/U', '', $auth);
                if (in_array($auth, $name) && $intersect == $param) {
                    //如果节点相符且url参数满足
                    $list[] = $auth;
                }
            } elseif (in_array($auth, $name)) {
                $list[] = $auth;
            }
        }
        if ($relation == 'or' and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation == 'and' and empty($diff)) {
            return true;
        }
        return false;
    }

    /**
     * 根据用户id获取用户组,返回值为数组
     * @param  uid int     用户id
     * @return array       用户所属的用户组 array(
     *                                         array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     *                                         ...)
     */
    public function getGroups($uid)
    {
        static $groups = [];
        if (isset($groups[$uid])) {
            return $groups[$uid];
        }
        $user_groups = Db::name($this->_config['AUTH_USER'])
            ->alias('a')
            ->where('a.id', $uid)
            ->where('g.status', 1)
            ->join($this->_config['AUTH_GROUP'] . ' g', "g.id = a.roleid")
            ->field('a.id as uid,g.id,g.parentid,roleid,title,rules')
            ->select();
        $groups[$uid] = $user_groups ?: [];
        return $groups[$uid];
    }

    /**
     * 获得权限列表
     * @param integer $uid  用户id
     */
    protected function getAuthList($uid)
    {
        static $_rulelist = []; //保存用户验证通过的权限列表
        if (isset($_rulelist[$uid])) {
            return $_rulelist[$uid];
        }
        if (2 == $this->_config['AUTH_TYPE'] && Session::has('_AUTH_LIST_' . $uid)) {
            return Session::get('_AUTH_LIST_' . $uid);
        }
        // 读取用户规则节点
        $ids = $this->getRuleIds($uid);
        if (empty($ids)) {
            $_rulelist[$uid] = [];
            return [];
        }
        $where = [
            ['status', '=', 1],
        ];
        if (!in_array('*', $ids)) {
            $where[] = ['id', 'in', $ids];
        }
        //读取用户组所有权限规则
        $rules = Db::name($this->_config['AUTH_RULE'])->where($where)->field('id,parentid,condition,icon,name,title,ismenu')->select();
        //循环规则，判断结果。
        $rulelist = [];
        if (in_array('*', $ids)) {
            $rulelist[] = "*";
        }
        foreach ($rules as $rule) {
            //超级管理员无需验证condition
            if (!empty($rule['condition']) && !in_array('*', $ids)) {
                //根据condition进行验证
                $user    = $this->getUserInfo($uid); //获取用户信息,一维数组
                $nums = 0;
                $condition = str_replace(['&&', '||'], "\r\n", $rule['condition']);
                $condition = preg_replace('/\{(\w*?)\}/', '\\1', $condition);
                $conditionArr = explode("\r\n", $condition);
                foreach ($conditionArr as $index => $item) {
                    preg_match("/^(\w+)\s?([\>\<\=]+)\s?(.*)$/", trim($item), $matches);
                    if ($matches && isset($user[$matches[1]]) && version_compare($user[$matches[1]], $matches[3], $matches[2])) {
                        $nums++;
                    }
                }
                if ($conditionArr && ((stripos($rule['condition'], "||") !== false && $nums > 0) || count($conditionArr) == $nums)) {
                    $rulelist[$rule['id']] = strtolower($rule['name']);
                }

            } else {
                //只要存在就记录
                $rulelist[$rule['id']] = strtolower($rule['name']);
            }
        }
        $_rulelist[$uid] = $rulelist;
        //登录验证则需要保存规则列表
        if (2 == $this->_config['AUTH_TYPE']) {
            //规则列表结果保存到session
            Session::set('_AUTH_LIST_' . $uid, $rulelist);
        }
        return array_unique($rulelist);
    }

    public function getRuleIds($uid)
    {
        //读取用户所属用户组
        $groups = $this->getGroups($uid);
        $ids    = []; //保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        return $ids;
    }

    /**
     * 获得用户资料,根据自己的情况读取数据库
     */
    protected function getUserInfo($uid)
    {
        static $userinfo = [];
        if (!isset($userinfo[$uid])) {
            $userinfo[$uid] = Db::name($this->_config['auth_user'])->where(['id' => $uid])->find();
        }
        return $userinfo[$uid];
    }

}
