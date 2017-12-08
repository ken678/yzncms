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
namespace app\member\controller;

use app\common\controller\Base;
use think\Config;
use think\Db;
use think\Request;

/**
 * 前台会员
 */
class Memberbase extends Base
{
    //用户id
    protected $userid = 0;
    //用户信息
    protected $userinfo = array();

    public function __construct()
    {
        //会员模板
        $config['template'] = Config::get('template');
        $Theme = empty(self::$Cache["Config"]['theme']) ? 'default' : self::$Cache["Config"]['theme'];
        $config['template']['view_path'] = TEMPLATE_PATH . $Theme . '/member/';
        Config::set($config);
        parent::__construct();
    }

    protected function _initialize()
    {
        parent::_initialize();
        //登陆检测
        $this->check_member();
        //============全局模板变量==============
        $this->memberModel = cache("Model_Member"); //会员模型
        $this->memberConfig = cache("Member_Config"); //会员配置
    }

    /**
     * 检测用户是否已经登陆
     */
    final public function check_member()
    {
        $this->userid = is_login();
        $request = Request::instance();
        //该类方法不需要验证是否登陆
        if ($request->module() == 'member' && $request->controller() == 'Index' && in_array($request->action(), array('login', 'register', 'logout'))) {
            return true;
        };
        if ($this->userid) {
            //  获取用户信息
            $this->userinfo = Db::name('Member')->find($this->userid);
            //  判断用户是否被锁定
            if ($this->userinfo['status'] !== 1) {
                $this->error("您的帐号已经被锁定！", url('/'));
            }
            return true;
        } else {
            // 还没登录 跳转到登录页面
            $this->redirect('member/index/login');
        }
    }

    /**
     * 信息提示
     * @access protected
     * @param string $message 错误信息，可以是错误代码
     * @param array $data 附带数据
     * @param $type error 错误提示，success，普通小心提示
     * @return void
     */
    protected function showMessage($message = '', $data = array(), $type = 'success')
    {
        //如果是错误代码
        if (is_numeric($message)) {
            switch ($message) {
                case 1:
                    $message = '名字中带有禁用词，请更换一个！';
                    $data['error'] = 1;
                    break;
                case 2:
                    $message = '名字不能超过12个字母或6个汉字！';
                    $data['error'] = 2;
                    break;
                case 3:
                    $message = '名字中至少要含有一个字母或汉字！';
                    $data['error'] = 3;
                    break;
                case 4:
                    $message = '输入的电子邮箱格式不正确！';
                    $data['error'] = 4;
                    break;
                case 10000:
                    $message = '操作成功！';
                    $data['error'] = 10000;
                    break;
                case 10005:
                    $message = '登陆账号不能为空！';
                    $data['error'] = 10005;
                    break;
                case 10006:
                    $message = '登陆密码不能为空！';
                    $data['error'] = 10006;
                    break;
                case 10007:
                    $message = '分组名称不能为空！';
                    $data['error'] = 10007;
                    break;
                case 1011:
                    $message = '该帐号已经存在，请更换一个！';
                    $data['error'] = 1011;
                    break;
                case 20001:
                    $message = '您没有登录或已经退出，请登录后再进行操作 ！';
                    $data['error'] = 20001;
                    break;
                case 20002:
                    $message = '您没有权限进行操作 ！';
                    $data['error'] = 20002;
                    break;
                case 20011:
                    $message = '此邮箱已经存在，请更换一个！';
                    $data['error'] = 20011;
                    break;
                case 20014:
                    $message = '该账号已被锁定！';
                    $data['error'] = 20014;
                    break;
                case 20015:
                    $message = '邮箱账号未激活！';
                    $data['error'] = 20015;
                    break;
                case 20021:
                    $message = '两次输入密码不相同！';
                    $data['error'] = 20021;
                    break;
                case 20022:
                    $message = '当前密码不正确，请从新输入！';
                    $data['error'] = 20022;
                    break;
                case 20023:
                    $message = '账号或密码错误！';
                    $data['error'] = 20023;
                    break;
                case 20024:
                    $message = '请输入您的密码！';
                    $data['error'] = 20024;
                    break;
                case 20025:
                    $message = '密码长度应是6位以上！';
                    $data['error'] = 20025;
                    break;
                case 20031:
                    $message = '验证码错误！';
                    $data['error'] = 20031;
                    break;
                default:
                    break;
            }
        }
        //如果是数组
        if (is_array($message)) {
            $info = $message['info'];
            $error = $message['error'];
            $message = $info;
            $data['error'] = $error;
        }
        if ('success' == $type || $type == true) {
            $this->success($message);
        } else {
            $this->error($message);
        }
        exit();
    }

}
