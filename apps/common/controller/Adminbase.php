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
namespace app\common\controller;

use app\admin\model\AuthRule;
use app\admin\service\User;
use think\Db;
use think\Request;

//定义是后台
define('IN_ADMIN', true);

/**
 * 后台总控制器
 */
class Adminbase extends Base
{
    /**
     * 后台初始化
     */
    protected function _initialize()
    {
        parent::_initialize();

        //过滤不需要登陆的行为
        $allowUrl = ['admin/index/login',
            'admin/index/logout',
            'admin/index/getverify',
        ];
        $request = request();
        $visit = strtolower($this->request->module() . "/" . $this->request->controller() . "/" . $this->request->action());
        if (in_array($visit, $allowUrl)) {

        } else {
            if (false == $this->competence()) {
                //跳转到登录界面
                $this->error('请先登陆', url('admin/index/login'));
            } else {
                //是否超级管理员
                if (!User::getInstance()->isAdministrator()) {
                    //检测访问权限
                    $rule = strtolower($this->request->module() . '/' . $this->request->controller() . '/' . $this->request->action());
                    if (!$this->checkRule($rule, array('in', '1,2'))) {
                        $this->error('未授权访问!');
                    }
                }
            }
        }
    }

    /**
     * 验证登录
     * @return boolean
     */
    private function competence()
    {
        //检查是否登录
        $uid = (int) User::getInstance()->isLogin();
        if (empty($uid)) {
            return false;
        }
        //获取当前登录用户信息
        $userInfo = User::getInstance()->getInfo();
        if (empty($userInfo)) {
            User::getInstance()->logout();
            return false;
        }
        //是否锁定
        /*if (!$userInfo['status']) {
        User::getInstance()->logout();
        $this->error('您的帐号已经被锁定！', url('admin/index/login'));
        return false;
        }*/
        return $userInfo;
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
            $Auth = new \com\Auth();
        }
        if (!$Auth->check($rule, User::getInstance()->userid, $type, $mode)) {
            return false;
        }
        return true;
    }

    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param sting|Model  $model   模型名或模型实例
     * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param array        $base    基本的查询条件
     * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists($model, $where = array(), $order = '', $field = true)
    {
        $options = array();
        $REQUEST = (array) Request::instance()->param();
        if (is_string($model)) {
            $model = Db::name($model);
        }
        $pk = $model->getPk();

        if ($order === null) {
            //order置空
        } else if (isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']), array('desc', 'asc'))) {
            $options['order'] = '`' . $REQUEST['_field'] . '` ' . $REQUEST['_order'];
        } elseif ($order === '' && empty($options['order']) && !empty($pk)) {
            $options['order'] = $pk . ' desc';
        } elseif ($order) {
            $options['order'] = $order;
        }
        unset($REQUEST['_order'], $REQUEST['_field']);

        if (empty($options['where'])) {
            unset($options['where']);
        }
        if (isset($REQUEST['rp'])) {
            $listRows = (int) $REQUEST['rp'];
        } else {
            $listRows = config('list_rows') > 0 ? config('list_rows') : 10;
        }
        if (!empty($where)) {
            $options['where'] = $where;
            $list = $model->where($options['where'])->order($options['order'])->field($field)->paginate($listRows, false);
        } else {
            $list = $model->order($options['order'])->field($field)->paginate($listRows, false);
        }
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('_page', $page);
        $this->assign('_total', $list->total());
        if ($list && !is_array($list)) {
            $list = $list->toArray();
        }
        return $list['data'];
    }

}
