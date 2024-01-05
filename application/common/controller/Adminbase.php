<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: fastadmin: https://www.fastadmin.net/
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 后台控制模块
// +----------------------------------------------------------------------
namespace app\common\controller;

use app\admin\service\User;
use think\facade\Config;
use think\facade\Hook;
use think\facade\Session;
use think\Validate;

//定义是后台
define('IN_ADMIN', true);

class Adminbase extends Base
{
    //无需登录的方法,同时也就不需要鉴权了
    protected $noNeedLogin = [];
    //无需鉴权的方法,但需要登录
    protected $noNeedRight = [];
    //当前登录账号信息
    //public $_userinfo;
    //Multi方法可批量修改的字段
    protected $multiFields = 'status,listorder';
    /**
     * 权限控制类
     * @var Auth
     */
    protected $auth = null;
    //模型对象
    protected $modelClass = null;
    //快速搜索时执行查找的字段
    protected $searchFields = 'id';
    //Selectpage可显示的字段
    protected $selectpageFields = '*';
    //是否是关联查询
    protected $relationSearch = false;
    //前台提交过来,需要排除的字段数据
    protected $excludeFields = "";
    /**
     * 是否开启数据限制
     * 支持auth/personal
     * 表示按权限判断/仅限个人
     * 默认为禁用,若启用请务必保证表中存在admin_id字段
     */
    protected $dataLimit = false;
    //数据限制字段
    protected $dataLimitField = 'admin_id';
    //数据限制开启时自动填充限制字段值
    protected $dataLimitFieldAutoFill = true;
    //是否开启Validate验证
    protected $modelValidate = false;
    //是否开启模型场景验证
    protected $modelSceneValidate = false;

    /*
     * 引入后台控制器的traits
     */
    use \app\admin\library\traits\Curd;

    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->auth = User::instance();

        $modulename     = $this->request->module();
        $controllername = parse_name($this->request->controller());
        $actionname     = strtolower($this->request->action());

