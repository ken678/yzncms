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
// | 后台控制模块
// +----------------------------------------------------------------------
namespace app\common\controller;

use app\admin\service\User;
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
    public $_userinfo;
    public $rule;
    //Multi方法可批量修改的字段.
    protected $multiFields = 'status,listorder';
    //模型对象
    protected $modelClass = null;
    //Selectpage可显示的字段
    protected $selectpageFields = '*';
    //是否是关联查询
    protected $relationSearch = false;

    /*
     * 引入后台控制器的traits
     */
    use \app\admin\library\traits\Curd;

    //初始化
    protected function initialize()
    {
        parent::initialize();
        $this->rule = strtolower($this->request->module() . '/' . $this->request->controller() . '/' . $this->request->action());
        // 定义是否Dialog请求
        !defined('IS_DIALOG') && define('IS_DIALOG', $this->request->param("dialog") ? true : false);
        // 检测是否需要验证登录
        if (!$this->match($this->noNeedLogin)) {
            if (defined('UID')) {
                return;
            }
            define('UID', (int) User::instance()->isLogin());
            // 是否是超级管理员
            define('IS_ROOT', User::instance()->isAdministrator());

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

            // 判断是否需要验证权限
            if (false == $this->competence()) {
                //跳转到登录界面
                $this->error('请先登陆', url('admin/index/login'));
            } else {
                if (!$this->match($this->noNeedRight) && !IS_ROOT) {
                    //检测访问权限
                    if (!$this->checkRule($this->rule, [1, 2])) {
                        $this->error('未授权访问!');
                    }
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
    }

    public function match($arr = [])
    {
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return false;
        }
        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower($this->rule), $arr) || in_array('*', $arr)) {
            return true;
        }
        // 没找到匹配
        return false;
    }

    /**
     * 验证登录
     * @return boolean
     */
    private function competence()
    {
        //获取当前登录用户信息
        $this->_userinfo = $userInfo = Session::get('admin');
        if (empty($userInfo)) {
            User::instance()->logout();
            return false;
        }
        $this->assign('userInfo', $this->_userinfo);
        return $userInfo;

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
     * @param array $excludeFields 忽略构建搜索的字段
     * @return array
     */
    protected function buildTableParames($excludeFields = [], $relationSearch = null)
    {
        $relationSearch = is_null($relationSearch) ? $this->relationSearch : $relationSearch;
        $get            = $this->request->get('', null, null);
        $page           = isset($get['page']) && !empty($get['page']) ? $get['page'] : 1;
        $limit          = isset($get['limit']) && !empty($get['limit']) ? $get['limit'] : 15;
        $filters        = isset($get['filter']) && !empty($get['filter']) ? $get['filter'] : '{}';
        $ops            = isset($get['op']) && !empty($get['op']) ? $get['op'] : '{}';
        // json转数组
        $filters  = json_decode($filters, true);
        $ops      = json_decode($ops, true);
        $where    = [];
        $excludes = [];

        $tableName = lcfirst($this->modelClass->getName());

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
        return [$page, $limit, $where, $excludes];
    }

    /**
     * fastadmin的Selectpage的实现方法
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
