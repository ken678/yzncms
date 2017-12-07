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
namespace addons\Synclogin\Controller;

use app\home\controller\Addons;
use app\user\api\UserApi;

require_once dirname(dirname(__FILE__)) . "/ThinkSDK/ThinkOauth.class.php";

class Base extends Addons
{
    private $access_token = '';
    private $openid = '';
    private $type = '';
    private $token = array();

    public function _initialize()
    {
        $this->getSession();
    }

    private function getSession()
    {
        $session = session('SYNCLOGIN');
        /*        if(empty($session) && (ACTION_NAME != 'callback' && ACTION_NAME != 'login')){
        $this->error('参数错误');
        }*/
        $this->token = $session['TOKEN'];
        $this->type = $session['TYPE'];
        $this->openid = $session['OPENID'];
        $this->access_token = $session['ACCESS_TOKEN'];

    }

    //登陆地址
    public function login()
    {
        $type = $this->request->param('type');
        empty($type) && $this->error('参数错误');
        //加载ThinkOauth类并实例化一个对象
        $sns = \ThinkOauth::getInstance($type);
        //跳转到授权页面
        if ($type == 'weixin') {

        } else {
            $this->redirect($sns->getRequestCodeURL());
        }

    }

    //callback  登陆后回调地址
    public function callback()
    {

    }

}
