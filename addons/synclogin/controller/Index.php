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

use addons\synclogin\ThinkSDK\Oauth;
use app\addons\util\AddonsBase;
use app\member\model\Member as Member_Model;
use app\member\service\User;
use think\Db;

class Index extends AddonsBase
{
    private $access_token = '';
    private $openid       = '';
    private $type         = '';
    private $token        = array();

    public function initialize()
    {
        parent::initialize();
        $this->getSession();
        $this->UserService = User::instance();
    }

    private function getSession()
    {
        $session            = session('SYNCLOGIN');
        $this->token        = $session['TOKEN'];
        $this->type         = $session['TYPE'];
        $this->openid       = $session['OPENID'];
        $this->access_token = $session['ACCESS_TOKEN'];
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
        $is_login = $this->UserService->isLogin();
        if ($type == null || $code == null) {
            $this->error('参数错误');
        }
        $sns = Oauth::getInstance($type);
        // 获取TOKEN
        $token  = $sns->getAccessToken($code);
        $openid = !empty($token['unionid']) ? $token['unionid'] : $token['openid'];
        if (empty($token)) {
            $this->error('参数错误');
        }
        $session = array('TOKEN' => $token, 'TYPE' => $type, 'OPENID' => $openid, 'ACCESS_TOKEN' => $token['access_token']);
        session('SYNCLOGIN', $session);
        $this->getSession(); // 重新获取session
        //获取当前第三方登录用户信息
        if (is_array($token)) {
            $check = $this->checkIsSync(array('type_uid' => $openid, 'type' => $type));
            //是否完全登录状态
            /*if ($is_login && $check) {
            $this->loginWithoutpwd($is_login);
            }*/
            if ($is_login) {
                $this->dealIsLogin($is_login);
            } else {
                $addon_config = get_addon_config('synclogin');
                if ($addon_config['bind'] && !$check) {
                    $this->redirect(url('addons/synclogin/bind'));
                } else {
                    $this->prepare();
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
        $uid = $this->UserService->isLogin();
        if (!$uid) {
            $this->error('请登录！');
        }

        $res = Db::name('sync_login')->where(array('uid' => $uid, 'type' => $aType))->delete();
        if ($res) {
            $this->success('取消绑定成功', url('member/index/profile'));
        }
        $this->error('取消绑定失败');
    }

    //绑定新账号
    public function newAccount()
    {
        $post   = $data   = $this->request->post();
        $result = $this->validate($data, 'addons\synclogin\validate\Member');
        if (true !== $result) {
            return $this->error($result);
        }
        $userid = $this->UserService->userRegister($data['username'], $data['password'], $data['email']);
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
        $username   = $this->request->param('username');
        $password   = $this->request->param('password');
        $cookieTime = $this->request->param('cookieTime', 0);
        $userInfo   = $this->UserService->loginLocal($username, $password, $cookieTime ? 86400 * 180 : 86400);
        if ($userInfo) {
            $this->addSyncLoginData($userInfo['id']);
            if (!$forward) {
                $forward = url('member/index/index');
            }
            $this->success('账号绑定成功！', $forward);
        } else {
            $this->error('账号绑定失败，请重试！');
        }
    }

    //跳过绑定或自动新增账号
    public function prepare()
    {
        $openid       = $this->openid;
        $type         = $this->type;
        $token        = $this->token;
        $access_token = $this->access_token;
        $map          = array('type_uid' => $openid, 'type' => $type);

        $user_info = \addons\synclogin\ThinkSDK\GetInfo::getInstance($type, $token);
        if ($uid = Db::name('sync_login')->field('uid')->where($map)->value('uid')) {
            $user = Member_Model::where('id', $uid)->find();
            if (!$user) {
                Db::name('sync_login')->where($map)->delete();
                $uid = $this->addData($user_info);
            } else {
                $syncdata['oauth_token']        = $access_token;
                $syncdata['oauth_token_secret'] = $openid;
                Db::name('sync_login')->where($map)->update($syncdata);
            }
        } else {
            $uid = $this->addData($user_info);
        }
        $this->loginWithoutpwd($uid);
    }

    //新增账号
    private function addData($user_info)
    {
        // 先随机一个用户名,随后再变更为u+数字id
        $username = genRandomString(10);
        $password = genRandomString(6);
        $domain   = request()->host();
        $uid      = $this->UserService->userRegister($username, $password, $username . '@' . $domain);
        if ($uid > 0) {
            $fields = ['username' => 'u' . $uid, 'email' => 'u' . $uid . '@' . $domain];
            if (isset($$user_info['nickname'])) {
                $fields['nickname'] = $$user_info['nickname'];
            }
            if (isset($$user_info['avatar'])) {
                $fields['avatar'] = htmlspecialchars(strip_tags($user_info['avatar']));
            }
            // 记录数据到sync_login表中
            $this->addSyncLoginData($uid);
            return $uid;
        } else {
            $this->error($this->UserService->getError() ?: '新增账号失败，请重试！');
        }
    }

    /**
     * loginWithoutpwd  使用uid直接登陆，不使用帐号密码
     */
    private function loginWithoutpwd($uid)
    {
        if (0 < $uid) {
            if ($this->UserService->direct($uid)) {
                //登陆用户
                $this->success('登陆成功！', url('member/index/index'));
            } else {
                $this->error('登录失败！');
            }
        }
    }

    /**
     * addSyncLoginData  增加sync_login表中数据
     */
    private function addSyncLoginData($uid)
    {
        $data['uid']                = $uid;
        $data['type_uid']           = $this->openid;
        $data['oauth_token']        = $this->access_token;
        $data['oauth_token_secret'] = $this->openid;
        $data['type']               = $this->type;

        if (!Db::name('sync_login')->where($data)->count()) {
            Db::name('sync_login')->insert($data);
        }
        return true;
    }

    protected function dealIsLogin($uid = 0)
    {
        $session = session('SYNCLOGIN');
        $openid  = $session['OPENID'];
        $type    = $session['TYPE'];
        if ($this->checkIsSync(array('type_uid' => $openid, 'type' => $type))) {
            $this->error('该帐号已经被绑定！');
        }
        $this->addSyncLoginData($uid);
        $this->success('绑定成功！', url('member/index/profile'));
    }

    private function checkIsSync($map = array())
    {
        if (Db::name('sync_login')->where($map)->count()) {
            return true;
        } else {
            return false;
        }
    }

}
