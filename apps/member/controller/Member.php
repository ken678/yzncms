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

use app\common\controller\Adminbase;
use app\user\api\UserApi;

/**
 * 会员管理
 */
class Member extends Adminbase
{
    //会员用户组缓存
    protected $groupCache = array();
    //会员模型
    protected $groupsModel = array();
    //会员数据模型
    protected $member = null;

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->groupCache = cache("Member_group");
        $this->groupsModel = cache("Model_Member");
    }

    public function manage()
    {
        return $this->fetch();
    }

    //添加会员
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->request->param();
            dump($post);
            exit();
            /* 调用注册接口注册用户 */
            $User = new UserApi;
            $uid = $User->register($username, $password, $email, '', 'admin');
            if (0 < $uid) {
                //注册成功
                $user = array('uid' => $uid, 'nickname' => $username, 'status' => 1);
                if (!db('Member', [], false)->insert($user)) {
                    $this->error('用户添加失败！');
                } else {
                    $this->success('用户添加成功！', url('index'));
                }
            } else {
                //注册失败，显示错误信息
                $this->error($uid);
            }
        } else {
            foreach ($this->groupCache as $g) {
                if (in_array($g['groupid'], array(8, 1, 7))) {
                    continue;
                }
                $groupCache[$g['groupid']] = $g['name'];
            }
            foreach ($this->groupsModel as $m) {
                $groupsModel[$m['modelid']] = $m['name'];
            }
            $this->assign('groupCache', $groupCache);
            $this->assign('groupsModel', $groupsModel);
            return $this->fetch();
        }
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0)
    {
        switch ($code) {
            case -1:$error = '用户名长度必须在16个字符以内！';
                break;
            case -2:$error = '用户名被禁止注册！';
                break;
            case -3:$error = '用户名被占用！';
                break;
            case -4:$error = '密码长度必须在6-30个字符之间！';
                break;
            case -5:$error = '邮箱格式不正确！';
                break;
            case -6:$error = '邮箱长度必须在1-32个字符之间！';
                break;
            case -7:$error = '邮箱被禁止注册！';
                break;
            case -8:$error = '邮箱被占用！';
                break;
            case -9:$error = '手机格式不正确！';
                break;
            case -10:$error = '手机被禁止注册！';
                break;
            case -11:$error = '手机号被占用！';
                break;
            default:$error = '未知错误';
        }
        return $error;
    }

}
