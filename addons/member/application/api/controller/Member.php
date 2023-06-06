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
// | 前台会员api管理
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\common\controller\Api;
use app\member\service\User;

class Member extends Api
{
    //会员模型相关配置
    protected $memberConfig = [];
    //会员组缓存
    protected $memberGroup = [];
    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = ['login'];
    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = [];
    /**
     * 权限Auth
     * @var Auth
     */
    protected $auth = null;

    //初始化
    protected function initialize()
    {
        parent::initialize();
        $modulename         = $this->request->module();
        $controllername     = parse_name($this->request->controller());
        $actionname         = strtolower($this->request->action());
        $this->memberConfig = get_addon_config("member");
        $this->memberGroup  = cache("Member_Group");

        $this->auth = User::instance();
        $token      = $this->request->server('HTTP_TOKEN', $this->request->request('token', \think\facade\Cookie::get('token')));
        $path       = str_replace('.', '/', $controllername) . '/' . $actionname;
        if (substr($this->request->module(), 0, 7) == 'public_' || !$this->auth->match($this->noNeedLogin)) {
            //初始化
            $this->auth->init($token);
            //检测是否登录
            if (!$this->auth->isLogin()) {
                $this->error('请登录后再操作', null, 401);
            }
            // 判断是否需要验证权限
            /*if (!$this->auth->match($this->noNeedRight)) {

        }*/
        } else {
            // 如果有传递token才验证是否登录状态
            if ($token) {
                $this->auth->init($token);
            }
        }
    }

    /**
     * 会员中心
     */
    public function index()
    {
        $this->success('', ['welcome' => $this->auth->nickname]);
    }

    /**
     * 会员登录
     *
     * @ApiMethod (POST)
     * @param string $account  账号
     * @param string $password 密码
     */
    public function login()
    {
        $account  = $this->request->post('account');
        $password = $this->request->post('password');
        if (!$account || !$password) {
            $this->error('未知参数');
        }
        $ret = $this->auth->loginLocal($account, $password);
        if ($ret) {
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success('登录成功', $data);
        } else {
            $this->error($this->auth->getError());
        }
    }

    /**
     * 退出登录
     * @ApiMethod (POST)
     */
    public function logout()
    {
        if (!$this->request->isPost()) {
            $this->error('未知参数');
        }
        $this->auth->logout();
        $this->success('注销成功');
    }
}