        $path = $controllername . '/' . $actionname;
        // 定义是否Dialog请求
        !defined('IS_DIALOG') && define('IS_DIALOG', $this->request->param("dialog") ? true : false);
        // 检测是否需要验证登录
        if (!$this->auth->match($this->noNeedLogin)) {
            if (defined('UID')) {
                return;
            }
            if (!$this->auth->isLogin()) {
                Hook::listen('admin_nologin', $this);
                $this->error('请先登陆', url('admin/index/login'));
            }
            define('UID', (int) $this->auth->id);

            // 是否是超级管理员
            define('IS_ROOT', $this->auth->isAdministrator());

            if (!IS_ROOT && config::get('site.admin_forbid_ip')) {
                // 检查IP地址访问
                $arr = explode(',', config::get('site.admin_forbid_ip'));
                foreach ($arr as $val) {
                    //是否是IP段
                    if (strpos($val, '*')) {
                        if (strpos($this->request->ip(), str_replace('.*', '', $val)) !== false) {
                            $this->error('403:你在IP禁止段内,禁止访问！');
                        }
                    } else {
                        //不是IP段,用绝对匹配
                        if ($this->request->ip() == $val) {
                            $this->error('403:IP地址绝对匹配,禁止访问！');
                        }

                    }
                }
            }
            if (!IS_ROOT && !$this->auth->match($this->noNeedRight)) {
                //检测访问权限
                if (!$this->auth->check($path)) {
                    Hook::listen('admin_nopermission', $this);
                    $this->error('未授权访问!');
                }
            }
        }
        $site   = Config::get("site.");
        $config = [
            'modulename'     => $modulename,
            'controllername' => $controllername,
            'actionname'     => $actionname,
        ];
        //监听插件传入的变量
        $site = array_merge($site, $config, ...Hook::listen("config_init"));
        $this->assign('site', $site);
        $this->assign('auth', $this->auth);
        $this->assign('userInfo', Session::get('admin'));
    }

    /**
     * 渲染配置信息
     * @param mixed $name  键名或数组
     * @param mixed $value 值
     */
    protected function assignconfig($name, $value = '')
    {
        $this->view->site = array_merge($this->view->site ? $this->view->site : [], is_array($name) ? $name : [$name => $value]);
    }

    /**
     * 操作错误跳转的快捷方法
     */
    final public function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        model('admin/Adminlog')->record($msg, 0);
        parent::error($msg, $url, $data, $wait, $header);
    }

    /**
     * 操作成功跳转的快捷方法
     */
    final public function success($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        model('admin/Adminlog')->record($msg, 1);
        parent::success($msg, $url, $data, $wait, $header);
    }

    /**
     * 构建请求参数
     * @param mixed   $searchfields   快速查询的字段
     * @param array $excludeFields 忽略构建搜索的字段
     * @param boolean $relationSearch 是否关联查询
     * @return array
     */
    protected function buildTableParames($searchfields = null, $excludeFields = [], $relationSearch = null)
    {
        $searchfields   = is_null($searchfields) ? $this->searchFields : $searchfields;
        $relationSearch = is_null($relationSearch) ? $this->relationSearch : $relationSearch;
        $search         = $this->request->get("search", '');
        $filter         = $this->request->get("filter", '');
        $op             = $this->request->get("op", '', 'trim');
        $sort           = $this->request->get("sort", !empty($this->modelClass) && $this->modelClass->getPk() ? $this->modelClass->getPk() : 'id');
        $order          = $this->request->get("order", "DESC");
        $limit          = $this->request->get("limit/d", 999999);
        $page           = $this->request->get("page/d", 1);
        //新增自动计算分页偏移
        $offset = $page ? ($page - 1) * $limit : 0;
        if ($this->request->has("offset")) {
            $offset = $this->request->get("offset/d", 0);
        }
        $this->request->withGet([config::get('paginate.var_page') => $page]);
        $filter    = (array) json_decode($filter, true);
        $op        = (array) json_decode($op, true);
        $filter    = $filter ? $filter : [];
        $where     = [];
        $alias     = [];
        $bind      = [];
        $name      = '';
        $aliasName = '';
        if (!empty($this->modelClass) && $relationSearch) {
            $name         = $this->modelClass->getTable();
            $alias[$name] = parse_name(basename(str_replace('\\', '/', get_class($this->modelClass))));
            $aliasName    = $alias[$name] . '.';
        }
        $sortArr = explode(',', $sort);
        foreach ($sortArr as $index => &$item) {
            $item = stripos($item, ".") === false ? $aliasName . trim($item) : $item;
        }
        unset($item);
        $sort     = implode(',', $sortArr);
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $where[] = [$aliasName . $this->dataLimitField, 'in', $adminIds];
        }
        if ($search) {
            $searcharr = is_array($searchfields) ? $searchfields : explode(',', $searchfields);
            foreach ($searcharr as $k => &$v) {
                $v = stripos($v, ".") === false ? $aliasName . $v : $v;
            }
            unset($v);
            $where[] = [implode("|", $searcharr), "LIKE", "%{$search}%"];
        }
        $index = 0;
        foreach ($filter as $k => $v) {
            if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $k)) {
                continue;
            }
            $sym = isset($op[$k]) ? $op[$k] : '=';
            if (stripos($k, ".") === false) {
                $k = $aliasName . $k;
            }
            $v   = !is_array($v) ? trim($v) : $v;
            $sym = strtoupper(isset($op[$k]) ? $op[$k] : $sym);
            //null和空字符串特殊处理
            if (!is_array($v)) {
                if (in_array(strtoupper($v), ['NULL', 'NOT NULL'])) {
                    $sym = strtoupper($v);
                }
                if (in_array($v, ['""', "''"])) {
                    $v   = '';
                    $sym = '=';
                }
            }

            switch ($sym) {
                case '=':
                case '<>':
                    $where[] = [$k, $sym, (string) $v];
                    break;
                case 'LIKE':
                case 'NOT LIKE':
                case 'LIKE %...%':
                case 'NOT LIKE %...%':
                    $where[] = [$k, trim(str_replace('%...%', '', $sym)), "%{$v}%"];
                    break;
                case '>':
                case '>=':
                case '<':
                case '<=':
                    $where[] = [$k, $sym, intval($v)];
                    break;
                case 'FINDIN':
                case 'FINDINSET':
                case 'FIND_IN_SET':
                    $v       = is_array($v) ? $v : explode(',', str_replace(' ', ',', $v));
                    $findArr = array_values($v);
                    foreach ($findArr as $idx => $item) {
                        $bindName        = "item_" . $index . "_" . $idx;
                        $bind[$bindName] = $item;
                        $where[]         = ["FIND_IN_SET(:{$bindName}, `" . str_replace('.', '`.`', $k) . "`)", [$bindName => $item]];
                    }
                    break;
                case 'IN':
                case 'IN(...)':
                case 'NOT IN':
                case 'NOT IN(...)':
                    $where[] = [$k, str_replace('(...)', '', $sym), is_array($v) ? $v : explode(',', $v)];
                    break;
                case 'BETWEEN':
                case 'NOT BETWEEN':
                    $arr = array_slice(explode(',', $v), 0, 2);
                    if (stripos($v, ',') === false || !array_filter($arr)) {
                        continue 2;
                    }
                    //当出现一边为空时改变操作符
                    if ($arr[0] === '') {
                        $sym = $sym == 'BETWEEN' ? '<=' : '>';
                        $arr = $arr[1];
                    } elseif ($arr[1] === '') {
                        $sym = $sym == 'BETWEEN' ? '>=' : '<';
                        $arr = $arr[0];
                    }
                    $where[] = [$k, $sym, $arr];
                    break;
                case 'RANGE':
                case 'NOT RANGE':
                    $v   = str_replace(' - ', ',', $v);
                    $arr = array_slice(explode(',', $v), 0, 2);
                    if (stripos($v, ',') === false || !array_filter($arr)) {
                        continue 2;
                    }
                    //当出现一边为空时改变操作符
                    if ($arr[0] === '') {
                        $sym = $sym == 'RANGE' ? '<=' : '>';
                        $arr = $arr[1];
                    } elseif ($arr[1] === '') {
                        $sym = $sym == 'RANGE' ? '>=' : '<';
                        $arr = $arr[0];
                    }
                    $tableArr = explode('.', $k);
                    if (count($tableArr) > 1 && $tableArr[0] != $name && !in_array($tableArr[0], $alias) && !empty($this->modelClass)) {
                        //修复关联模型下时间无法搜索的BUG
                        $relation                                          = parse_name($tableArr[0], 1, false);
                        $alias[$this->modelClass->$relation()->getTable()] = $tableArr[0];
                    }
                    $where[] = [$k, str_replace('RANGE', 'BETWEEN', $sym) . ' TIME', $arr];
                    break;
                case 'NULL':
                case 'IS NULL':
                case 'NOT NULL':
                case 'IS NOT NULL':
                    $where[] = [$k, strtolower(str_replace('IS ', '', $sym))];
                    break;
                default:
                    break;
            }
            $index++;
        }
        if (!empty($this->modelClass)) {
            $this->modelClass->alias($alias);
        }
        $model = $this->modelClass;
        $where = function ($query) use ($where, $alias, $bind, &$model) {
            if (!empty($model)) {
                $model->alias($alias);
                //$model->bind($bind);
            }
            foreach ($where as $k => $v) {
                if (is_array($v)) {
                    call_user_func_array([$query, 'where'], $v);
                } else {
                    $query->where($v);
                }
            }
        };
        return [$page, $limit, $where, $sort, $order, $offset, $alias, $bind];
    }

    /**
     * 获取数据限制的管理员ID
     * 禁用数据限制时返回的是null
     * @return mixed
     */
    protected function getDataLimitAdminIds()
    {
        if (!$this->dataLimit) {
            return null;
        }
        if ($this->auth->isAdministrator()) {
            return null;
        }
        $adminIds = [];
        if (in_array($this->dataLimit, ['auth', 'personal'])) {
            $adminIds = $this->dataLimit == 'auth' ? $this->auth->getChildrenAdminIds(true) : [$this->auth->id];
        }
        return $adminIds;
    }

    /**
     *
     * 当前方法只是一个比较通用的搜索匹配,请按需重载此方法来编写自己的搜索逻辑,$where按自己的需求写即可
     * 这里示例了所有的参数，所以比较复杂，实现上自己实现只需简单的几行即可
     *
     */
    protected function selectpage()
    {
        //设置过滤方法
        $this->request->filter(['trim', 'strip_tags', 'htmlspecialchars']);

        //搜索关键词,客户端输入以空格分开,这里接收为数组
        $word = (array) $this->request->request("q_word/a");
        //当前页
        $page = $this->request->request("pageNumber");
        //分页大小
        $pagesize = $this->request->request("pageSize");
        //搜索条件
        $andor = $this->request->request("andOr", "and", "strtoupper");
        //排序方式
        $orderby = (array) $this->request->request("orderBy/a");
        //显示的字段
        $field = $this->request->request("showField");
        //主键
        $primarykey = $this->request->request("keyField");
        //主键值
        $primaryvalue = $this->request->request("keyValue");
        //搜索字段
        $searchfield = (array) $this->request->request("searchField/a");
        //自定义搜索条件
        $custom = (array) $this->request->request("custom/a");
        //是否返回树形结构
        $istree = $this->request->request("isTree", 0);
        $ishtml = $this->request->request("isHtml", 0);
        if ($istree) {
            $word     = [];
            $pagesize = 99999;
        }
        $order = [];
        foreach ($orderby as $k => $v) {
            $order[$v[0]] = $v[1];
        }
        $field = $field ? $field : 'name';

        //如果有primaryvalue,说明当前是初始化传值
        if ($primaryvalue !== null) {
            //$where = [$primarykey => ['in', $primaryvalue]];
            $where    = [$primarykey => explode(',', $primaryvalue)];
            $pagesize = 99999;
        } else {
            $where = function ($query) use ($word, $andor, $field, $searchfield, $custom) {
                $logic       = $andor == 'AND' ? '&' : '|';
                $searchfield = is_array($searchfield) ? implode($logic, $searchfield) : $searchfield;
                $searchfield = str_replace(',', $logic, $searchfield);
                $word        = array_filter(array_unique($word));
                if (count($word) == 1) {
                    $query->where($searchfield, "like", "%" . reset($word) . "%");
                } else {
                    $query->where(function ($query) use ($word, $searchfield) {
                        foreach ($word as $index => $item) {
                            $query->whereOr(function ($query) use ($item, $searchfield) {
                                $query->where($searchfield, "like", "%{$item}%");
                            });
                        }
                    });
                }
                if ($custom && is_array($custom)) {
                    foreach ($custom as $k => $v) {
                        if (is_array($v) && 2 == count($v)) {
                            $query->where($k, trim($v[0]), $v[1]);
                        } else {
                            $query->where($k, '=', $v);
                        }
                    }
                }
            };
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->modelClass = $this->modelClass->where($this->dataLimitField, 'in', $adminIds);
        }
        $list  = [];
        $total = $this->modelClass->where($where)->count();
        if ($total > 0) {
            $fields = is_array($this->selectpageFields) ? $this->selectpageFields : ($this->selectpageFields && $this->selectpageFields != '*' ? explode(',', $this->selectpageFields) : []);
            //如果有primaryvalue,说明当前是初始化传值,按照选择顺序排序
            if ($primaryvalue !== null && preg_match("/^[a-z0-9_\-]+$/i", $primarykey)) {
                $primaryvalue = array_unique(is_array($primaryvalue) ? $primaryvalue : explode(',', $primaryvalue));
                //修复自定义data-primary-key为字符串内容时，给排序字段添加上引号
                $primaryvalue = array_map(function ($value) {
                    return '\'' . $value . '\'';
                }, $primaryvalue);

                $primaryvalue = implode(',', $primaryvalue);

                $this->modelClass->orderRaw("FIELD(`{$primarykey}`, {$primaryvalue})");
            } else {
                $this->modelClass->order($order);
            }

            $this->modelClass->removeOption('where');

            if (is_array($adminIds)) {
                $this->modelClass->where($this->dataLimitField, 'in', $adminIds);
            }

            $datalist = $this->modelClass->where($where)
                ->page($page, $pagesize)
                ->select();

            foreach ($datalist as $index => $item) {
                unset($item['password'], $item['salt']);
                if ($this->selectpageFields == '*') {
                    $result = [
                        $primarykey => isset($item[$primarykey]) ? $item[$primarykey] : '',
                        $field      => isset($item[$field]) ? $item[$field] : '',
                    ];
                } else {
                    $result = array_intersect_key(($item instanceof Model ? $item->toArray() : (array) $item), array_flip($fields));
                }
                $result['pid'] = isset($item['pid']) ? $item['pid'] : (isset($item['parent_id']) ? $item['parent_id'] : 0);
                $list[]        = $result;
            }
            if ($istree && !$primaryvalue) {
                $tree = \util\Tree::instance();
                $tree->init(collection($list)->toArray(), 'pid');
                $list = $tree->getTreeList($tree->getTreeArray(0), $field);
                if (!$ishtml) {
                    foreach ($list as &$item) {
                        $item = str_replace('&nbsp;', ' ', $item);
                    }
                    unset($item);
                }
            }
        }
        //这里一定要返回有list这个字段,total是可选的,如果total<=list的数量,则会隐藏分页按钮
        return json(['data' => $list, 'count' => $total]);
    }

    //刷新Token
    protected function token()
    {
        $token = $this->request->param('__token__');
        //验证Token
        if (!Validate::make()->check(['__token__' => $token], ['__token__' => 'require|token'])) {
            $this->error('令牌错误！', '', ['__token__' => $this->request->token()]);
        }
        //刷新Token
        $this->request->token();
    }
}
