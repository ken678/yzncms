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
// | 第三方服务类
// +----------------------------------------------------------------------
namespace addons\synclogin\library;

use addons\synclogin\model\SyncLogin as SyncLoginModel;
use app\member\model\Member as MemberModel;

class Service
{
    /**
     * 第三方登录
     * @param string $type 平台
     * @param array  $params   参数
     * @param array  $extend   会员扩展信息
     * @param int    $keeptime 有效时长
     * @return boolean
     *
     */
    public static function connect($type, $params = [], $extend = [], $keeptime = 0)
    {
        $token = $params['token'];
        $data  = [
            'openid'       => $token['openid'],
            'access_token' => $token['access_token'],
            'type'         => $type,
            'logint_time'  => time(),
        ];
        $auth = \app\member\service\User::instance();
        //查询是否有第三方登录记录
        $third = SyncLoginModel::get(['type' => $type, 'openid' => $token['openid']], 'member');
        if ($third) {
            if (!$third->member) {
                //删除不存在会员记录
                $third->delete();
            } else {
                $third->allowField(true)->save($values);
                // 写入登录Cookies和Token
                return $auth->direct($third->uid);
            }
        }
        if ($auth->id) {
            if (!$third) {
                $data['uid'] = $auth->id;
                SyncLoginModel::create($values, true);
            }
            $user = $auth->getUser();
        } else {
            //先随机一个用户名,随后再变更为u+数字id
            $username = genRandomString(10);
            $password = genRandomString(6);
            $domain   = request()->host();
            $uid      = $auth->userRegister($username, $password, $username . '@' . $domain);
            if ($uid > 0) {
                $user   = $auth->getUser();
                $fields = ['username' => 'u' . $uid, 'email' => 'u' . $uid . '@' . $domain];
                if (isset($$user_info['nickname'])) {
                    $fields['nickname'] = $$user_info['nickname'];
                }
                if (isset($$user_info['avatar'])) {
                    $fields['avatar'] = htmlspecialchars(strip_tags($user_info['avatar']));
                }
                // 更新会员资料
                $user = MemberModel::get($user->id);
                $user->save($fields);
                // 记录数据到sync_login表中
                $data['uid'] = $user->id;
                SyncLoginModel::create($data, true);
            } else {
                $auth->logout();
                return false;
            }
        }
        // 写入登录Cookies和Token
        return $auth->direct($user->id);
    }

    public static function isBindThird($type, $openid, $apptype = '', $unionid = '')
    {

    }
}
