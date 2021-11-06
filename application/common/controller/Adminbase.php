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
        $path       = strtolower($this->request->module() . '/' . $this->request->controller() . '/' . $this->request->action());
        // 定义是否Dialog请求
        !defined('IS_DIALOG') && define('IS_DIALOG', $this->request->param("dialog") ? true : false);
        // 检测是否需要验证登录
        if (!$this->auth->match($this->noNeedLogin, $path)) {
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

            if (!IS_ROOT && config('admin_forbid_ip')) {
                // 检查IP地址访问
                $arr = explode(',', config('admin_forbid_ip'));
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
            if (!IS_ROOT && !$this->auth->match($this->noNeedRight, $path)) {
                //检测访问权限
                if (!$this->checkRule($path, [1, 2])) {
                    Hook::listen('admin_nopermission', $this);
                    $this->error('未授权访问!');
                }
            }
        }
        $config = \think\facade\config::get('app.');
        $site   = [
            'upload_thumb_water'     => $config['upload_thumb_water'],
            'upload_thumb_water_pic' => $config['upload_thumb_water_pic'],
            'upload_image_size'      => $config['upload_image_size'],
            'upload_file_size'       => $config['upload_file_size'],
            'upload_image_ext'       => $config['upload_image_ext'],
            'upload_file_ext'        => $config['upload_file_ext'],
            'chunking'               => $config['chunking'],
            'chunksize'              => $config['chunksize'],
        ];
        $this->assign('site', $site);
        $this->assign('auth', $this->auth);
        $this->assign('userInfo', Session::get('admin'));
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
        if (!$Auth->check($rule, UID, $type, $mode)) {
            return false;
        }
        return true;
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

        $search  = $this->request->get("search", '');
        $filters = $this->request->get("filter", '');
        $ops     = $this->request->get("op", '', 'trim');
        $page    = $this->request->get("page/d", 1);
        $limit   = $this->request->get("limit/d", 15);
        $order   = $this->request->get("order", "DESC");
        $sort    = $this->request->get("sort", !empty($this->modelClass) && $this->modelClass->getPk() ? $this->modelClass->getPk() : 'id');

        // json转数组
        $filters   = (array) json_decode($filters, true);
        $ops       = (array) json_decode($ops, true);
        $filters   = $filters ? $filters : [];
        $where     = [];
        $excludes  = [];
        $aliasName = '';

        $tableName = lcfirst($this->modelClass->getName());
        $sortArr   = explode(',', $sort);
        foreach ($sortArr as $index => &$item) {
            $item = stripos($item, ".") === false ? $aliasName . trim($item) : $item;
        }
        unset($item);
        $sort = implode(',', $sortArr);
        if ($search) {
            $searcharr = is_array($searchfields) ? $searchfields : explode(',', $searchfields);
            foreach ($searcharr as $k => &$v) {
                $v = stripos($v, ".") === false ? $aliasName . $v : $v;
            }
            unset($v);
            $where[] = [implode("|", $searcharr), "LIKE", "%{$search}%"];
        }
        foreach ($filters as $key => $val) {
            if (in_array($key, $excludeFields)) {
                $excludes[$key] = $val;
                continue;
            }
            $op = isset($ops[$key]) && !empty($ops[$key]) ? $ops[$key] : '%*%';
            if ($relationSearch && count(explode('.', $key)) == 1) {
                $key = "{$tableName}.{$key}";
            }
            switch (strtolower($op)) {
                case '=':
                    $where[] = [$key, '=', $val];
                    break;
                case '%*%':
                    $where[] = [$key, 'LIKE', "%{$val}%"];
                    break;
                case '*%':
                    $where[] = [$key, 'LIKE', "{$val}%"];
                    break;
                case '%*':
                    $where[] = [$key, 'LIKE', "%{$val}"];
                    break;
                case 'range':
                    list($beginTime, $endTime) = explode(' - ', $val);
                    $where[]                   = [$key, '>=', strtotime($beginTime)];
                    $where[]                   = [$key, '<=', strtotime($endTime)];
                    break;
                default:
                    $where[] = [$key, $op, "%{$val}"];
            }
        }
        return [$page, $limit, $where, $sort, $order];
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
        /*$adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
        $this->model->where($this->dataLimitField, 'in', $adminIds);
        }*/
        $list  = [];
        $total = $this->modelClass->where($where)->count();
        if ($total > 0) {
            /*if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
            }*/
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
