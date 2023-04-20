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
     * @param string $platform 平台
     * @param array  $params   参数
     * @param array  $extend   会员扩展信息
     * @param int    $keeptime 有效时长
     * @return boolean
     *
     */
    public static function connect($platform, $params = [], $extend = [], $keeptime = 0)
    {
        $time     = time();
        $token    = $params['token'];
        $nickname = $params['nickname'] ?? '';
        $avatar   = $params['avatar'] ?? '';
        $data     = [
            'openid'        => $token['openid'],
            'access_token'  => $token['access_token'],
            'refresh_token' => $token['refresh_token'] ?? '',
            'expires_in'    => $token['expires_in'] ?? '',
            'openname'      => $nickname,
            'platform'      => $platform,
            'login_time'    => $time,
            'expire_time'   => isset($token['expires_in']) ? $time + $token['expires_in'] : '',
            'unionid'       => $params['unionid'] ?? '',
        ];
        $data = array_merge($data, $params);

        $auth = \app\member\service\User::instance();
        //查询是否有第三方登录记录
        $third = SyncLoginModel::get(['platform' => $platform, 'openid' => $token['openid']], 'member');
        if ($third) {
            if (!$third->member) {
                //删除不存在会员记录
                $third->delete();
            } else {
                $third->allowField(true)->save($data);
                // 写入登录Cookies和Token
                return $auth->direct($third->uid);
            }
        }

        //存在unionid就需要判断是否需要生成新记录 QQ和微信、淘宝可以获取unionid
        if (isset($params['unionid']) && !empty($params['unionid'])) {
            $third = SyncLoginModel::get(['platform' => $platform, 'unionid' => $params['unionid']], 'member');
            if ($third) {
                if (!$third->member) {
                    $third->delete();
                } else {
                    // 保存第三方信息
                    $data['uid'] = $third->uid;
                    $third       = SyncLoginModel::create($data, true);
                    // 写入登录Cookies和Token
                    return $auth->direct($third->uid);
                }
            }
        }
        if ($auth->id) {
            if (!$third) {
                $data['uid'] = $auth->id;
                SyncLoginModel::create($data, true);
            }
            $user = $auth->getUser();
        } else {
            //先随机一个用户名,随后再变更为u+数字id
            $username = genRandomString(10);
            $password = genRandomString(6);
            $domain   = request()->host();
            $uid      = $auth->userRegister($username, $password, $username . '@' . $domain, '', $extend);
            if ($uid > 0) {
                $user   = $auth->getUser();
                $fields = ['username' => 'u' . $uid, 'email' => 'u' . $uid . '@' . $domain];
                if ($nickname) {
                    $fields['nickname'] = $nickname;
                }
                if ($avatar) {
                    $fields['avatar'] = htmlspecialchars(strip_tags($avatar));
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

    /**
     * 是否绑定第三方
     */
    public static function isBindThird($platform, $openid, $apptype = '', $unionid = '')
    {
        $conddtions = [
            'platform' => $platform,
            'openid'   => $openid,
        ];
        if ($apptype) {
            $conddtions['apptype'] = $apptype;
        }
        $third = SyncLoginModel::get($conddtions, 'member');
        //第三方存在
        if ($third) {
            //用户失效
            if (!$third->member) {
                $third->delete();
                return false;
            }
            return true;
        }
        if ($unionid) {
            $third = SyncLoginModel::get(['platform' => $platform, 'unionid' => $unionid], 'member');
            if ($third) {
                //
                if (!$third->member) {
                    $third->delete();
                    return false;
                }
                return true;
            }
        }
        return false;
    }
}
