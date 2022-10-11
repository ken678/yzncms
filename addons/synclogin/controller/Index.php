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
// | 第三方登录管理
// +----------------------------------------------------------------------
namespace addons\synclogin\Controller;

use addons\synclogin\library\Oauth;
use addons\synclogin\library\Service;
use addons\synclogin\model\SyncLogin as SyncLoginModel;
use app\member\controller\MemberBase;
use think\facade\Cookie;
use think\facade\Hook;

class Index extends MemberBase
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = [];
    private $access_token  = '';
    private $openid        = '';
    private $type          = '';
    private $token         = array();

    public function initialize()
    {
        parent::initialize();
        $auth = $this->auth;
        //监听注册登录退出的事件
        Hook::add('user_login_successed', function ($user) use ($auth) {
            $expire = $this->request->post('keeplogin') ? 30 * 86400 : 0;
            Cookie::set('uid', $user->id, $expire);
            Cookie::set('token', $auth->getToken(), $expire);
        });
        Hook::add('user_register_successed', function ($user) use ($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });
        Hook::add('user_delete_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
        Hook::add('user_logout_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
        $this->getSession();
    }

    private function getSession()
    {
        $session            = session('SYNCLOGIN');
        $this->token        = $session['TOKEN'];
        $this->type         = $session['TYPE'];
        $this->openid       = $session['OPENID'];
        $this->access_token = $session['ACCESS_TOKEN'];
        $this->openname     = $session['OPENNAME'];
    }

    //登陆地址
    public function login()
    {
        $type = $this->request->param('type');
        empty($type) && $this->error('参数错误');
        //加载ThinkOauth类并实例化一个对象
        $sns          = Oauth::getInstance($type);
        $addon_config = get_addon_config('synclogin');
        if (0 == $addon_config['display']) {
            $sns->setDisplay('mobile');
        }
        //跳转到授权页面
        $this->redirect($sns->getRequestCodeURL());

    }

    //授权回调地址
    public function callback()
    {
        $type     = $this->request->param('type/s');
        $code     = $this->request->param('code/s');
        $is_login = $this->auth->isLogin();
        if ($type == null || $code == null) {
            $this->error('参数错误');
        }
        $sns = Oauth::getInstance($type);
        // 获取TOKEN
        $token = $sns->getAccessToken($code);
        // 获取第三方获取信息
        $userinfo = $sns->getUserInfo();
        $openid   = !empty($token['unionid']) ? $token['unionid'] : $token['openid'];
        if (empty($token)) {
            $this->error('参数错误');
        }
        $session = array('TOKEN' => $token, 'TYPE' => $type, 'OPENID' => $openid, 'ACCESS_TOKEN' => $token['access_token'], 'OPENNAME' => $userinfo['nickname']);
        session('SYNCLOGIN', $session);
        $this->getSession(); // 重新获取session
        //获取当前第三方登录用户信息
        if (is_array($token)) {
            $check = Service::isBindThird($type, $openid);
            if ($is_login) {
                $this->dealIsLogin($this->auth->id);
            } else {
                $addon_config = get_addon_config('synclogin');
                if ($addon_config['bind'] && !$check) {
                    $this->redirect(addon_url('synclogin/index/bind'));
                } else {
                    //$this->prepare();
                    $loginret = Service::connect($type, $userinfo);
                    if ($loginret) {
                        $this->success('登陆成功！', url('member/index/index'));
                    }
                }
            }
        } else {
            echo "获取第三方用户的基本信息失败";
        }
    }

    //绑定账号
    public function bind()
    {
        if (!$this->token) {
            $this->error('无效的token');
        }
        $tip               = $this->request->param('tip/s');
        $tip == '' && $tip = 'new';
        $this->assign('tip', $tip);
        return $this->fetch('bind');
    }

    //解绑账号
    public function unBind()
    {
        $aType = $this->request->param('type/s');
        if (empty($aType)) {
            $this->error('参数错误');
        }
        if (!$this->auth->isLogin()) {
            $this->error('请登录！');
        }
        $third = SyncLoginModel::where('uid', $this->auth->id)->where('type', $aType)->find();
        if (!$third) {
            $this->error("未找到指定的账号绑定信息");
        }
        $third->delete();
        $this->success('取消绑定成功', url('member/index/profile'));
    }

    //绑定新账号
    public function newAccount()
    {
        $post   = $data   = $this->request->post('');
        $result = $this->validate($data, 'addons\synclogin\validate\Member');
        if (true !== $result) {
            return $this->error($result);
        }
        $userid = $this->auth->userRegister($data['username'], $data['password'], $data['email']);
        if ($userid > 0) {
            $this->addSyncLoginData($userid);
            $this->success('账号绑定成功！', url('member/index/index'));
        } else {
            $this->error('账号绑定失败，请重试！');
        }

    }

    //绑定已有账号
    public function bindAccount()
    {
        $account  = $this->request->param('account');
        $password = $this->request->param('password');
        $userInfo = $this->auth->loginLocal($account, $password);
        if ($userInfo) {
            $this->addSyncLoginData($this->auth->id);
            $this->success('账号绑定成功！', url('member/index/index'));
        } else {
            $this->error('账号绑定失败，请重试！');
        }
    }

    /**
     * 增加sync_login表中数据
     */
    private function addSyncLoginData($uid)
    {
        $data['uid']          = $uid;
        $data['openid']       = $this->openid;
        $data['access_token'] = $this->access_token;
        $data['type']         = $this->type;
        $data['openname']     = $this->openname;
        $data['login_time']   = time();
        return SyncLoginModel::create($data);
    }

    protected function dealIsLogin($uid = 0)
    {
        $session = session('SYNCLOGIN');
        $openid  = $session['OPENID'];
        $type    = $session['TYPE'];
        if (Service::isBindThird($type, $openid)) {
            $this->error('该帐号已经被绑定！');
        }
        $this->addSyncLoginData($uid);
        $this->success('绑定成功！', url('member/index/profile'));
    }
}
